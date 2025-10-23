<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Esto nos da control total sobre los nombres y datos de la API
        return [
            'id' => $this->id,
            'titulo' => $this->title,
            'descripcion' => $this->description,
            'completada' => $this->is_completed,
            'creada_en' => $this->created_at->toIso8601String(),
            'actualizada_en' => $this->updated_at->toIso8601String(),
        ];
    }
}
