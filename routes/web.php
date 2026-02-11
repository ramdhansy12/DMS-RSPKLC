<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| AUTH & DASHBOARD
|--------------------------------------------------------------------------
*/
// Route::get('/dashboard', function () {
//     return redirect()->route('documents.index'); // langsung ke dokumen
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware(['auth', 'verified'])->group(function () {
Route::get('/dashboard', [DocumentController::class, 'dashboard'])
        ->name('dashboard');



Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');



/*
|--------------------------------------------------------------------------
| PUBLIC PREVIEW (KHUSUS GOOGLE VIEWER)
|--------------------------------------------------------------------------
*/
Route::get(
    '/documents/version/{version}/public-preview',
    [DocumentController::class, 'publicPreview']
)->name('documents.publicPreview');

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

    // Dokumen
    Route::resource('documents', DocumentController::class)
        ->only(['index','create','store','show','edit']);

    Route::get(
        '/documents/version/{id}/download',
        [DocumentController::class, 'download']
    )->name('documents.download');

    Route::get(
        '/documents/version/{id}/preview',
        [DocumentController::class, 'preview']
    )->name('documents.preview');

    Route::post(
        '/documents/{document}/revisi',
        [DocumentController::class, 'addVersion']
    )->name('documents.revisi');
});

/*
|--------------------------------------------------------------------------
| ADMIN RS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','role:admin'])->group(function () {
    Route::resource('documents', DocumentController::class)
        ->only(['update','destroy']);
});

require __DIR__.'/auth.php';