<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Mockery\Generator\StringManipulation\Pass\Pass;

Route::post('/login', [AuthController::class, 'login']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::put('/categories/{id}', [CategoryController::class, 'update']);
Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);


Route::post('/verify-otp', [PasswordResetController::class, 'verifyOtp']);
Route::post('/forgot-password', [PasswordResetController::class, 'sendOtp']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);
