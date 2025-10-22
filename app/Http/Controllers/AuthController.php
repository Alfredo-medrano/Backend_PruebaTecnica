<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class AuthController extends Controller
{
    //
    public function register(RegisterRequest $request)
    {
        $user = User::created([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        /*
        Generar el token JWT para el usuario registrado
        */
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'Usuario registrado y autenticado con exito',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

   public function login(Request $request)
    {
       $credentials = $request->validate([
           'email' => 'required|email',
           'password' => 'required|string|min:8',
       ]);

        /*
           Intentar autenticar al usuario y generar el token JWT
        */
          if (! $token = JWTAuth::attempt($credentials)) {
              return response()->json(['error' => 'Credenciales invalidas'], 401);
          }


          
           return response()->json([
               'token' => $token,
               'token_type' => 'bearer',
               'expires_in' => JWTAuth::factory()->getTTL() * 60 
           ], 200);
    }
}
