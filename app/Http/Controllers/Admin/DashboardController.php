<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Membership;
use App\Models\Attendance;
use App\Models\MembershipPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Core counters
        $studentsCount = User::role('student')->where('is_active', true)->count();
        $trainersCount = User::role('trainer')->where('is_active', true)->count();
        
        // 2. Earnings this month
        $earningsThisMonth = Membership::whereMonth('start_date', now()->month)
            ->whereYear('start_date', now()->year)
            ->sum('price_paid');

        // 3. Attendances today
        $attendancesTodayCount = Attendance::whereDate('checked_in_at', today())->count();

        // 4. Recent memberships
        $recentMemberships = Membership::with(['user', 'plan'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // 5. Expiring memberships (next 10 days)
        $expiringMemberships = Membership::where('status', 'active')
            ->whereDate('end_date', '<=', now()->addDays(10))
            ->whereDate('end_date', '>=', now())
            ->with(['user', 'plan'])
            ->orderBy('end_date', 'asc')
            ->get();

        // 6. Plan distribution (Chart data)
        $planStats = DB::table('memberships')
            ->join('membership_plans', 'memberships.plan_id', '=', 'membership_plans.id')
            ->where('memberships.status', 'active')
            ->select('membership_plans.name', DB::raw('COUNT(*) as total'))
            ->groupBy('membership_plans.name')
            ->get();

        $planLabels = $planStats->pluck('name')->toArray();
        $planCounts = $planStats->pluck('total')->toArray();

        return view('admin.dashboard', compact(
            'studentsCount',
            'trainersCount',
            'earningsThisMonth',
            'attendancesTodayCount',
            'recentMemberships',
            'expiringMemberships',
            'planLabels',
            'planCounts'
        ));
    }
}
