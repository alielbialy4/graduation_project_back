<?php

use Illuminate\Support\Facades\Route;
use App\Modules\AdminAuth\Controllers\AuthController;

Route::prefix('/mcp')->group(function () {

    Route::post('/login', [AuthController::class, 'login']);
});
