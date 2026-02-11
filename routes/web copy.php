<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| PUBLIC PREVIEW (Google Viewer)
|--------------------------------------------------------------------------
*/
Route::get(
    '/documents/version/{version}/public-preview',
    [DocumentController::class, 'publicPreview']
)->name('documents.publicPreview');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED USERS (ADMIN + STAFF)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    | Dashboard
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    | Profile
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | STAFF & ADMIN - VIEW ONLY
    |--------------------------------------------------------------------------
    */
    Route::resource('documents', DocumentController::class)
        ->only(['index','show']);

    Route::get(
        '/documents/version/{id}/download',
        [DocumentController::class, 'download']
    )->name('documents.download');

    Route::get(
        '/documents/version/{id}/preview',
        [DocumentController::class, 'preview']
    )->name('documents.preview');
});


/*
|--------------------------------------------------------------------------
| ADMIN ONLY
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','role:admin'])->group(function () {

    Route::resource('documents', DocumentController::class)
        ->only(['create','store','edit','update','destroy']);

    Route::post(
        '/documents/{document}/revisi',
        [DocumentController::class, 'addVersion']
    )->name('documents.revisi');

});

require __DIR__.'/auth.php';
