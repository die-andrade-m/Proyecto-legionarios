<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeightLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'exercise_id',
        'weight_kg',
        'reps',
        'one_rep_max',
        'logged_at',
        'notes',
    ];

    protected $casts = [
        'weight_kg' => 'float',
        'reps' => 'integer',
        'one_rep_max' => 'float',
        'logged_at' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    /**
     * Calculate 1RM using Epley formula: w * (1 + r / 30)
     */
    public static function calculateOneRepMax(float $weight, int $reps): float
    {
        if ($reps <= 1) {
            return $weight;
        }
        return round($weight * (1 + ($reps / 30)), 2);
    }
}
