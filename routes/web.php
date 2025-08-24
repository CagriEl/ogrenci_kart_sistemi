<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\FormController;

// Ziyaretçi ana sayfa (form)
Route::get('/', function () {
    return view('students.index'); // resources/views/students/index.blade.php
});

// Başvuru kaydet (public POST)
Route::post('/students', [StudentController::class, 'store'])->name('students.store');

// Kategori partial (AJAX) - public GET
Route::get('/kategori-form/{kategori}', [FormController::class, 'kategoriForm'])->name('kategori.form');

// Admin rotaları (GİRİŞ ŞART)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/students', [StudentController::class, 'adminIndex'])->name('admin.students.index');

    Route::get('/admin/students/{student}/edit', [StudentController::class, 'edit'])->name('admin.students.edit');
    Route::put('/admin/students/{student}', [StudentController::class, 'update'])->name('admin.students.update');
    Route::delete('/admin/students/{student}', [StudentController::class, 'destroy'])->name('admin.students.destroy');

    Route::get('/admin/students/{id}/download/{file_type}', [StudentController::class, 'downloadFile'])->name('admin.students.download');
    Route::post('/admin/students/update-status/{id}', [StudentController::class, 'updateStatus'])->name('admin.students.update_status');

    Route::get('/admin/students/basilan-kartlar', [StudentController::class, 'basilanKartlar'])->name('admin.students.basilan_kartlar');
    Route::get('/admin/students/sicil-olusturulanlar', [StudentController::class, 'sicilOlusturulanlar'])->name('admin.students.sicil_olusturulanlar');
    Route::get('/admin/students/eksik-belge', [StudentController::class, 'eksikBelgeOlanlar'])->name('admin.students.eksik_belge');
Route::post('/admin/students/{id}/send-eksik-belge', [StudentController::class, 'sendEksikBelgeMail'])
        ->name('admin.students.send_eksik_belge');
});

// Auth rotaları (login/register vs.)
Auth::routes();

// İsteğe bağlı: /home
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
