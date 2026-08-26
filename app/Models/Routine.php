<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Routine extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'trainer_id', 'name', 'description', 'difficulty',
        'goal', 'is_template', 'duration_weeks',
    ];

    protected $casts = [
        'is_template' => 'boolean',
    ];

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function days(): HasMany
    {
        return $this->hasMany(RoutineDay::class)->orderBy('day_number');
    }

    public function userRoutines(): HasMany
    {
        return $this->hasMany(UserRoutine::class);
    }

    public function getDifficultyLabelAttribute(): string
    {
        return match($this->difficulty) {
            'beginner'     => 'Principiante',
            'intermediate' => 'Intermedio',
            'advanced'     => 'Avanzado',
            default        => ucfirst($this->difficulty),
        };
    }

    public function getDifficultyColorAttribute(): string
    {
        return match($this->difficulty) {
            'beginner'     => 'success',
            'intermediate' => 'warning',
            'advanced'     => 'danger',
            default        => 'secondary',
        };
    }
}
