<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Landing Page
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('landing');
})->name('landing');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Dashboard - accessible by all authenticated users
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Customers - accessible by both admin and staff (write ops guarded in component)
    Route::get('/customers', function () {
        return view('customers');
    })->name('customers');

    // Services - admin only
    Route::get('/services', function () {
        return view('services');
    })->middleware('role:admin')->name('services');
});
