<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'recorded_by',
        'checked_in_at',
        'notes',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
    ];

    /**
     * Student associated with the attendance.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Trainer or Admin who recorded the attendance.
     */
    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * Scope for attendances recorded today.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('checked_in_at', today());
    }

    /**
     * Scope for attendances in the current month.
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('checked_in_at', now()->month)
                     ->whereYear('checked_in_at', now()->year);
    }

    /**
     * Scope for attendances in a specific month and year.
     */
    public function scopeForMonth($query, int $year, int $month)
    {
        return $query->whereYear('checked_in_at', $year)
                     ->whereMonth('checked_in_at', $month);
    }

    /**
     * Scope for attendances for a specific student.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Formatted string of date (e.g., "27 de Agosto, 2026").
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->checked_in_at ? $this->checked_in_at->translatedFormat('d \d\e F, Y') : '-';
    }

    /**
     * Formatted string of exact time (e.g., "18:30:45").
     */
    public function getFormattedTimeAttribute(): string
    {
        return $this->checked_in_at ? $this->checked_in_at->format('H:i:s') : '-';
    }

    /**
     * Formatted short time (e.g., "18:30").
     */
    public function getFormattedShortTimeAttribute(): string
    {
        return $this->checked_in_at ? $this->checked_in_at->format('H:i') : '-';
    }

    /**
     * Name of the trainer/admin who registered this attendance.
     */
    public function getRecorderNameAttribute(): string
    {
        return $this->recorder?->name ?? 'Entrenador del Dojo';
    }
}
