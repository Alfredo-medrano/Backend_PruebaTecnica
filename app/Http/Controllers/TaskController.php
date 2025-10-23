<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource; 
use App\Services\TaskService; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource; 

class TaskController extends Controller
{
    // Inyección de dependencias del servicio
    public function __construct(protected TaskService $taskService)
    {
    }

    /**
     * GET /api/tasks
     * Muestra la lista de tareas del usuario.
     */
    public function index(): JsonResource
    {
        $tasks = $this->taskService->getTasksForUser(Auth::user());
        
        // Usamos el Resource para formatear la colección
        return TaskResource::collection($tasks);
    }

    /**
     * POST /api/tasks
     * Crea una nueva tarea.
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->createTask(Auth::user(), $request->validated());

        // Devolvemos el nuevo recurso usando el Resource y un código 201
        return (new TaskResource($task))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * GET /api/tasks/{id}
     * Muestra una tarea específica.
     */
    public function show(string $id): TaskResource
    {
        // El servicio buscará la tarea y lanzará 404 si no existe o no pertenece al usuario.
        $task = $this->taskService->findTaskForUser(Auth::user(), $id);
        
        return new TaskResource($task);
    }

    /**
     * PUT /api/tasks/{id}
     * Actualiza una tarea.
     */
    public function update(UpdateTaskRequest $request, string $id): TaskResource
    {
        // El servicio buscará y actualizará. Lanzará 404 si es necesario.
        $task = $this->taskService->updateTask(Auth::user(), $id, $request->validated());

        return new TaskResource($task);
    }

    /**
     * DELETE /api/tasks/{id}
     * Elimina una tarea.
     */
    public function destroy(string $id): JsonResponse
    {
        // El servicio buscará y eliminará.
        $this->taskService->deleteTask(Auth::user(), $id);

        // Devolvemos una respuesta vacía con código 204
        return response()->json(null, 204);
    }
}