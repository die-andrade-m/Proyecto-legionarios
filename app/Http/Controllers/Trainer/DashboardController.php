<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Membership;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $trainer */
        $trainer = $request->user();

        // 1. Total active students assigned to this trainer
        $activeStudentsCount = User::role('student')
            ->where('trainer_id', $trainer->id)
            ->where('is_active', true)
            ->count();

        // 2. Today's attendances (assigned students)
        $todayAttendances = Attendance::whereDate('checked_in_at', today())
            ->whereHas('user', function($q) use ($trainer) {
                $q->where('trainer_id', $trainer->id);
            })
            ->with('user')
            ->orderBy('checked_in_at', 'desc')
            ->get();

        // 3. Students missing frequently (No attendance in last 7 days)
        // Let's find students whose latest attendance is older than 7 days, or have no attendance
        $sevenDaysAgo = now()->subDays(7);
        
        $assignedStudents = User::role('student')
            ->where('trainer_id', $trainer->id)
            ->where('is_active', true)
            ->with(['attendances' => function($q) {
                $q->orderBy('checked_in_at', 'desc');
            }])
            ->get();

        $inactiveStudents = $assignedStudents->filter(function($student) use ($sevenDaysAgo) {
            $latest = $student->attendances->first();
            if (!$latest) return true; // Never attended
            return $latest->checked_in_at->lt($sevenDaysAgo);
        });

        // 4. Memberships expiring soon (within 10 days) for assigned students
        $expiringMemberships = Membership::where('status', 'active')
            ->whereDate('end_date', '<=', now()->addDays(10))
            ->whereDate('end_date', '>=', now())
            ->whereHas('user', function($q) use ($trainer) {
                $q->where('trainer_id', $trainer->id);
            })
            ->with(['user', 'plan'])
            ->orderBy('end_date', 'asc')
            ->get();

        return view('trainer.dashboard', compact(
            'activeStudentsCount',
            'todayAttendances',
            'inactiveStudents',
            'expiringMemberships'
        ));
    }
}
