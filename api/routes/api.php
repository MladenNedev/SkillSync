<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;

// This route is required by Sanctum SPA auth
// Returns the authenticated user.

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// Dashboard routes

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/dashboard/summary', [DashboardController::class, 'summary']);
    Route::get('/dashboard/chart', [DashboardController::class, 'chart']);
    Route::get('/dashboard/recent-courses', [DashboardController::class, 'recentCourses']);
});
