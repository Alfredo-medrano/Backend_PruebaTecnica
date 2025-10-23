<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rutas de autenticación con límite de 60 peticiones por minuto

Route::controller(AuthController::class)->middleware('throttle:60,1')->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

// Rutas Protegidas por JWT y limitadas a 100 peticiones por minuto
Route::middleware(['auth:api', 'throttle:100,1'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Usamos apiResource y añadimos 'show'
    Route::apiResource('tasks', TaskController::class)->only([
        'index', 'store', 'show', 'update', 'destroy'
    ]);
});