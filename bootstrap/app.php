<?php

use App\Services\TelegramService;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Keep this empty.

        // If your project uses Sanctum SPA authentication, you may keep:
        // $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->report(function (\Throwable $exception): void {
            // Ignore normal HTTP errors such as 404.
            if (
                $exception instanceof HttpExceptionInterface &&
                $exception->getStatusCode() < 500
            ) {
                return;
            }

            app(TelegramService::class)->sendException($exception);
        });
    })
    ->create();