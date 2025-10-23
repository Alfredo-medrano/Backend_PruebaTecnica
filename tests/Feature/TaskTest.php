<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    // Datos base de una tarea
    protected array $taskData = [
        'title' => 'Comprar víveres',
        'description' => 'Leche, pan y huevos',
        'is_completed' => false,
    ];

    /**
     * Helper para autenticar a un usuario y obtener el token JWT.
     * 
     */
    protected function authenticateUser($user = null, $token = null): string
    {
        $user = $user ?? User::factory()->create();
        $token = JWTAuth::fromUser($user);
        return $token;
    }

    //---------------------------------------------------------
    // PRUEBAS DE SEGURIDAD (Unauthorized)
    //---------------------------------------------------------

    /**
     * Todos los endpoints de tareas requieren autenticación (401).
     *
     * @return void
     */
    public function test_task_endpoints_require_authentication()
    {
        $task = Task::factory()->create();

        $this->postJson('/api/tasks', $this->taskData)->assertStatus(401);
        $this->getJson('/api/tasks')->assertStatus(401);
        $this->getJson('/api/tasks/' . $task->id)->assertStatus(401);
        $this->putJson('/api/tasks/' . $task->id, $this->taskData)->assertStatus(401);
        $this->deleteJson('/api/tasks/' . $task->id)->assertStatus(401);
    }

    //---------------------------------------------------------
    // PRUEBAS CRUD
    //---------------------------------------------------------

    /**
     * Un usuario puede crear una tarea (201).
     *
     * @return void
     */
    public function test_user_can_create_a_task()
    {
        $token = $this->authenticateUser();

        $response = $this->postJson('/api/tasks', $this->taskData, ['Authorization' => "Bearer $token"]);

        $response->assertStatus(201)
            ->assertJsonPath('data.titulo', 'Comprar víveres')
            ->assertJsonStructure(['data' => ['id', 'titulo', 'descripcion', 'completada']]);

        $this->assertDatabaseHas('tasks', ['title' => 'Comprar víveres']);
    }

    /**
     * La creación de una tarea falla con datos inválidos (422).
     *
     * @return void
     */
    public function test_task_creation_fails_with_invalid_data()
    {
        $token = $this->authenticateUser();

        $response = $this->postJson('/api/tasks', ['title' => ''], ['Authorization' => "Bearer $token"]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title']);
    }

    /**
     * Un usuario puede listar sus propias tareas (200).
     *
     * @return void
     */
    public function test_user_can_list_only_their_tasks()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Task::factory()->for($user1)->create(['title' => 'Tarea de User1']);
        Task::factory()->for($user2)->create(['title' => 'Tarea de User2']);

        $token1 = $this->authenticateUser($user1);

        $response = $this->getJson('/api/tasks', ['Authorization' => "Bearer $token1"]);

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['titulo' => 'Tarea de User1'])
            ->assertJsonMissing(['titulo' => 'Tarea de User2']);
    }

    /**
     * Un usuario puede ver una de sus tareas (200).
     *
     * @return void
     */
    public function test_user_can_view_their_own_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create(['title' => 'Tarea para ver']);
        $token = $this->authenticateUser($user);

        $response = $this->getJson('/api/tasks/' . $task->id, ['Authorization' => "Bearer $token"]);

        $response->assertStatus(200)
            ->assertJsonFragment(['titulo' => 'Tarea para ver']);
    }

    /**
     * Un usuario NO puede ver una tarea de otro usuario (404).
     *
     * @return void
     */
    public function test_user_cannot_view_another_users_task()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $taskOfUser1 = Task::factory()->for($user1)->create();
        $token2 = $this->authenticateUser($user2); 

        $response = $this->getJson('/api/tasks/' . $taskOfUser1->id, ['Authorization' => "Bearer $token2"]);

        // La lógica del servicio debería devolver 404 si la tarea no se encuentra
        // en el scope del usuario autenticado.
        $response->assertStatus(404);
    }
    
    /**
     * Un usuario puede actualizar una de sus tareas (200).
     *
     * @return void
     */
    public function test_user_can_update_their_own_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create(['is_completed' => false]);
        $token = $this->authenticateUser($user);
        $updateData = ['is_completed' => true];

        $response = $this->putJson('/api/tasks/' . $task->id, $updateData, ['Authorization' => "Bearer $token"]);

        $response->assertStatus(200)
            ->assertJsonFragment(['completada' => true]);

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'is_completed' => true]);
    }

    /**
     * Un usuario puede eliminar una de sus tareas (204).
     *
     * @return void
     */
    public function test_user_can_delete_their_own_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create();
        $token = $this->authenticateUser($user);

        $response = $this->deleteJson('/api/tasks/' . $task->id, [], ['Authorization' => "Bearer $token"]);

        $response->assertStatus(204);

        // Afirmar que la tarea ya no está en la base de datos
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
    
    /**
     * Prueba: Un usuario NO puede eliminar la tarea de otro (404).
     *
     * @return void
     */
    public function test_user_cannot_delete_another_users_task()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $taskOfUser1 = Task::factory()->for($user1)->create();
        $token2 = $this->authenticateUser($user2); 

        $response = $this->deleteJson('/api/tasks/' . $taskOfUser1->id, [], ['Authorization' => "Bearer $token2"]);

        $response->assertStatus(404);
        
        // Afirmar que la tarea del User 1 NO fue eliminada
        $this->assertDatabaseHas('tasks', ['id' => $taskOfUser1->id]);
    }
}