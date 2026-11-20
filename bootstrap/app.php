<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Aquí registras tus middlewares personalizados (aliases)
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'consulta' => \App\Http\Middleware\RolConsulta::class,
            // Puedes agregar más si quieres, ejemplo:
            // 'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        // Opcional: si quieres agregar middleware global o a grupos específicos
        // $middleware->web(append: \App\Http\Middleware\TuMiddleware::class);
        // $middleware->api(prepend: \App\Http\Middleware\OtroMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();