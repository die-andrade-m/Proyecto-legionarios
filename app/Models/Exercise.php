<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exercise extends Model
{
    protected $fillable = [
        'name', 'description', 'muscle_group', 'secondary_muscle_group',
        'equipment', 'difficulty', 'video_url', 'image_path', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function routineDayExercises(): HasMany
    {
        return $this->hasMany(RoutineDayExercise::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getMuscleGroupLabelAttribute(): string
    {
        return match($this->muscle_group) {
            'chest'     => 'Pecho',
            'back'      => 'Espalda',
            'legs'      => 'Piernas',
            'shoulders' => 'Hombros',
            'arms'      => 'Brazos',
            'core'      => 'Core',
            'cardio'    => 'Cardio',
            'full_body' => 'Cuerpo completo',
            default     => ucfirst($this->muscle_group),
        };
    }
}
