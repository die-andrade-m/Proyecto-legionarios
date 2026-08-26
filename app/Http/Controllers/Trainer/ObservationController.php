<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Observation;
use Illuminate\Http\Request;

class ObservationController extends Controller
{
    public function store(Request $request, User $student)
    {
        /** @var \App\Models\User $trainer */
        $trainer = $request->user();

        if ($student->trainer_id !== $trainer->id) {
            abort(403);
        }

        $request->validate([
            'content' => 'required|string|max:1000',
            'category' => 'required|in:technique,nutrition,motivation,general,injury,progress',
            'is_private' => 'required|boolean',
            'is_pinned' => 'nullable|boolean',
        ]);

        Observation::create([
            'trainer_id' => $trainer->id,
            'student_id' => $student->id,
            'content' => $request->content,
            'category' => $request->category,
            'is_private' => $request->is_private,
            'is_pinned' => $request->has('is_pinned') ? $request->is_pinned : false,
        ]);

        return redirect()->back()->with('success', '¡Observación agregada con éxito!');
    }

    public function destroy(Request $request, Observation $observation)
    {
        /** @var \App\Models\User $trainer */
        $trainer = $request->user();

        if ($observation->trainer_id !== $trainer->id) {
            abort(403);
        }

        $observation->delete();

        return redirect()->back()->with('success', 'Observación eliminada.');
    }
}
