<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Routine;
use App\Models\RoutineDay;
use App\Models\RoutineDayExercise;
use App\Models\UserRoutine;
use App\Models\User;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RoutineController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $trainer */
        $trainer = $request->user();

        // Templates & trainer's own routines
        $routines = Routine::where('trainer_id', $trainer->id)
            ->orWhere('is_template', true)
            ->withCount('userRoutines')
            ->get();

        return view('trainer.routines.index', compact('routines'));
    }

    public function show(Request $request, Routine $routine)
    {
        $routine->load('days.exercises.exercise');
        $students = User::role('student')
            ->where('trainer_id', $request->user()->id)
            ->orderBy('name')
            ->get();

        return view('trainer.routines.show', compact('routine', 'students'));
    }

    public function create()
    {
        $exercises = Exercise::active()->orderBy('name')->get();
        return view('trainer.routines.create', compact('exercises'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'goal' => 'required|string',
            'is_template' => 'required|boolean',
            'duration_weeks' => 'nullable|integer|min:1',
            // Days & exercises array validation
            'days' => 'required|array|min:1',
            'days.*.name' => 'required|string',
            'days.*.focus_area' => 'nullable|string',
            'days.*.is_rest_day' => 'required|boolean',
            'days.*.exercises' => 'nullable|array',
            'days.*.exercises.*.exercise_id' => 'required|exists:exercises,id',
            'days.*.exercises.*.sets' => 'required|integer|min:1',
            'days.*.exercises.*.reps' => 'required|string',
            'days.*.exercises.*.weight_kg' => 'nullable|numeric|min:0',
            'days.*.exercises.*.rest_seconds' => 'required|integer|min:0',
            'days.*.exercises.*.notes' => 'nullable|string',
        ]);

        /** @var \App\Models\User $trainer */
        $trainer = $request->user();

        $routine = Routine::create([
            'trainer_id' => $trainer->id,
            'name' => $request->name,
            'description' => $request->description,
            'difficulty' => $request->difficulty,
            'goal' => $request->goal,
            'is_template' => $request->is_template,
            'duration_weeks' => $request->duration_weeks,
        ]);

        foreach ($request->days as $dayIndex => $dayData) {
            $dayNumber = $dayIndex + 1;
            $routineDay = RoutineDay::create([
                'routine_id' => $routine->id,
                'day_number' => $dayNumber,
                'name' => $dayData['name'],
                'focus_area' => $dayData['focus_area'] ?? null,
                'is_rest_day' => $dayData['is_rest_day'],
                'notes' => $dayData['notes'] ?? null,
            ]);

            if (!$dayData['is_rest_day'] && isset($dayData['exercises'])) {
                foreach ($dayData['exercises'] as $exIndex => $exData) {
                    RoutineDayExercise::create([
                        'routine_day_id' => $routineDay->id,
                        'exercise_id' => $exData['exercise_id'],
                        'sets' => $exData['sets'],
                        'reps' => $exData['reps'],
                        'weight_kg' => $exData['weight_kg'] ?? null,
                        'rest_seconds' => $exData['rest_seconds'],
                        'notes' => $exData['notes'] ?? null,
                        'order' => $exIndex + 1,
                    ]);
                }
            }
        }

        return redirect()->route('trainer.routines.index')->with('success', '¡Rutina creada con éxito!');
    }

    public function duplicate(Request $request, Routine $routine)
    {
        /** @var \App\Models\User $trainer */
        $trainer = $request->user();

        // Clone routine
        $newRoutine = $routine->replicate();
        $newRoutine->name = $routine->name . ' (Copia)';
        $newRoutine->trainer_id = $trainer->id;
        $newRoutine->is_template = false; // By default copy is an editable routine
        $newRoutine->save();

        // Clone days and exercises
        foreach ($routine->days as $day) {
            $newDay = $day->replicate();
            $newDay->routine_id = $newRoutine->id;
            $newDay->save();

            foreach ($day->exercises as $exercise) {
                $newEx = $exercise->replicate();
                $newEx->routine_day_id = $newDay->id;
                $newEx->save();
            }
        }

        return redirect()->route('trainer.routines.show', $newRoutine)
            ->with('success', '¡Rutina duplicada con éxito! Ya puedes asignarla o editarla.');
    }

    public function assign(Request $request, Routine $routine)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'duration_weeks' => 'nullable|integer|min:1',
        ]);

        $student = User::findOrFail($request->student_id);

        // Security check
        if ($student->trainer_id !== $request->user()->id) {
            abort(403);
        }

        // Deactivate previous active routines for this student
        UserRoutine::where('user_id', $student->id)->update(['is_active' => false]);

        $duration = $request->duration_weeks ?? $routine->duration_weeks ?? 8;

        UserRoutine::create([
            'user_id' => $student->id,
            'routine_id' => $routine->id,
            'assigned_at' => Carbon::now(),
            'ends_at' => Carbon::now()->addWeeks($duration),
            'is_active' => true,
        ]);

        // Evaluate achievements (first routine assigned/completed check)
        app(\App\Services\AchievementService::class)->evaluate($student);

        return redirect()->back()->with('success', '¡Rutina asignada con éxito al alumno ' . $student->name . '!');
    }
}
