<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BodyStat;
use App\Models\ProgressPhoto;
use App\Models\Observation;
use App\Services\AttendanceService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function index(Request $request)
    {
        /** @var \App\Models\User $trainer */
        $trainer = $request->user();

        $students = User::role('student')
            ->where('trainer_id', $trainer->id)
            ->with(['latestBodyStat', 'activeMembership.plan'])
            ->orderBy('name', 'asc')
            ->get();

        return view('trainer.students.index', compact('students'));
    }

    public function show(Request $request, User $student)
    {
        /** @var \App\Models\User $trainer */
        $trainer = $request->user();

        // Security check
        if ($student->trainer_id !== $trainer->id) {
            abort(403, 'No tienes permiso para ver este alumno.');
        }

        $membership = $student->activeMembership()->with('plan')->first() 
            ?? $student->memberships()->with('plan')->latest()->first();

        $bodyStats = $student->bodyStats;
        $photos = $student->progressPhotos;
        $observations = $student->observations()->orderBy('created_at', 'desc')->get();
        $achievements = $student->achievements()->orderBy('user_achievements.unlocked_at', 'desc')->get();

        // Calculate attendance stats
        $monthlyStats = $this->attendanceService->getMonthlyStats($student);
        $attendancesThisMonth = $student->attendancesThisMonth();
        $streak = $student->currentStreak();

        // Graph data
        $chartData = [
            'labels' => [],
            'weight' => [],
            'fat' => [],
            'muscle' => [],
        ];

        foreach ($bodyStats->reverse() as $stat) {
            $chartData['labels'][] = $stat->measured_at->format('d/m/Y');
            $chartData['weight'][] = $stat->weight;
            $chartData['fat'][] = $stat->body_fat;
            $chartData['muscle'][] = $stat->muscle_mass;
        }

        return view('trainer.students.show', compact(
            'student',
            'membership',
            'bodyStats',
            'photos',
            'observations',
            'achievements',
            'monthlyStats',
            'attendancesThisMonth',
            'streak',
            'chartData'
        ));
    }

    public function storeMeasurements(Request $request, User $student)
    {
        /** @var \App\Models\User $trainer */
        $trainer = $request->user();

        if ($student->trainer_id !== $trainer->id) {
            abort(403);
        }

        $request->validate([
            'weight' => 'required|numeric|between:30,250',
            'height' => 'required|numeric|between:100,250',
            'body_fat' => 'nullable|numeric|between:2,60',
            'muscle_mass' => 'nullable|numeric|between:10,150',
            'waist' => 'nullable|numeric|between:40,200',
            'hip' => 'nullable|numeric|between:40,200',
            'arm' => 'nullable|numeric|between:15,80',
            'leg' => 'nullable|numeric|between:20,120',
            'chest' => 'nullable|numeric|between:50,200',
            'measured_at' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $stat = BodyStat::create(array_merge($request->all(), [
            'user_id' => $student->id,
            'recorded_by' => $trainer->id,
        ]));

        // Re-evaluate achievements for the student
        app(\App\Services\AchievementService::class)->evaluate($student);

        return redirect()->route('trainer.students.show', $student)
            ->with('success', '¡Medidas corporales registradas correctamente!');
    }
}
