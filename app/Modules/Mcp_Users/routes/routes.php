<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Mcp_Users\Controllers\adminController;

Route::middleware(['auth:sanctum', 'ability:admin,moderator'])->group(function () {
    Route::get('mcp/users', [adminController::class, 'index']);
    Route::get('mcp/users/{id}', [adminController::class, 'show']);
    Route::put('mcp/users/block/{id}', [adminController::class, 'blockToggle']);
    // Route::delete('mcp/users/{id}', [adminController::class, 'delete']);

    // withdraw requests
    Route::get('mcp/withdraw-requests', [adminController::class, 'withdrawRequests']);
    Route::get('mcp/withdraw-requests/{id}', [adminController::class, 'withdrawRequestShow']);
    // reject withdraw request
    Route::put('mcp/withdraw-requests/reject/{id}', [adminController::class, 'rejectWithdrawRequest']);
    // approve withdraw request
    Route::post('mcp/withdraw-requests/approve', [adminController::class, 'approveWithdrawRequest']);
});


