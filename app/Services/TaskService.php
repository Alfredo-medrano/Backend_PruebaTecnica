<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class TaskService
{
    /**
     * Obtiene todas las tareas para un usuario específico.
     *
     * @param User $user
     * @return Collection
     */
    public function getTasksForUser(User $user): Collection
    {
        return $user->tasks()->orderBy('created_at', 'desc')->get();
    }

    /**
     * Crea una nueva tarea para un usuario.
     *
     * @param User $user
     * @param array $data Datos validados (title, description, etc.)
     * @return Task
     */
    public function createTask(User $user, array $data): Task
    {
        // La relación $user->tasks() asigna automáticamente el user_id
        return $user->tasks()->create($data);
    }

    /**
     * Encuentra una tarea específica que pertenece a un usuario.
     * Lanzará ModelNotFoundException si no se encuentra
     *
     * @param User $user
     * @param string $id
     * @return Task
     */
    public function findTaskForUser(User $user, string $id): Task
    {
        return $user->tasks()->findOrFail($id);
    }

    /**
     * Actualiza una tarea que pertenece a un usuario.
     * Lanzará ModelNotFoundException si no se encuentra.
     *
     * @param User $user
     * @param string $id
     * @param array $data Datos validados
     * @return Task
     */
    public function updateTask(User $user, string $id, array $data): Task
    {
        $task = $user->tasks()->findOrFail($id);
        $task->update($data);
        return $task;
    }

    /**
     * Elimina una tarea que pertenece a un usuario.
     * Lanzará ModelNotFoundException si no se encuentra.
     *
     * @param User $user
     * @param string $id
     * @return bool
     */
    public function deleteTask(User $user, string $id): bool
    {
        $task = $user->tasks()->findOrFail($id);
        return $task->delete();
    }
}