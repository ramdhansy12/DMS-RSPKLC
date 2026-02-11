<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | DOCUMENTS - VIEW ONLY (STAFF + ADMIN)
    |--------------------------------------------------------------------------
    */
    Route::resource('documents', DocumentController::class)
        ->only(['index', 'show']);

    Route::prefix('documents/version')->group(function () {

        Route::get('{id}/download', [DocumentController::class, 'download'])
            ->name('documents.download');

        Route::get('{id}/preview', [DocumentController::class, 'preview'])
            ->name('documents.preview');

    });
});


/*
|--------------------------------------------------------------------------
| ADMIN ONLY
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','role:admin'])->group(function () {

    /*
    | Full CRUD Dokumen
    */
    Route::resource('documents', DocumentController::class)
        ->only(['create','store','edit','update','destroy']);

    /*
    | Revisi Dokumen
    */
    Route::post(
        '/documents/{document}/revisi',
        [DocumentController::class, 'addVersion']
    )->name('documents.revisi');

    Route::resource('users', UserController::class);

});

require __DIR__.'/auth.php';