<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoutineController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        // Get active routine
        $activeUserRoutine = $user->activeRoutine()
            ->with(['routine.days' => function($q) {
                $q->orderBy('day_number');
            }, 'routine.days.exercises' => function($q) {
                $q->orderBy('order');
            }, 'routine.days.exercises.exercise'])
            ->first();

        $routine = $activeUserRoutine ? $activeUserRoutine->routine : null;

        // Current day number (1-7) to highlight today's routine
        $todayDayNumber = now()->dayOfWeekIso;

        return view('student.routine', compact('user', 'routine', 'todayDayNumber'));
    }
}
