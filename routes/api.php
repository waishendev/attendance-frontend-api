<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeavesController;

Route::post('/auth/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn']);
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut']);
    Route::get('/attendance/my', [AttendanceController::class, 'myLogs']);

    Route::get('/leaves/my', [LeavesController::class, 'index']);
    Route::post('/leaves', [LeavesController::class, 'store']);
    Route::put('/leaves/{id}/cancel', [LeavesController::class, 'cancel']);
});
