<?php

namespace Database\Seeders;

use App\Models\MembershipPlan;
use Illuminate\Database\Seeder;

class MembershipPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Plan Mensual',
                'description' => 'Acceso libre a máquinas y entrenadores durante 30 días.',
                'price' => 35000.00,
                'duration_days' => 30,
                'color' => '#6C3CF7', // Violeta
                'features' => ['Acceso ilimitado', 'Evaluación inicial', 'Rutina personalizada'],
                'is_active' => true,
            ],
            [
                'name' => 'Plan Trimestral',
                'description' => 'Paga 3 meses con descuento. Ideal para compromisos a mediano plazo.',
                'price' => 90000.00,
                'duration_days' => 90,
                'color' => '#FF6B35', // Naranja
                'features' => ['Acceso ilimitado', 'Evaluación mensual', 'Rutina personalizada', '1 Pase de invitado al mes'],
                'is_active' => true,
            ],
            [
                'name' => 'Plan Anual',
                'description' => 'Tu año completo de fitness con el mejor precio y beneficios premium.',
                'price' => 300000.00,
                'duration_days' => 365,
                'color' => '#22C55E', // Verde
                'features' => ['Acceso ilimitado', 'Evaluación mensual', 'Rutina personalizada', 'Pases de invitado ilimitados', 'Polera de regalo'],
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            MembershipPlan::firstOrCreate(['name' => $plan['name']], $plan);
        }
    }
}
