<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QueueController;

// Route untuk test koneksi database
Route::get('/test-db', function () {
    try {
        DB::connection()->getPdo();
        return "✅ Database terkoneksi: " . DB::connection()->getDatabaseName();
    } catch (\Exception $e) {
        return "Gagal koneksi database: " . $e->getMessage();
    }
});

Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login']);
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/dashboard', [AuthController::class, 'dashboard'])->name('admin.dashboard');
    
    Route::get('/admin/queues', [QueueController::class, 'index'])->name('admin.queues.index');
    Route::get('/admin/queues/current', [QueueController::class, 'current'])->name('admin.queues.current');
    Route::post('/admin/queues/next', [QueueController::class, 'next'])->name('admin.queues.next');
    Route::post('/admin/queues/previous', [QueueController::class, 'previous'])->name('admin.queues.previous');
    Route::post('/admin/queues', [QueueController::class, 'store'])->name('admin.queues.store');
    Route::put('/admin/queues/{queue}', [QueueController::class, 'update'])->name('admin.queues.update');
    Route::delete('/admin/queues/{queue}', [QueueController::class, 'destroy'])->name('admin.queues.destroy');
});

Route::get('/', function () {
    return redirect('/admin/login');
});
