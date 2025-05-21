<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // 👈 rutas API agregadas
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
   ->withMiddleware(function (Middleware $middleware) {
    // Middleware global
    $middleware->use([
        \Illuminate\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Http\Middleware\ValidatePostSize::class,
        \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ]);

    // Middleware de grupo API
    $middleware->api([
        'throttle',
        \Illuminate\Http\Middleware\HandleCors::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ]);

    // Aliases
    $middleware->alias([
        'auth:sanctum' => \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    ]);
})
->withExceptions(function (Exceptions $exceptions) {
    $exceptions->render(function (Throwable $e, Illuminate\Http\Request $request) {
        if ($request->is('api/*') || $request->expectsJson()) {
            $statusCode = 500;

            if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                $statusCode = 401;
                return response()->json(['message' => 'No autenticado'], $statusCode);
            }

            if ($e instanceof \Illuminate\Validation\ValidationException) {
                $statusCode = 422;
                return response()->json([
                    'message' => 'Error de validación',
                    'errors' => $e->errors(),
                ], $statusCode);
            }

            return response()->json([
                'message' => $e->getMessage(),
            ], $statusCode);
        }
    });
})
    ->create();
