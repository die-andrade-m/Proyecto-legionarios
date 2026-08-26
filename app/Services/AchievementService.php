<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\User;
use App\Models\UserAchievement;
use Illuminate\Support\Facades\Log;

class AchievementService
{
    /**
     * Evaluate all active achievements for a user and unlock any newly met conditions.
     * Returns array of newly unlocked achievement names.
     */
    public function evaluate(User $user): array
    {
        $newlyUnlocked = [];
        $achievements  = Achievement::active()->get();
        $unlockedIds   = $user->userAchievements()->pluck('achievement_id')->toArray();

        foreach ($achievements as $achievement) {
            // Skip already unlocked
            if (in_array($achievement->id, $unlockedIds)) continue;

            if ($this->conditionMet($user, $achievement)) {
                UserAchievement::create([
                    'user_id'        => $user->id,
                    'achievement_id' => $achievement->id,
                    'unlocked_at'    => now(),
                ]);
                $newlyUnlocked[] = $achievement->name;
                Log::info("Achievement unlocked: [{$achievement->name}] for user [{$user->name}]");
            }
        }

        return $newlyUnlocked;
    }

    /**
     * Check if a specific achievement condition is met for a user.
     */
    private function conditionMet(User $user, Achievement $achievement): bool
    {
        return match($achievement->condition_type) {
            'attendances_count'       => $user->attendances()->count() >= $achievement->condition_value,
            'streak_days'             => $user->currentStreak() >= $achievement->condition_value,
            'first_routine_completed' => $user->userRoutines()->exists(),
            'first_photo_uploaded'    => $user->progressPhotos()->exists(),
            'measurements_recorded'   => $user->bodyStats()->count() >= $achievement->condition_value,
            'months_active'           => $this->monthsActive($user) >= $achievement->condition_value,
            'weight_goal_reached'     => $this->weightGoalReached($user),
            default                   => false,
        };
    }

    private function monthsActive(User $user): int
    {
        $first = $user->attendances()->orderBy('checked_in_at')->first();
        if (!$first) return 0;
        return (int) $first->checked_in_at->diffInMonths(now());
    }

    private function weightGoalReached(User $user): bool
    {
        // Basic check: if user has at least 2 body stat records and latest weight <= first weight
        $stats = $user->bodyStats()->orderBy('measured_at')->pluck('weight');
        if ($stats->count() < 2) return false;
        return $stats->last() <= $stats->first();
    }
}
