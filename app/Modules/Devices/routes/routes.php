<?php

use App\Modules\Devices\Controllers\adminController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('user/devices', adminController::class);
});
