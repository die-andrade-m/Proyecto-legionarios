<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    protected AttendanceService $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * Show trainer attendance management panel with student check-in, calendar, and logs.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $trainer */
        $trainer = $request->user();

        // Get students (if trainer has specific students or all active students)
        $studentsQuery = User::role('student')->where('is_active', true)->orderBy('name', 'asc');
        
        // If trainer only manages their assigned students, we can filter or include them
        $allStudents = $studentsQuery->get();

        // Selected student for calendar view (default to first student or query param)
        $selectedStudentId = $request->input('student_id', $allStudents->first()?->id);
        $selectedStudent = $selectedStudentId ? User::find($selectedStudentId) : null;

        // Month & Year for Calendar
        $year = $request->input('year', (int)now()->year);
        $month = $request->input('month', (int)now()->month);

        $calendarData = null;
        if ($selectedStudent) {
            $calendarData = $this->attendanceService->getDetailedCalendar($selectedStudent, (int)$year, (int)$month);
        }

        // Today's Attendances
        $todayAttendances = Attendance::with(['user', 'recorder'])
            ->whereDate('checked_in_at', today())
            ->orderBy('checked_in_at', 'desc')
            ->get();

        // Check which students already attended today
        $todayAttendedStudentIds = $todayAttendances->pluck('user_id')->toArray();

        // Historical Attendance Logs with filter
        $logsQuery = Attendance::with(['user', 'recorder'])->orderBy('checked_in_at', 'desc');

        if ($request->filled('filter_student_id')) {
            $logsQuery->where('user_id', $request->filter_student_id);
        }

        if ($request->filled('filter_date')) {
            $logsQuery->whereDate('checked_in_at', $request->filter_date);
        }

        $attendanceLogs = $logsQuery->paginate(20)->withQueryString();

        return view('trainer.attendances.index', compact(
            'allStudents',
            'selectedStudent',
            'calendarData',
            'todayAttendances',
            'todayAttendedStudentIds',
            'attendanceLogs',
            'year',
            'month'
        ));
    }

    /**
     * Store a new attendance for a student (submitted by trainer).
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'date'       => 'nullable|date',
            'time'       => 'nullable|date_format:H:i',
            'notes'      => 'nullable|string|max:255',
        ], [
            'student_id.required' => 'Debes seleccionar un alumno.',
            'student_id.exists'   => 'El alumno seleccionado no existe.',
        ]);

        $student = User::findOrFail($request->student_id);
        $trainer = $request->user();

        // Determine date & time
        $date = $request->input('date', now()->toDateString());
        $time = $request->input('time', now()->format('H:i:s'));
        
        // Ensure seconds are included
        if (strlen($time) === 5) {
            $time .= ':00';
        }

        $dateTime = Carbon::parse("{$date} {$time}");

        $notes = $request->input('notes', "Registrado por profesor {$trainer->name}");

        $attendance = $this->attendanceService->checkIn($student, $trainer, $dateTime, $notes);

        if (!$attendance) {
            return redirect()->back()
                ->with('error', "El alumno {$student->name} ya tiene registrada su asistencia para la fecha {$dateTime->format('d/m/Y')}.");
        }

        return redirect()->back()
            ->with('success', "¡Asistencia de {$student->name} registrada exitosamente a las {$dateTime->format('H:i:s')}!");
    }

    /**
     * Remove an attendance record (in case of mistake).
     */
    public function destroy(Attendance $attendance)
    {
        $studentName = $attendance->user?->name ?? 'Alumno';
        $timeString = $attendance->checked_in_at->format('d/m/Y H:i:s');

        $attendance->delete();

        return redirect()->back()
            ->with('success', "Asistencia de {$studentName} ({$timeString}) eliminada correctamente.");
    }
}
