<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthTest extends TestCase
{
    // Usamos RefreshDatabase para asegurar que cada test comienza con una DB limpia.
    use RefreshDatabase; 

    protected array $userData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ];

    /**
     * El registro de un nuevo usuario es exitoso (201).
     *
     * @return void
     */
    public function test_user_can_register_successfully()
    {
        $response = $this->postJson('/api/register', $this->userData);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'message',
                     'user' => ['id', 'name', 'email'],
                     'token',
                 ]);

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    /**
     * El registro falla con datos de validación incorrectos (422).
     *
     * @return void
     */
    public function test_registration_fails_with_invalid_data()
    {
        $invalidData = [
            'name' => 'TooShort',
            'email' => 'invalid-email',
            'password' => 'short',
            'password_confirmation' => 'mismatch',
        ];

        $response = $this->postJson('/api/register', $invalidData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email', 'password']);
    }

    /**
     * El inicio de sesión es exitoso y devuelve un token (200).
     *
     * @return void
     */
    public function test_user_can_login_successfully()
    {
        // Crear el usuario antes de intentar iniciar sesión
        User::factory()->create([
            'email' => 'login@test.com',
            'password' => bcrypt('Password123'),
        ]);

        $loginData = [
            'email' => 'login@test.com',
            'password' => 'Password123',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token', 'token_type', 'expires_in']);
    }

    /**
     * El inicio de sesión falla con credenciales incorrectas (401).
     *
     * @return void
     */
    public function test_user_login_fails_with_wrong_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'nonexistent@test.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
                 ->assertJson(['error' => 'Credenciales invalidas']);
    }
    
    /**
     * El usuario puede cerrar sesión (invalidar su token) exitosamente (200).
     *
     * @return void
     */
    public function test_user_can_logout_successfully()
    {
        $token = $this->getJwtToken();

        // Cerrar sesión usando el token obtenido
        $response = $this->postJson('/api/logout', [], ['Authorization' => "Bearer $token"]);
        $response->assertStatus(200)
             ->assertJson(['message' => 'Sesión cerrada con exito. Token invalidado.']);


    /*
        $this->getJson('/api/tasks', ['Authorization' => "Bearer $token"])
             ->assertStatus(401);
    */
    }
    
    /**
     * Helper para registrar un usuario y obtener su token JWT.
     */
    protected function getJwtToken(): string
    {
        $user = User::factory()->create([
            'email' => $this->userData['email'],
            'password' => bcrypt($this->userData['password']),
            'name' => $this->userData['name'],
        ]);

        // Simula el login para obtener el token
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => $this->userData['password'],
        ]);

        return $response->json('token');
    }
}