<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'birth_date',
        'objective',
        'trainer_id',
        'is_active',
        'emergency_contact_name',
        'emergency_contact_phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'birth_date'        => 'date',
            'is_active'         => 'boolean',
        ];
    }

    // ─── Relationships ───────────────────────────────────────────

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(User::class, 'trainer_id');
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    public function activeMembership(): HasOne
    {
        return $this->hasOne(Membership::class)->where('status', 'active')->latestOfMany();
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function bodyStats(): HasMany
    {
        return $this->hasMany(BodyStat::class)->orderBy('measured_at', 'desc');
    }

    public function weightLogs(): HasMany
    {
        return $this->hasMany(WeightLog::class)->orderBy('logged_at', 'desc');
    }

    public function latestBodyStat(): HasOne
    {
        return $this->hasOne(BodyStat::class)->latestOfMany('measured_at');
    }

    public function progressPhotos(): HasMany
    {
        return $this->hasMany(ProgressPhoto::class)->orderBy('taken_at', 'desc');
    }

    public function userRoutines(): HasMany
    {
        return $this->hasMany(UserRoutine::class);
    }

    public function activeRoutine(): HasOne
    {
        return $this->hasOne(UserRoutine::class)->where('is_active', true)->latestOfMany();
    }

    public function observations(): HasMany
    {
        return $this->hasMany(Observation::class, 'student_id')->latest();
    }

    public function userAchievements(): HasMany
    {
        return $this->hasMany(UserAchievement::class);
    }

    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
                    ->withPivot('unlocked_at')
                    ->withTimestamps();
    }

    // ─── Role Helpers ─────────────────────────────────────────────

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isTrainer(): bool
    {
        return $this->hasRole('trainer');
    }

    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }

    public function getPrimaryRole(): ?string
    {
        if ($this->isAdmin()) return 'admin';
        if ($this->isTrainer()) return 'trainer';
        if ($this->isStudent()) return 'student';
        return null;
    }

    // ─── Attendance Helpers ───────────────────────────────────────

    public function hasCheckedInToday(): bool
    {
        return $this->attendances()
                    ->whereDate('checked_in_at', today())
                    ->exists();
    }

    public function attendancesThisMonth(): int
    {
        return $this->attendances()
                    ->whereMonth('checked_in_at', now()->month)
                    ->whereYear('checked_in_at', now()->year)
                    ->count();
    }

    public function currentStreak(): int
    {
        $dates = $this->attendances()
                      ->orderByDesc('checked_in_at')
                      ->pluck('checked_in_at')
                      ->map(fn($d) => $d->toDateString())
                      ->unique()
                      ->values();

        if ($dates->isEmpty()) return 0;

        $streak = 0;
        $current = now()->toDateString();

        foreach ($dates as $date) {
            if ($date === $current || $date === now()->subDays($streak)->toDateString()) {
                $streak++;
                $current = now()->subDays($streak)->toDateString();
            } else {
                break;
            }
        }

        return $streak;
    }

    // ─── Scopes ──────────────────────────────────────────────────

    public function scopeRole($query, string $role)
    {
        return $query->whereHas('roles', function($q) use ($role) {
            $q->where('name', $role);
        });
    }

    // ─── Accessors ───────────────────────────────────────────────

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=6C3CF7&color=fff&bold=true';
    }

    public function getAgeAttribute(): ?int
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }
}
