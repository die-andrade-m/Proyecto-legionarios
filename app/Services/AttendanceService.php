<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    /**
     * Register attendance for a user. Returns false if already checked in today.
     */
    public function checkIn(User $user, ?string $notes = null): Attendance|false
    {
        // Anti-duplicate: prevent more than one check-in per day
        if ($user->hasCheckedInToday()) {
            return false;
        }

        $attendance = Attendance::create([
            'user_id'       => $user->id,
            'checked_in_at' => now(),
            'notes'         => $notes,
        ]);

        // Trigger achievement check after recording attendance
        app(AchievementService::class)->evaluate($user);

        return $attendance;
    }

    /**
     * Get monthly attendance stats for a user.
     */
    public function getMonthlyStats(User $user, int $year = null, int $month = null): array
    {
        $year  = $year  ?? now()->year;
        $month = $month ?? now()->month;

        $attendances = $user->attendances()
                            ->whereYear('checked_in_at', $year)
                            ->whereMonth('checked_in_at', $month)
                            ->get();

        $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;
        $count       = $attendances->count();
        $percentage  = $daysInMonth > 0 ? round(($count / $daysInMonth) * 100) : 0;

        // Build calendar grid
        $calendar = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($year, $month, $day)->toDateString();
            $calendar[$date] = $attendances->contains(fn($a) => $a->checked_in_at->toDateString() === $date);
        }

        return compact('count', 'percentage', 'calendar', 'daysInMonth');
    }

    /**
     * Get attendance data for a Chart.js bar chart (last 6 months).
     */
    public function getLast6MonthsData(User $user): array
    {
        $labels = [];
        $data   = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->translatedFormat('M');
            $data[] = $user->attendances()
                           ->whereYear('checked_in_at', $date->year)
                           ->whereMonth('checked_in_at', $date->month)
                           ->count();
        }

        return compact('labels', 'data');
    }

    /**
     * Get top students by attendance this month.
     */
    public function getMonthlyRanking(int $limit = 10): array
    {
        return DB::table('attendances')
                 ->join('users', 'attendances.user_id', '=', 'users.id')
                 ->whereMonth('checked_in_at', now()->month)
                 ->whereYear('checked_in_at', now()->year)
                 ->select('users.id', 'users.name', 'users.avatar', DB::raw('COUNT(*) as total'))
                 ->groupBy('users.id', 'users.name', 'users.avatar')
                 ->orderByDesc('total')
                 ->limit($limit)
                 ->get()
                 ->toArray();
    }
}
