<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Observation extends Model
{
    protected $fillable = [
        'trainer_id', 'student_id', 'content',
        'is_private', 'category', 'is_pinned',
    ];

    protected $casts = [
        'is_private' => 'boolean',
        'is_pinned'  => 'boolean',
    ];

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'technique'  => 'Técnica',
            'nutrition'  => 'Nutrición',
            'motivation' => 'Motivación',
            'injury'     => 'Lesión',
            'progress'   => 'Progreso',
            default      => 'General',
        };
    }

    public function getCategoryIconAttribute(): string
    {
        return match($this->category) {
            'technique'  => '🎯',
            'nutrition'  => '🥗',
            'motivation' => '⚡',
            'injury'     => '🩹',
            'progress'   => '📈',
            default      => '📝',
        };
    }

    public function getCategoryColorAttribute(): string
    {
        return match($this->category) {
            'technique'  => 'primary',
            'nutrition'  => 'success',
            'motivation' => 'warning',
            'injury'     => 'danger',
            'progress'   => 'info',
            default      => 'secondary',
        };
    }

    public function scopePublic($query)
    {
        return $query->where('is_private', false);
    }

    public function scopeForStudent($query, int $studentId)
    {
        return $query->where('student_id', $studentId);
    }
}
