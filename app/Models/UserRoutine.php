<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserRoutine extends Model
{
    protected $fillable = ['user_id', 'routine_id', 'assigned_at', 'ends_at', 'is_active', 'notes'];

    protected $casts = [
        'assigned_at' => 'date',
        'ends_at'     => 'date',
        'is_active'   => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function routine(): BelongsTo
    {
        return $this->belongsTo(Routine::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
