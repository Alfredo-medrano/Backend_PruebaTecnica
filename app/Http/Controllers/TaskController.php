<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{

    /**
     * GET /api/tasks lista las tareas del usuario autenticado.
     */
    public function index()
    {
        $tasks = Auth::user()->tasks()->orderBy('created_at', 'desc')->get();
        
        return response()->json($tasks);
    }

    /**
     * POST /api/tasks crea una nueva tarea.
     */
    public function store(StoreTaskRequest $request)
    {
        // El user_id se asigna automáticamente usando la relación.
        $task = Auth::user()->tasks()->create($request->validated());

        return response()->json([
            'message' => 'Tarea creada exitosamente',
            'task' => $task
        ], 201); 
    }

    /**
     * PUT /api/tasks/{id} edita una tarea.
     */
    public function update(UpdateTaskRequest $request, string $id)
    {
        $task = Auth::user()->tasks()->findOrFail($id); 
        $task->update($request->validated());

        return response()->json([
            'message' => 'Tarea actualizada exitosamente',
            'task' => $task
        ]);
    }
    
    /**
     * DELETE /api/tasks/{id} elimina una tarea.
     */
    public function destroy(string $id)
    {
        $task = Auth::user()->tasks()->findOrFail($id);
        $task->delete();

        return response()->json(null, 204); 
    }
}
