<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Throwable;

class TelegramService
{
    public function sendMessage(string $message): bool
    {
        $token = config('services.telegram.bot_token');
        $chatId = config('services.telegram.chat_id');

        if (empty($token) || empty($chatId)) {
            Log::warning('Telegram bot token or chat ID is not configured.');
            return false;
        }

        try {
            $response = Http::timeout(10)->post(
                "https://api.telegram.org/bot{$token}/sendMessage",
                [
                    'chat_id' => $chatId,
                    'text' => $message,
                ]
            );

            if ($response->failed()) {
                Log::error('Telegram message failed.', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
                return false;
            }
            return true;
        } catch (Throwable $exception) {
            Log::error('Cannot connect to Telegram.', [
                'error' => $exception->getMessage(),
            ]);
            return false;
        }
    }

    public function sendException(Throwable $exception): bool
    {
        if (!config('services.telegram.error_notifications')) {
            return false;
        }

        $isConsole = app()->runningInConsole();

        $method = $isConsole ? 'CLI' : request()->method();
        $url = $isConsole ? 'Console command' : request()->fullUrl();
        $ip  = $isConsole ? 'N/A' : request()->ip();
        $userId = $isConsole ? 'N/A' : (Auth::id() ?? 'Guest');

        $message = implode(PHP_EOL, [
            'SYSTEM ERROR DETECTED',
            '================================',
            '',
            'Project: ' . config('app.name'),
            'Environment: ' . app()->environment(),
            'Time: ' . now()->format('d M Y, h:i:s A'),
            '',
            'ERROR DETAILS',
            'Type: ' . class_basename($exception),
            'Message: ' . $exception->getMessage(),
            '',
            'REQUEST DETAILS',
            'Method: ' . $method,
            'URL: ' . $url,
            'IP Address: ' . $ip,
            '',
            'ERROR LOCATION',
            'File: ' . $exception->getFile(),
            'Line: ' . $exception->getLine(),
            '',
            '================================',
            'Please check the system as soon as possible.',
        ]);
        return $this->sendMessage($message);
    }
}
