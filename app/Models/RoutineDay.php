<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoutineDay extends Model
{
    protected $fillable = [
        'routine_id', 'day_number', 'name', 'focus_area', 'notes', 'is_rest_day',
    ];

    protected $casts = [
        'is_rest_day' => 'boolean',
    ];

    public function routine(): BelongsTo
    {
        return $this->belongsTo(Routine::class);
    }

    public function exercises(): HasMany
    {
        return $this->hasMany(RoutineDayExercise::class)->orderBy('order');
    }

    public function getDayNameAttribute(): string
    {
        return match($this->day_number) {
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo',
            default => "Día {$this->day_number}",
        };
    }
}
