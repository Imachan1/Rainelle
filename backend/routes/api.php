<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InsightsController;
use App\Http\Controllers\MoodEntryController;
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::apiResource('mood-entries', MoodEntryController::class);
    Route::get('/weather/current', [WeatherController::class, 'current']);
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/insights', [InsightsController::class, 'index']);
});
