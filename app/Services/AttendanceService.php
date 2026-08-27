<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AttendanceService
{
    /**
     * Register attendance for a student.
     * Can be registered by a trainer or admin, with current or custom timestamp.
     */
    public function checkIn(User $student, ?User $recorder = null, ?Carbon $dateTime = null, ?string $notes = null): Attendance|false
    {
        $checkedInAt = $dateTime ?? now();
        $dateString = $checkedInAt->toDateString();

        // Prevent duplicate attendance for the same day
        $alreadyAttended = $student->attendances()
            ->whereDate('checked_in_at', $dateString)
            ->exists();

        if ($alreadyAttended) {
            return false;
        }

        $data = [
            'user_id'       => $student->id,
            'checked_in_at' => $checkedInAt,
            'notes'         => $notes ?? 'Registro de asistencia en Dojo Legionarios.',
        ];

        if ($recorder) {
            $data['recorded_by'] = $recorder->id;
        }

        try {
            $attendance = Attendance::create($data);
        } catch (\Exception $e) {
            // Fallback in case recorded_by column was not yet added in database
            unset($data['recorded_by']);
            $attendance = Attendance::create($data);
        }

        // Trigger achievement check after recording attendance
        app(AchievementService::class)->evaluate($student);

        return $attendance;
    }

    /**
     * Get detailed monthly calendar grid and statistics for a student.
     */
    public function getDetailedCalendar(User $user, ?int $year = null, ?int $month = null): array
    {
        $now = now();
        $year  = $year  ?? (int)$now->year;
        $month = $month ?? (int)$now->month;

        $targetDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $monthName  = $targetDate->translatedFormat('F Y');
        $daysInMonth = $targetDate->daysInMonth;

        // Fetch attendances with recorder for the month
        $attendances = $user->attendances()
            ->with('recorder')
            ->whereYear('checked_in_at', $year)
            ->whereMonth('checked_in_at', $month)
            ->orderBy('checked_in_at', 'asc')
            ->get();

        // Index attendances by date string 'Y-m-d'
        $attendancesByDate = [];
        foreach ($attendances as $att) {
            $attendancesByDate[$att->checked_in_at->toDateString()] = $att;
        }

        // Build calendar weeks (starting from Monday: 1 to Sunday: 7)
        $startDayOfWeek = $targetDate->dayOfWeekIso; // 1 (Mon) to 7 (Sun)
        $weeks = [];
        $currentWeek = [];

        // Pre-fill empty days from previous month
        for ($i = 1; $i < $startDayOfWeek; $i++) {
            $currentWeek[] = null;
        }

        $todayString = $now->toDateString();

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDate = Carbon::createFromDate($year, $month, $day);
            $dateString = $currentDate->toDateString();
            $att = $attendancesByDate[$dateString] ?? null;

            $currentWeek[] = [
                'day'          => $day,
                'date'         => $dateString,
                'has_attended' => $att !== null,
                'time'         => $att ? $att->checked_in_at->format('H:i:s') : null,
                'short_time'   => $att ? $att->checked_in_at->format('H:i') : null,
                'recorder_name'=> $att ? ($att->recorder?->name ?? 'Entrenador') : null,
                'notes'        => $att ? $att->notes : null,
                'is_today'     => $dateString === $todayString,
                'is_future'    => $currentDate->isFuture(),
            ];

            if (count($currentWeek) === 7) {
                $weeks[] = $currentWeek;
                $currentWeek = [];
            }
        }

        // Fill remaining days in the last week
        if (count($currentWeek) > 0) {
            while (count($currentWeek) < 7) {
                $currentWeek[] = null;
            }
            $weeks[] = $currentWeek;
        }

        // Navigation links
        $prevDate = $targetDate->copy()->subMonth();
        $nextDate = $targetDate->copy()->addMonth();

        $count = $attendances->count();
        $percentage = $daysInMonth > 0 ? round(($count / $daysInMonth) * 100) : 0;

        return [
            'year'         => $year,
            'month'        => $month,
            'monthName'    => ucfirst($monthName),
            'daysInMonth'  => $daysInMonth,
            'attendances'  => $attendances,
            'count'        => $count,
            'percentage'   => $percentage,
            'weeks'        => $weeks,
            'prevYear'     => $prevDate->year,
            'prevMonth'    => $prevDate->month,
            'nextYear'     => $nextDate->year,
            'nextMonth'    => $nextDate->month,
            'currentStreak'=> $user->currentStreak(),
        ];
    }

    /**
     * Get monthly attendance stats for a user (summary).
     */
    public function getMonthlyStats(User $user, int $year = null, int $month = null): array
    {
        $detailed = $this->getDetailedCalendar($user, $year, $month);
        return [
            'count'       => $detailed['count'],
            'percentage'  => $detailed['percentage'],
            'daysInMonth' => $detailed['daysInMonth'],
        ];
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
            $labels[] = ucfirst($date->translatedFormat('M'));
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
