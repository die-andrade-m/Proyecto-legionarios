<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoutineDayExercise extends Model
{
    protected $fillable = [
        'routine_day_id', 'exercise_id', 'sets', 'reps',
        'weight_kg', 'rest_seconds', 'notes', 'order',
    ];

    protected $casts = [
        'weight_kg' => 'decimal:2',
    ];

    public function routineDay(): BelongsTo
    {
        return $this->belongsTo(RoutineDay::class);
    }

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    public function getRestLabelAttribute(): string
    {
        if ($this->rest_seconds < 60) return "{$this->rest_seconds}s";
        $min = intdiv($this->rest_seconds, 60);
        $sec = $this->rest_seconds % 60;
        return $sec > 0 ? "{$min}m {$sec}s" : "{$min}min";
    }
}
