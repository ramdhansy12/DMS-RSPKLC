<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| AUTH & DASHBOARD
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| USER LOGIN (STAFF RS)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dokumen (lihat & upload)
    Route::resource('documents', DocumentController::class)
        ->only(['index','create','store','show','edit']);

    // Download versi dokumen
    Route::get(
        '/documents/version/{id}/download',
        [DocumentController::class, 'download']
    )->name('documents.download');

    // Revisi dokumen (tambah versi)
    Route::post(
        '/documents/{document}/revisi',
        [DocumentController::class, 'addVersion']
    )->name('documents.revisi');

    Route::get(
    '/documents/version/{id}/preview',
    [DocumentController::class, 'preview']
    )->name('documents.preview');

});

/*
|--------------------------------------------------------------------------
| ADMIN RS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','role:admin'])->group(function () {

    Route::resource('documents', DocumentController::class)
        ->only(['edit','update','destroy']);

    Route::get('/admin', function () {
        return 'ADMIN AREA LARAVEL 12';
    });
});

require __DIR__.'/auth.php';
