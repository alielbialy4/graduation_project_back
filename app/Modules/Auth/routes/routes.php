<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Auth\Controllers\AuthController;
use App\Modules\Auth\Controllers\SocialiteController;

Route::prefix('/user')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    // socialite
    Route::get('login/google/redirect', [SocialiteController::class, 'redirect'])->name('socialite.redirect');
    Route::get('login/google/callback', [SocialiteController::class, 'callback']);
    
    Route::middleware(['auth:sanctum', 'abilities:user'])->group(function () {
        Route::get('/profile/edit', [AuthController::class, 'profile']);
        Route::post('/profile/password/update', [AuthController::class, 'updatePassword']);
        Route::put('/profile/update', [AuthController::class, 'updateProfile']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});
