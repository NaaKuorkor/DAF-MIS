<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        //api: __DIR__.'/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
         $middleware->alias([
        'student' => \App\Http\Middleware\Student::class,
        'staff' => \App\Http\Middleware\Staff::class,
    ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Ensure validation exceptions return JSON for API requests
        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, $request) {
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson() || 
                str_contains($request->header('Accept', ''), 'application/json')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
        });
    })->create();
