<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use App\Models\WeightLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WeightLogController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $exercises = Exercise::orderBy('name', 'asc')->get();

        // Exercise filter for personal progression chart
        $selectedExerciseId = $request->get('exercise_id', $exercises->first()?->id);
        $selectedExercise = $exercises->firstWhere('id', $selectedExerciseId);

        // 1. Personal Logs
        $personalLogs = WeightLog::with('exercise')
            ->where('user_id', $user->id)
            ->orderBy('logged_at', 'desc')
            ->get();

        // Personal PRs (Max weight per exercise for current student)
        $personalPrs = WeightLog::where('user_id', $user->id)
            ->select('exercise_id', DB::raw('MAX(weight_kg) as max_weight'), DB::raw('MAX(one_rep_max) as max_1rm'))
            ->groupBy('exercise_id')
            ->pluck('max_weight', 'exercise_id');

        // Personal Chart Data for selected exercise
        $chartLogs = WeightLog::where('user_id', $user->id)
            ->where('exercise_id', $selectedExerciseId)
            ->orderBy('logged_at', 'asc')
            ->get();

        $chartLabels = $chartLogs->pluck('logged_at')->map(fn($d) => $d->format('d/m/Y'))->toArray();
        $chartWeights = $chartLogs->pluck('weight_kg')->toArray();
        $chartOneRepMax = $chartLogs->pluck('one_rep_max')->toArray();

        // 2. Leaderboard / Ranking General
        $leaderboardFilterExerciseId = $request->get('leaderboard_exercise_id');

        $leaderboardQuery = WeightLog::with(['user', 'exercise'])
            ->select('user_id', 'exercise_id', DB::raw('MAX(weight_kg) as max_weight'), DB::raw('MAX(one_rep_max) as max_1rm'), DB::raw('MAX(logged_at) as last_logged'))
            ->groupBy('user_id', 'exercise_id');

        if ($leaderboardFilterExerciseId) {
            $leaderboardQuery->where('exercise_id', $leaderboardFilterExerciseId);
        }

        $leaderboardRecords = $leaderboardQuery
            ->get()
            ->sortByDesc('max_weight')
            ->values();

        // Overall Top 3 strongest students across all exercises
        $topStudentsOverall = WeightLog::with('user')
            ->select('user_id', DB::raw('MAX(weight_kg) as top_weight'), DB::raw('COUNT(DISTINCT exercise_id) as total_exercises'))
            ->groupBy('user_id')
            ->orderBy('top_weight', 'desc')
            ->take(3)
            ->get();

        return view('student.weights.index', compact(
            'exercises',
            'selectedExerciseId',
            'selectedExercise',
            'personalLogs',
            'personalPrs',
            'chartLabels',
            'chartWeights',
            'chartOneRepMax',
            'leaderboardRecords',
            'leaderboardFilterExerciseId',
            'topStudentsOverall'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'exercise_id' => 'required|exists:exercises,id',
            'weight_kg' => 'required|numeric|min:0.5|max:1000',
            'reps' => 'required|integer|min:1|max:100',
            'logged_at' => 'required|date',
            'notes' => 'nullable|string|max:255',
        ]);

        $oneRepMax = WeightLog::calculateOneRepMax(
            (float) $validated['weight_kg'],
            (int) $validated['reps']
        );

        WeightLog::create([
            'user_id' => auth()->id(),
            'exercise_id' => $validated['exercise_id'],
            'weight_kg' => $validated['weight_kg'],
            'reps' => $validated['reps'],
            'one_rep_max' => $oneRepMax,
            'logged_at' => $validated['logged_at'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->back()->with('success', '¡Registro de peso guardado con éxito en tu historial! 💪');
    }

    public function destroy(WeightLog $weightLog)
    {
        if ($weightLog->user_id !== auth()->id()) {
            abort(403, 'Acción no autorizada.');
        }

        $weightLog->delete();

        return redirect()->back()->with('success', 'Registro de peso eliminado correctamente.');
    }
}
