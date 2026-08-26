<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\BodyStat;
use App\Models\ProgressPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgressController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        // Get body stats ordered by date
        $bodyStats = $user->bodyStats;

        // Progress Photos grouped by date
        $photos = $user->progressPhotos;

        // Prepare data for Chart.js
        $chartData = [
            'labels' => [],
            'weight' => [],
            'fat' => [],
            'muscle' => [],
        ];

        // We reverse them to show chronologically in the chart (past to present)
        foreach ($bodyStats->reverse() as $stat) {
            $chartData['labels'][] = $stat->measured_at->format('d/m/Y');
            $chartData['weight'][] = $stat->weight;
            $chartData['fat'][] = $stat->body_fat;
            $chartData['muscle'][] = $stat->muscle_mass;
        }

        return view('student.progress', compact('user', 'bodyStats', 'photos', 'chartData'));
    }

    public function storePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:5120', // Max 5MB
            'angle' => 'required|in:front,side,back,other',
            'caption' => 'nullable|string|max:150',
            'taken_at' => 'required|date',
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('progress_photos/' . $user->id, 'public');

            ProgressPhoto::create([
                'user_id' => $user->id,
                'photo_path' => $path,
                'caption' => $request->caption,
                'angle' => $request->angle,
                'taken_at' => $request->taken_at,
            ]);

            // Re-evaluate achievements (e.g. upload first photo)
            app(\App\Services\AchievementService::class)->evaluate($user);

            return redirect()->back()->with('success', '¡Foto de progreso subida con éxito!');
        }

        return redirect()->back()->with('error', 'No se pudo subir la foto.');
    }
}
