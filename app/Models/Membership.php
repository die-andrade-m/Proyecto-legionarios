<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Membership extends Model
{
    protected $fillable = [
        'user_id', 'plan_id', 'start_date', 'end_date',
        'status', 'price_paid', 'notes', 'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'price_paid' => 'decimal:2',
    ];

    // ─── Relationships ───────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(MembershipPlan::class, 'plan_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ─── Helpers ─────────────────────────────────────────────────

    public function getDaysRemainingAttribute(): int
    {
        if ($this->status !== 'active') return 0;
        return max(0, now()->diffInDays($this->end_date, false));
    }

    public function getProgressPercentageAttribute(): int
    {
        $total = $this->start_date->diffInDays($this->end_date);
        if ($total === 0) return 100;
        $elapsed = $this->start_date->diffInDays(now());
        return min(100, (int) (($elapsed / $total) * 100));
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'active'    => $this->days_remaining <= 7 ? 'warning' : 'success',
            'expired'   => 'danger',
            'suspended' => 'secondary',
            'pending'   => 'info',
            default     => 'secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'active'    => 'Activa',
            'expired'   => 'Vencida',
            'suspended' => 'Suspendida',
            'pending'   => 'Pendiente',
            default     => 'Desconocido',
        };
    }

    public function isExpiringSoon(): bool
    {
        return $this->status === 'active' && $this->days_remaining <= 7;
    }

    // ─── Scopes ──────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeExpiringSoon($query, int $days = 7)
    {
        return $query->where('status', 'active')
                     ->whereDate('end_date', '<=', now()->addDays($days))
                     ->whereDate('end_date', '>=', now());
    }
}
