<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\Auth\UserController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\TableController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;

// Authentication
Route::post('/login', [AuthController::class, 'login']);

// Password reset / OTP
Route::post('/forgot-password', [PasswordResetController::class, 'sendOtp']);
Route::post('/verify-otp', [PasswordResetController::class, 'verifyOtp']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);

// Categories
Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::get('/categories/{id}', [CategoryController::class, 'show'])->whereNumber('id');
Route::put('/categories/{id}', [CategoryController::class, 'update'])->whereNumber('id');
Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->whereNumber('id');

// Products
Route::get('/products', [ProductController::class, 'index']);
Route::post('/products', [ProductController::class, 'store']);
Route::get('/products/{id}', [ProductController::class, 'show'])->whereNumber('id');
Route::put('/products/{id}', [ProductController::class, 'update'])->whereNumber('id');
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->whereNumber('id');

// Orders
Route::get('/orders', [OrderController::class, 'index']);
Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders/{id}', [OrderController::class, 'show'])->whereNumber('id');
Route::put('/orders/{id}', [OrderController::class, 'update'])->whereNumber('id');
Route::delete('/orders/{id}', [OrderController::class, 'delete'])->whereNumber('id');

// Users
Route::apiResource('users', UserController::class)
    ->whereNumber('user');

// Tables
Route::apiResource('tables', TableController::class)
    ->whereNumber('table');

// otp
Route::post('/login', [AuthController::class, 'login']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);