<?php

use App\Http\Controllers\Api\AiRecommendationController;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiBookingController;
use App\Http\Controllers\Api\ApiServiceController;
use App\Http\Controllers\Api\ApiUserController;
use Illuminate\Support\Facades\Route;

// Public API Routes
Route::post('/auth/register', [ApiAuthController::class, 'register']);
Route::post('/auth/login', [ApiAuthController::class, 'login']);

// E-waste listings and filtering (Publically available)
Route::get('/services', [ApiServiceController::class, 'index']);
Route::get('/services/{id}', [ApiServiceController::class, 'show']);

// AI Endpoints
Route::get('/ai/predict-day', [AiRecommendationController::class, 'predictOptimalDay']);
Route::get('/ai/recommend-points', [AiRecommendationController::class, 'recommendCollectionPoints']);

// Throttled Secure API Route Group
Route::middleware(['throttle:api'])->group(function () {
    
    // Service CRUD for collectors
    Route::post('/services', [ApiServiceController::class, 'store']);
    Route::put('/services/{id}', [ApiServiceController::class, 'update']);
    Route::delete('/services/{id}', [ApiServiceController::class, 'destroy']);

    // Bookings for customers
    Route::get('/bookings', [ApiBookingController::class, 'index']);
    Route::post('/bookings', [ApiBookingController::class, 'store']);

    // Profiles & photo uploads
    Route::put('/user/profile', [ApiUserController::class, 'updateProfile']);
    Route::post('/user/upload-photo', [ApiUserController::class, 'uploadPhoto']);
});
