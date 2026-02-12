<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DocumentController,
    ProfileController,
    DashboardController,
    UserController,
    ActivityLogController
};

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('welcome'));

/*
|--------------------------------------------------------------------------
| PUBLIC PREVIEW
|--------------------------------------------------------------------------
*/

Route::get(
    '/documents/version/{version}/public-preview',
    [DocumentController::class,'publicPreview']
)->name('documents.publicPreview');


/*
|--------------------------------------------------------------------------
| AUTHENTICATED USERS
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    | Dashboard
    */
    Route::get('/dashboard',[DashboardController::class,'index'])
        ->name('dashboard');


    /*
    | Profile
    */
    Route::prefix('profile')->group(function () {

        Route::get('/',[ProfileController::class,'edit'])->name('profile.edit');
        Route::patch('/',[ProfileController::class,'update'])->name('profile.update');
        Route::delete('/',[ProfileController::class,'destroy'])->name('profile.destroy');

    });


    /*
    | Documents (ALL ROUTES — permission handled in controller)
    */
    Route::resource('documents', DocumentController::class);


    /*
    | File Actions
    */
    Route::prefix('documents/version')->group(function () {

        Route::get('{id}/download',[DocumentController::class,'download'])
            ->name('documents.download');

        Route::get('{id}/preview',[DocumentController::class,'preview'])
            ->name('documents.preview');

    });

});


/*
|--------------------------------------------------------------------------
| ADMIN ONLY
|--------------------------------------------------------------------------
*/

Route::middleware(['auth','role:admin'])->group(function () {

    Route::get('/activity-logs',[ActivityLogController::class,'index'])
        ->name('activity.index');

    Route::resource('users',UserController::class);

});


require __DIR__.'/auth.php';