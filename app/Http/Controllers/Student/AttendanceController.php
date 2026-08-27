<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Self-check-in has been disabled. Only trainers and admins can record attendance.
     */
    public function store(Request $request)
    {
        return redirect()->route('student.attendances.index')
            ->with('error', 'El registro de asistencia debe ser realizado directamente por el profesor o entrenador en el dojo.');
    }
}
