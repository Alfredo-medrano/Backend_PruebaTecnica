<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

// Rutas Protegidas por JWT 
Route::middleware('auth:api')->group(function () {
    
    // Route::apiResource para crear las rutas RESTful estándar (index, store, update, destroy)
    Route::apiResource('tasks', TaskController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);
});