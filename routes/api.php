<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\Auth\UserController; 
use App\Http\Controllers\Api\Auth\PasswordResetController;

// Authentication & OTP Routes
Route::post('/login', [AuthController::class, 'login']);
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Mockery\Generator\StringManipulation\Pass\Pass;

Route::post('/login', [AuthController::class, 'login']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::put('/categories/{id}', [CategoryController::class, 'update']);
Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

Route::get('/products', [ProductController::class, 'index']);
Route::post('/products', [ProductController::class, 'store']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::put('/products/{id}', [ProductController::class, 'update']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);


Route::post('/verify-otp', [PasswordResetController::class, 'verifyOtp']);
Route::post('/forgot-password', [PasswordResetController::class, 'sendOtp']);
Route::post('/verify-otp', [PasswordResetController::class, 'verifyOtp']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);

// User CRUD Routes
Route::apiResource('users', UserController::class);