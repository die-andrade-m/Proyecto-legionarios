<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardRouterController extends Controller
{
    public function __invoke(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isTrainer()) {
            return redirect()->route('trainer.dashboard');
        }

        // Default to student
        return redirect()->route('student.dashboard');
    }
}
