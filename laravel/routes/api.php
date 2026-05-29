<?php

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public API Routes (unauthenticated)
|--------------------------------------------------------------------------
*/
Route::post('/login', [ApiAuthController::class, 'login'])->name('api.login');

/*
|--------------------------------------------------------------------------
| Protected API Routes (Sanctum token required, rate-limited)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {

    // Current user info
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('api.user');

    // Logout (revoke token)
    Route::post('/logout', [ApiAuthController::class, 'logout'])->name('api.logout');

    // Customer resource routes
    Route::apiResource('customers', CustomerController::class);

    // Service resource routes
    Route::apiResource('services', ServiceController::class);
});
