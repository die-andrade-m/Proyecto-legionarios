<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\AttendanceService;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        if ($user->hasCheckedInToday()) {
            return redirect()->back()->with('error', 'Ya has registrado tu asistencia por el día de hoy.');
        }

        $notes = 'Auto-registro de asistencia mediante plataforma.';
        $attendance = $this->attendanceService->checkIn($user, $notes);

        if ($attendance) {
            // Re-evaluate achievements in case one was unlocked
            return redirect()->back()->with('success', '¡Asistencia registrada con éxito! Sigue entrenando duro.');
        }

        return redirect()->back()->with('error', 'Hubo un problema al registrar tu asistencia.');
    }
}
