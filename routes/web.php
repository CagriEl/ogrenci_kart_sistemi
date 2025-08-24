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



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

Route::match(['get','post'], '/upload-test', function (Request $request) {
    if ($request->isMethod('get')) {
        return <<<HTML
<!DOCTYPE html><html lang="tr"><body>
<h3>Upload Test</h3>
<form method="POST" enctype="multipart/form-data">
<input type="hidden" name="_token" value="{$request->session()->token()}">
<input type="file" name="vesikalik" accept="image/*" required>
<button type="submit">Yükle</button>
</form>
</body></html>
HTML;
    }

    if (!$request->hasFile('vesikalik')) {
        \Log::warning('UploadTest: hasFile=false', ['files' => $request->allFiles()]);
        return 'DOSYA GELMEDİ (hasFile=false)';
    }

    $file = $request->file('vesikalik');
    if (!$file->isValid()) {
        \Log::warning('UploadTest: isValid=false', [
            'err_code' => $file->getError(),
            'err_msg'  => $file->getErrorMessage(),
            'tmp_dir'  => sys_get_temp_dir(),
            'ini'      => [
                'upload_max_filesize' => ini_get('upload_max_filesize'),
                'post_max_size'       => ini_get('post_max_size'),
                'file_uploads'        => ini_get('file_uploads'),
            ],
        ]);
        return 'GEÇERSİZ YÜKLEME: err_code=' . $file->getError() . ' tmp=' . sys_get_temp_dir();
    }

    // Güvenli kayıt
    $path = Storage::disk('public')->putFile('vesikalik_test', $file);
    return 'OK: ' . $path;
});
