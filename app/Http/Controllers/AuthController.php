<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Services\AuthService; 
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\JsonResponse; 

class AuthController extends Controller
{
    // Inyección de dependencias del servicio
    public function __construct(protected AuthService $authService)
    {
    }

    /**
     * Registra un nuevo usuario.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->registerUser($request->validated());

        return response()->json([
            'message' => 'Usuario registrado y autenticado con exito',
            'user' => $result['user'], 
            'token' => $result['token'], 
        ], 201);
    }

    /**
     * Autentica un usuario y devuelve un token.
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Delegamos la lógica al servicio
        $token = $this->authService->attemptLogin($credentials);

        if (!$token) {
            return response()->json(['error' => 'Credenciales invalidas'], 401);
        }

        // Si hay token lo devolvemos
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ], 200);
    }

    public function logout(): JsonResponse
    {
        try {
            // Invalida el token que se envió en la petición
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                'message' => 'Sesión cerrada con exito. Token invalidado.'
            ], 200);

        } catch (\Exception $e) {
            // Esto captura errores si el token no se pudo invalidar (ej. ya estaba invalidado)
            return response()->json([
                'message' => 'Ocurrió un error al cerrar la sesión o el token ya es inválido.'
            ], 500);
        }
    }
}