<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
        // 1. Configuración del grupo 'api' para evitar Rate Limiting (Throttle)
        // Esto anula la configuración por defecto que podría estar causando el 403.
        $middleware->api(prepend: [
            // Aquí puede añadir el middleware de CORS si es necesario (ej: \Fruitcake\Cors\HandleCors::class)
            // Pero para las pruebas iniciales, lo dejamos limpio para evitar más conflictos.
        ]);

        // 2. Agregar alias para JWT
        // Esto asegura que el middleware 'auth:api' que usa en routes/api.php
        // apunte correctamente a la clase de autenticación de JWT.
        $middleware->alias([
            'auth:api' => \Tymon\JWTAuth\Http\Middleware\Authenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
