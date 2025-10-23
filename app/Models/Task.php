<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    /**
     * Los atributos que se pueden asignar masivamente.
     * Quitamos 'user_id' porque se asigna vía relación,
     * previniendo vulnerabilidades de asignación masiva.
     */
    protected $fillable = [
        'title',
        'description',
        'is_completed',
    ];

    /**
     * Los atributos que deben ser casteados a tipos nativos.
     * Esto asegura que 'is_completed' siempre sea un booleano.
     */
    protected $casts = [
        'is_completed' => 'boolean',
    ];

    /**
     * Traer el usuario al que pertenece la tarea.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}