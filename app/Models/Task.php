<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;
    /**
     * Los atributos que son asignables en masa.
     */
    protected $fillable = [
        'title',
        'description',
        'is_completed',
    ];

    /**
     * Los atributos que deben ser casteados a tipos nativos.
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