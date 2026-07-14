<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\Auth\UserController; 
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\TableController;

// Authentication & OTP Routes
Route::post('/login', [AuthController::class, 'login']);
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Http;
use Mockery\Generator\StringManipulation\Pass\Pass;

// Authentication & OTP Routes
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

Route::get('/orders', [OrderController::class, 'index']);
Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders/{id}', [OrderController::class, 'show']);
Route::put('/orders/{id}', [OrderController::class, 'update']);
Route::delete('/orders/{id}', [OrderController::class, 'delete']);

Route::post('/verify-otp', [PasswordResetController::class, 'verifyOtp']);
Route::post('/forgot-password', [PasswordResetController::class, 'sendOtp']);
Route::post('/verify-otp', [PasswordResetController::class, 'verifyOtp']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);

// User CRUD Routes
Route::apiResource('users', UserController::class);
Route::apiResource('tables', TableController::class);

Route::get('/telegram-test', function () {
    $token = config('services.telegram.bot_token');
    $chatId = config('services.telegram.chat_id');

    if (empty($token) || empty($chatId)) {
        return response()->json([
            'success' => false,
            'token_loaded' => !empty($token),
            'chat_id_loaded' => !empty($chatId),
            'message' => 'Telegram configuration is missing.',
        ]);
    }

    try {
        $response = Http::timeout(10)->post(
            "https://api.telegram.org/bot{$token}/sendMessage",
            [
                'chat_id' => $chatId,
                'text' => '✅ Laravel Telegram connection test',
            ]
        );

        return response()->json([
            'http_status' => $response->status(),
            'telegram_response' => $response->json(),
        ]);
    } catch (\Throwable $exception) {
        return response()->json([
            'success' => false,
            'exception' => $exception->getMessage(),
        ]);
    }
});

Route::get('/error-test', function () {
    throw new \RuntimeException(
        'This is a test error for Telegram notification.'
    );
});