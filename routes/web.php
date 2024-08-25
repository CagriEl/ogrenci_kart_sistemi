<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return view('students.index');

});

Route::post('/store', [StudentController::class, 'store'])->name('students.store');

// Admin Paneli Rotaları (Sadece Giriş Yapmış Kullanıcılar Erişebilir)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/students', [StudentController::class, 'adminIndex'])->name('admin.students.index');
    Route::get('/admin/students/{student}/edit', [StudentController::class, 'edit'])->name('admin.students.edit');
    Route::put('/admin/students/{student}', [StudentController::class, 'update'])->name('admin.students.update');
    Route::delete('/admin/students/{student}', [StudentController::class, 'destroy'])->name('admin.students.destroy');
    Route::get('/admin/students/basilan-kartlar', [StudentController::class, 'basilanKartlar'])->name('admin.students.basilan_kartlar');
    Route::get('/admin/students/sicil-olusturulanlar', [StudentController::class, 'sicilOlusturulanlar'])->name('admin.students.sicil_olusturulanlar');
    Route::get('/admin/students/{id}/edit', [StudentController::class, 'edit'])->name('admin.students.edit');

});

// Logout Route (Çıkış)
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/admin/approved-students', [StudentController::class, 'approvedStudents'])->name('admin.students.approved');

Route::get('/admin/students/{id}/download/{file_type}', [StudentController::class, 'downloadFile'])->name('admin.students.download');
Route::post('/admin/students/update-status/{id}', [StudentController::class, 'updateStatus'])->name('admin.students.update_status');
