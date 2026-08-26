<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $achievements = [
            [
                'name' => 'Primer Entrenamiento',
                'description' => '¡Has registrado tu primera asistencia en el gimnasio!',
                'icon' => '🔥',
                'badge_color' => '#6C3CF7',
                'condition_type' => 'attendances_count',
                'condition_value' => 1,
                'points' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'Legionario de Bronce',
                'description' => 'Completa 10 asistencias en total.',
                'icon' => '🥉',
                'badge_color' => '#CD7F32',
                'condition_type' => 'attendances_count',
                'condition_value' => 10,
                'points' => 50,
                'is_active' => true,
            ],
            [
                'name' => 'Legionario de Plata',
                'description' => 'Completa 30 asistencias en total.',
                'icon' => '🥈',
                'badge_color' => '#C0C0C0',
                'condition_type' => 'attendances_count',
                'condition_value' => 30,
                'points' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Legionario de Oro',
                'description' => 'Completa 100 asistencias en total.',
                'icon' => '🥇',
                'badge_color' => '#FFD700',
                'condition_type' => 'attendances_count',
                'condition_value' => 100,
                'points' => 300,
                'is_active' => true,
            ],
            [
                'name' => 'Constancia Pura',
                'description' => 'Logra una racha de 3 días seguidos asistiendo al gimnasio.',
                'icon' => '⚡',
                'badge_color' => '#FF6B35',
                'condition_type' => 'streak_days',
                'condition_value' => 3,
                'points' => 50,
                'is_active' => true,
            ],
            [
                'name' => 'Hábito de Acero',
                'description' => 'Logra una racha de 5 días seguidos asistiendo al gimnasio.',
                'icon' => '🐺',
                'badge_color' => '#3B82F6',
                'condition_type' => 'streak_days',
                'condition_value' => 5,
                'points' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Enfoque en Progreso',
                'description' => 'Registra tus primeras medidas corporales.',
                'icon' => '📐',
                'badge_color' => '#22C55E',
                'condition_type' => 'measurements_recorded',
                'condition_value' => 1,
                'points' => 20,
                'is_active' => true,
            ],
            [
                'name' => 'Modelo del Mes',
                'description' => 'Sube tu primera foto de progreso físico.',
                'icon' => '📸',
                'badge_color' => '#EC4899',
                'condition_type' => 'first_photo_uploaded',
                'condition_value' => 1,
                'points' => 20,
                'is_active' => true,
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::firstOrCreate(['name' => $achievement['name']], $achievement);
        }
    }
}
