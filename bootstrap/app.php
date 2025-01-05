<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Middleware\SignatureMiddleware;
use App\Http\Middleware\CustomThrottleRequests;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Middleware\ThrottleRequests;

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
        // Este registro manual es inecesario. Laravel ya tiene registrado ThrottleRequests con el alias throttle por defecto.
        // $middleware->alias([
        //     'throttle' => ThrottleRequests::class,
        // ]); 
        
        // // Reemplazar el middleware de throttling en las rutas API
        // // Solo se reemplaza la clase ThrottleRequests internamente en los grupos de middleware web o api, pero el alias throttle sigue apuntando al middleware original. La solución es reemplazar a nivel de alias en AppServiceProvider.
        // $middleware->api(replace: [
        //     ThrottleRequests::class => CustomThrottleRequests::class,
        // ]);
        
        $middleware->alias([
            'signature' => SignatureMiddleware::class
        ]);

        $middleware->web(prepend: [
            // SignatureMiddleware::class.':X-Application-Name', // Llama directamente a la clase del middleware SignatureMiddleware. No necesita alias
            'signature:X-Application-Name',
        ]);

        $middleware->api(prepend: [
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
