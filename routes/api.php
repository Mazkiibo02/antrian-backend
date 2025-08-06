<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicQueueController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QueueController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public Queue API Routes
Route::prefix('public/queue')->group(function () {
    Route::get('/now', [PublicQueueController::class, 'getCurrentQueue']);
    Route::get('/list', [PublicQueueController::class, 'getWaitingQueues']);
});

// Admin Authentication API Routes
Route::prefix('admin')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:admin');
    
    // Protected Admin Routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AuthController::class, 'dashboard']);
        Route::get('/queues', [QueueController::class, 'index']);
        Route::get('/queues/current', [QueueController::class, 'current']);
        Route::post('/queues/next', [QueueController::class, 'next']);
        Route::post('/queues/previous', [QueueController::class, 'previous']);
        Route::post('/queues', [QueueController::class, 'store']);
        Route::put('/queues/{queue}', [QueueController::class, 'update']);
        Route::delete('/queues/{queue}', [QueueController::class, 'destroy']);
    });
}); 