<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Log;

class AuthService
{
    /**
     * Registra un nuevo usuario y genera un token.
     *
     * @param array $data Datos validados del request.
     * @return array Contiene 'user' y 'token'.
     */
    public function registerUser(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = JWTAuth::fromUser($user);

        return ['user' => $user, 'token' => $token];
    }

    /**
     * Intenta autenticar a un usuario y devolver un token JWT.
     *
     * @param array $credentials Credenciales (email y password).
     * @return string|null El token JWT o null si falla.
     */
    public function attemptLogin(array $credentials): ?string
    {
        try {
            // Intenta generar un token con las credenciales
            if (! $token = JWTAuth::attempt($credentials)) {
                return null; // Credenciales inválidas
            }
        } catch (JWTException $e) {
            // Error interno si JWT no puede crear el token
            Log::error('Error al crear token JWT: ' . $e->getMessage());
            return null;
        }
        
        return $token;
    }
}