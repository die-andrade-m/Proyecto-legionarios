<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\AttendanceService;
use App\Models\BodyStat;
use App\Models\Observation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        // Membership info
        $membership = $user->activeMembership()->with('plan')->first();
        if (!$membership) {
            // Get latest membership even if expired
            $membership = $user->memberships()->with('plan')->latest()->first();
        }

        // Attendance Stats
        $attendancesThisMonth = $user->attendancesThisMonth();
        $monthlyStats = $this->attendanceService->getMonthlyStats($user);
        $currentStreak = $user->currentStreak();

        // Weight progress (for simple graph/card info)
        $latestStat = $user->latestBodyStat;
        $firstStat = $user->bodyStats()->orderBy('measured_at', 'asc')->first();
        
        $weightDiff = 0;
        if ($latestStat && $firstStat) {
            $weightDiff = $latestStat->weight - $firstStat->weight;
        }

        // Routine of the day
        $activeUserRoutine = $user->activeRoutine()->with('routine.days.exercises.exercise')->first();
        $todayDayOfWeek = now()->dayOfWeekIso; // 1 (Mon) - 7 (Sun)
        $todayRoutineDay = null;

        if ($activeUserRoutine) {
            $todayRoutineDay = $activeUserRoutine->routine->days
                ->where('day_number', $todayDayOfWeek)
                ->first();
        }

        // Observations (Only public ones)
        $observations = $user->observations()->public()->limit(3)->get();

        // Unlocked achievements
        $achievements = $user->achievements()->orderBy('user_achievements.unlocked_at', 'desc')->get();

        // Today's attendance record with recorder info
        $todayAttendance = $user->attendances()->with('recorder')->whereDate('checked_in_at', today())->first();
        $checkedInToday = $todayAttendance !== null;

        return view('student.dashboard', compact(
            'user',
            'membership',
            'attendancesThisMonth',
            'monthlyStats',
            'currentStreak',
            'latestStat',
            'weightDiff',
            'todayRoutineDay',
            'observations',
            'achievements',
            'checkedInToday',
            'todayAttendance'
        ));
    }
}
