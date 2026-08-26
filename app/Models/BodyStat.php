<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BodyStat extends Model
{
    protected $fillable = [
        'user_id', 'weight', 'height', 'bmi', 'body_fat',
        'muscle_mass', 'waist', 'hip', 'arm', 'leg', 'chest',
        'measured_at', 'notes', 'recorded_by',
    ];

    protected $casts = [
        'measured_at' => 'date',
        'weight'      => 'decimal:2',
        'height'      => 'decimal:2',
        'bmi'         => 'decimal:2',
        'body_fat'    => 'decimal:2',
        'muscle_mass' => 'decimal:2',
        'waist'       => 'decimal:2',
        'hip'         => 'decimal:2',
        'arm'         => 'decimal:2',
        'leg'         => 'decimal:2',
        'chest'       => 'decimal:2',
    ];

    // ─── Relationships ───────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // ─── Auto-calculate BMI on save ──────────────────────────────

    protected static function booted(): void
    {
        static::saving(function (BodyStat $stat) {
            if ($stat->weight && $stat->height && $stat->height > 0) {
                $heightInMeters = $stat->height / 100;
                $stat->bmi = round($stat->weight / ($heightInMeters ** 2), 2);
            }
        });
    }

    // ─── Helpers ─────────────────────────────────────────────────

    public function getBmiCategoryAttribute(): string
    {
        if (!$this->bmi) return 'Sin datos';
        return match(true) {
            $this->bmi < 18.5 => 'Bajo peso',
            $this->bmi < 25   => 'Normal',
            $this->bmi < 30   => 'Sobrepeso',
            default           => 'Obesidad',
        };
    }

    public function getBmiColorAttribute(): string
    {
        if (!$this->bmi) return 'secondary';
        return match(true) {
            $this->bmi < 18.5 => 'info',
            $this->bmi < 25   => 'success',
            $this->bmi < 30   => 'warning',
            default           => 'danger',
        };
    }
}
