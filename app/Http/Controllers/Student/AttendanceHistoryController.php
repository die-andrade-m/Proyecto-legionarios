<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\AttendanceService;
use Illuminate\Http\Request;

class AttendanceHistoryController extends Controller
{
    protected AttendanceService $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * Show student's personal interactive attendance calendar and detailed history.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $student */
        $student = $request->user();

        $year = $request->input('year', (int)now()->year);
        $month = $request->input('month', (int)now()->month);

        $calendarData = $this->attendanceService->getDetailedCalendar($student, (int)$year, (int)$month);

        // Fetch all historical attendances for this student with pagination
        $historyQuery = $student->attendances()
            ->with('recorder')
            ->orderBy('checked_in_at', 'desc');

        if ($request->filled('filter_month')) {
            $historyQuery->whereMonth('checked_in_at', $request->filter_month);
        }

        if ($request->filled('filter_year')) {
            $historyQuery->whereYear('checked_in_at', $request->filter_year);
        }

        $attendancesList = $historyQuery->paginate(15)->withQueryString();

        return view('student.attendances.index', compact(
            'student',
            'calendarData',
            'attendancesList',
            'year',
            'month'
        ));
    }
}
