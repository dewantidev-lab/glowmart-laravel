<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});


/*
|--------------------------------------------------------------------------
| Product Routes
|--------------------------------------------------------------------------
*/

// Bisa diakses tanpa login
Route::apiResource('products', ProductController::class)
    ->only(['index', 'show']);

// Harus menggunakan token
Route::apiResource('products', ProductController::class)
    ->except(['index', 'show'])
    ->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| Category Routes
|--------------------------------------------------------------------------
*/

// Bisa diakses tanpa login
Route::apiResource('categories', CategoryController::class)
    ->only(['index', 'show']);

// Harus menggunakan token
Route::apiResource('categories', CategoryController::class)
    ->except(['index', 'show'])
    ->middleware('auth:sanctum');