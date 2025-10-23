<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password; 

class RegisterRequest extends FormRequest
{
    /**
     * Determinar si el usuario esta autorizado para hacer esta solicitud.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Traer las reglas de validacion para la solicitud.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'confirmed',
                // Usamos un minimo de criterios para mayor seguridad
                Password::min(8) 
                    ->letters()     
                    ->mixedCase()   
                    ->numbers()     
                    ->symbols()     
            ],
        ];
    }
}