<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = ['user_id', 'checked_in_at', 'notes'];

    protected $casts = [
        'checked_in_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('checked_in_at', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('checked_in_at', now()->month)
                     ->whereYear('checked_in_at', now()->year);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
