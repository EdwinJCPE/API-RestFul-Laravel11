<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Middleware\TransformInput;
use App\Http\Middleware\SignatureMiddleware;
use App\Http\Middleware\CustomThrottleRequests;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Laravel\Passport\Http\Middleware\CheckClientCredentials;
use Laravel\Passport\Http\Middleware\CheckForAnyScope;
use Laravel\Passport\Http\Middleware\CheckScopes;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // web: __DIR__.'/../routes/web.php',
        // api: __DIR__.'/../routes/api.php',
        // apiPrefix: 'api',
        using: function () {
            // Route::middleware('api')
            //     ->prefix('api')
            //     ->group(base_path('routes/api.php'));

            Route::middleware('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        },
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // $middleware->append(HandleCors::class);  // Middleware global (afecta a TODAS las rutas) - No es necesario ya que se esta registrando con un alias líneas más abajo

        // // Reemplazar el middleware de throttling en las rutas API
        // // Solo se reemplaza la clase ThrottleRequests internamente en los grupos de middleware web o api, pero el alias throttle sigue apuntando al middleware original. La solución es reemplazar a nivel de alias en AppServiceProvider.
        // $middleware->api(replace: [
        //     ThrottleRequests::class => CustomThrottleRequests::class,
        // ]);

        $middleware->alias([
            // 'throttle' => ThrottleRequests::class, // Este registro manual es inecesario. Laravel ya tiene registrado ThrottleRequests con el alias throttle por defecto.
            'scope' => CheckForAnyScope::class,
            'scopes' => CheckScopes::class,
            'signature' => SignatureMiddleware::class,
            'transform.input' => TransformInput::class,
            'cors' => HandleCors::class,
            'client.credentials' => CheckClientCredentials::class,
        ]);

        $middleware->web(prepend: [
            // SignatureMiddleware::class.':X-Application-Name', // Llama directamente a la clase del middleware SignatureMiddleware. No necesita alias
            'signature:X-Application-Name',
        ]);

        $middleware->api(prepend: [
            'cors',
            // SignatureMiddleware::class.':X-Application-Name',
            'signature:X-Application-Name',
            'throttle:api', // Aplicar throttle:api a todas las rutas API
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withSingletons([
        \Illuminate\Contracts\Debug\ExceptionHandler::class => \App\Exceptions\Handler::class,
    ])
    ->create();
