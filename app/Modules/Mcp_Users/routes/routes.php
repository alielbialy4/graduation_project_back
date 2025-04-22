<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Mcp_Users\Controllers\adminController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('mcp/users', [adminController::class, 'index']);
    Route::get('mcp/users/{id}', [adminController::class, 'show']);
    // delete user
    Route::delete('mcp/users/{id}', [adminController::class, 'delete']);

});
