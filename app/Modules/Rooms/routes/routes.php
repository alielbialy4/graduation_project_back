<?php

use App\Modules\Rooms\Controllers\adminController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'abilities:user'])->group(function () {
    Route::resource('user/rooms', adminController::class);
});
