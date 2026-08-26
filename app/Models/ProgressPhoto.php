<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressPhoto extends Model
{
    protected $fillable = ['user_id', 'photo_path', 'caption', 'angle', 'taken_at'];

    protected $casts = [
        'taken_at' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getPhotoUrlAttribute(): string
    {
        return asset('storage/' . $this->photo_path);
    }

    public function getAngleLabelAttribute(): string
    {
        return match($this->angle) {
            'front' => 'Frontal',
            'side'  => 'Lateral',
            'back'  => 'Posterior',
            default => 'Otra',
        };
    }
}
