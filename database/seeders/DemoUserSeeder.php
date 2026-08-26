<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\MembershipPlan;
use App\Models\Membership;
use App\Models\Attendance;
use App\Models\BodyStat;
use App\Models\Observation;
use App\Models\Routine;
use App\Models\RoutineDay;
use App\Models\RoutineDayExercise;
use App\Models\UserRoutine;
use App\Models\Exercise;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $trainerRole = Role::where('name', 'trainer')->first();
        $studentRole = Role::where('name', 'student')->first();

        // 1. Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@gym.com'],
            [
                'name' => 'Admin Legionario',
                'password' => Hash::make('password'),
                'phone' => '+56911111111',
                'is_active' => true,
            ]
        );
        $admin->roles()->sync([$adminRole->id]);

        // 2. Trainer
        $trainer = User::firstOrCreate(
            ['email' => 'entrenador@gym.com'],
            [
                'name' => 'Prof. Carlos Silva',
                'password' => Hash::make('password'),
                'phone' => '+56922222222',
                'objective' => 'Entrenar con disciplina y enfoque técnico para guiar a los alumnos.',
                'is_active' => true,
            ]
        );
        $trainer->roles()->sync([$trainerRole->id]);

        // 3. Students
        $studentsData = [
            [
                'name' => 'Juan Pérez',
                'email' => 'juan@gym.com',
                'phone' => '+56933333333',
                'objective' => 'Ganar masa muscular y mejorar mi fuerza general.',
                'birth_date' => '1995-05-15',
                'weight' => 78.5,
                'height' => 176.0,
                'body_fat' => 18.2,
                'muscle_mass' => 38.4,
                'waist' => 84.0,
                'hip' => 96.0,
                'arm' => 35.0,
                'leg' => 54.0,
                'chest' => 102.0,
            ],
            [
                'name' => 'María González',
                'email' => 'maria@gym.com',
                'phone' => '+56944444444',
                'objective' => 'Pérdida de peso, tonificación muscular y acondicionamiento físico.',
                'birth_date' => '1998-09-22',
                'weight' => 64.2,
                'height' => 162.0,
                'body_fat' => 26.5,
                'muscle_mass' => 25.1,
                'waist' => 72.0,
                'hip' => 104.0,
                'arm' => 28.5,
                'leg' => 51.0,
                'chest' => 92.0,
            ],
            [
                'name' => 'Pedro Soto',
                'email' => 'pedro@gym.com',
                'phone' => '+56955555555',
                'objective' => 'Mejorar resistencia cardiovascular y movilidad de rodillas.',
                'birth_date' => '1989-12-05',
                'weight' => 88.0,
                'height' => 180.0,
                'body_fat' => 24.1,
                'muscle_mass' => 36.8,
                'waist' => 94.0,
                'hip' => 102.0,
                'arm' => 37.0,
                'leg' => 56.0,
                'chest' => 106.0,
            ],
        ];

        $mensual = MembershipPlan::where('name', 'Plan Mensual')->first();
        $trimestral = MembershipPlan::where('name', 'Plan Trimestral')->first();

        foreach ($studentsData as $idx => $sData) {
            $student = User::firstOrCreate(
                ['email' => $sData['email']],
                [
                    'name' => $sData['name'],
                    'password' => Hash::make('password'),
                    'phone' => $sData['phone'],
                    'birth_date' => $sData['birth_date'],
                    'objective' => $sData['objective'],
                    'trainer_id' => $trainer->id,
                    'is_active' => true,
                ]
            );
            $student->roles()->sync([$studentRole->id]);

            // Assign Membership
            $plan = ($idx === 1) ? $trimestral : $mensual;
            Membership::create([
                'user_id' => $student->id,
                'plan_id' => $plan->id,
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->addDays($plan->duration_days - 10),
                'status' => 'active',
                'price_paid' => $plan->price,
                'created_by' => $admin->id,
            ]);

            // Assign Body Stats (2 entries to show progression)
            // First entry: 1 month ago
            BodyStat::create([
                'user_id' => $student->id,
                'weight' => $sData['weight'] + 2.5, // weighed more
                'height' => $sData['height'],
                'body_fat' => $sData['body_fat'] + 2.0,
                'muscle_mass' => $sData['muscle_mass'] - 1.2,
                'waist' => $sData['waist'] + 3.0,
                'hip' => $sData['hip'] + 2.0,
                'arm' => $sData['arm'] - 1.0,
                'leg' => $sData['leg'] - 1.5,
                'chest' => $sData['chest'] - 2.0,
                'measured_at' => Carbon::now()->subMonths(1),
                'recorded_by' => $trainer->id,
                'notes' => 'Medición inicial de ingreso.',
            ]);

            // Current entry: Today
            BodyStat::create([
                'user_id' => $student->id,
                'weight' => $sData['weight'],
                'height' => $sData['height'],
                'body_fat' => $sData['body_fat'],
                'muscle_mass' => $sData['muscle_mass'],
                'waist' => $sData['waist'],
                'hip' => $sData['hip'],
                'arm' => $sData['arm'],
                'leg' => $sData['leg'],
                'chest' => $sData['chest'],
                'measured_at' => Carbon::now(),
                'recorded_by' => $trainer->id,
                'notes' => 'Segunda evaluación. Muestra progresos constantes.',
            ]);

            // Assign Attendances: 6 attendances in the last 10 days
            for ($i = 9; $i >= 0; $i -= 1.5) {
                // Skips some days
                if (intval($i) % 3 === 0) continue;
                Attendance::create([
                    'user_id' => $student->id,
                    'checked_in_at' => Carbon::now()->subDays(intval($i))->hour(rand(8, 20))->minute(rand(0, 59)),
                    'notes' => 'Ingreso normal por QR físico.',
                ]);
            }

            // Assign Observations
            Observation::create([
                'trainer_id' => $trainer->id,
                'student_id' => $student->id,
                'content' => 'Corregir la postura en sentadillas. Mantener la espalda recta y no levantar talones.',
                'is_private' => false,
                'category' => 'technique',
            ]);

            Observation::create([
                'trainer_id' => $trainer->id,
                'student_id' => $student->id,
                'content' => 'Muy buena progresión en el peso muerto. Mantén el ritmo de asistencia.',
                'is_private' => false,
                'category' => 'motivation',
                'is_pinned' => true,
            ]);

            Observation::create([
                'trainer_id' => $trainer->id,
                'student_id' => $student->id,
                'content' => 'Comentarios privados: Muestra cierta molestia al hacer esfuerzo de hombros. Monitorear.',
                'is_private' => true,
                'category' => 'injury',
            ]);
        }

        // 4. Create a Demo Routine and Assign to Student 1 (Juan)
        $routine = Routine::create([
            'trainer_id' => $trainer->id,
            'name' => 'Fuerza e Hipertrofia (A/B)',
            'description' => 'Rutina base de 4 días enfocada en ganar fuerza general y masa muscular.',
            'difficulty' => 'intermediate',
            'goal' => 'hypertrophy',
            'is_template' => false,
            'duration_weeks' => 8,
        ]);

        // Day 1: Empuje (Lunes)
        $day1 = RoutineDay::create([
            'routine_id' => $routine->id,
            'day_number' => 1, // Lunes
            'name' => 'Día A - Empuje (Pecho/Hombro/Tríceps)',
            'focus_area' => 'chest',
            'is_rest_day' => false,
        ]);

        $ex1 = Exercise::where('name', 'Press de Banca Plano')->first();
        $ex2 = Exercise::where('name', 'Press Militar con Barra')->first();
        $ex3 = Exercise::where('name', 'Vuelos Laterales con Mancuernas')->first();
        $ex4 = Exercise::where('name', 'Extensión de Tríceps en Polea')->first();

        RoutineDayExercise::create([
            'routine_day_id' => $day1->id,
            'exercise_id' => $ex1->id,
            'sets' => 4,
            'reps' => '8-10',
            'weight_kg' => 60.0,
            'rest_seconds' => 90,
            'order' => 1,
            'notes' => 'Enfocarse en la fase excéntrica controlada.',
        ]);

        RoutineDayExercise::create([
            'routine_day_id' => $day1->id,
            'exercise_id' => $ex2->id,
            'sets' => 3,
            'reps' => '10',
            'weight_kg' => 30.0,
            'rest_seconds' => 90,
            'order' => 2,
        ]);

        RoutineDayExercise::create([
            'routine_day_id' => $day1->id,
            'exercise_id' => $ex3->id,
            'sets' => 3,
            'reps' => '12-15',
            'weight_kg' => 10.0,
            'rest_seconds' => 60,
            'order' => 3,
        ]);

        RoutineDayExercise::create([
            'routine_day_id' => $day1->id,
            'exercise_id' => $ex4->id,
            'sets' => 3,
            'reps' => '12',
            'weight_kg' => 22.0,
            'rest_seconds' => 60,
            'order' => 4,
        ]);

        // Day 2: Tirón (Martes)
        $day2 = RoutineDay::create([
            'routine_id' => $routine->id,
            'day_number' => 2, // Martes
            'name' => 'Día B - Tirón (Espalda/Bíceps/Core)',
            'focus_area' => 'back',
            'is_rest_day' => false,
        ]);

        $ex5 = Exercise::where('name', 'Jalón al Pecho')->first();
        $ex6 = Exercise::where('name', 'Remo con Barra')->first();
        $ex7 = Exercise::where('name', 'Curl de Bíceps con Barra')->first();
        $ex8 = Exercise::where('name', 'Plancha Abdominal')->first();

        RoutineDayExercise::create([
            'routine_day_id' => $day2->id,
            'exercise_id' => $ex5->id,
            'sets' => 4,
            'reps' => '10-12',
            'weight_kg' => 50.0,
            'rest_seconds' => 90,
            'order' => 1,
        ]);

        RoutineDayExercise::create([
            'routine_day_id' => $day2->id,
            'exercise_id' => $ex6->id,
            'sets' => 3,
            'reps' => '8',
            'weight_kg' => 40.0,
            'rest_seconds' => 90,
            'order' => 2,
        ]);

        RoutineDayExercise::create([
            'routine_day_id' => $day2->id,
            'exercise_id' => $ex7->id,
            'sets' => 3,
            'reps' => '10',
            'weight_kg' => 25.0,
            'rest_seconds' => 60,
            'order' => 3,
        ]);

        RoutineDayExercise::create([
            'routine_day_id' => $day2->id,
            'exercise_id' => $ex8->id,
            'sets' => 3,
            'reps' => '45 segundos',
            'weight_kg' => null,
            'rest_seconds' => 45,
            'order' => 4,
        ]);

        // Assign to Juan (Juan has ID 3 or 4 based on firstOrCreate, let's query by email)
        $juanUser = User::where('email', 'juan@gym.com')->first();
        $mariaUser = User::where('email', 'maria@gym.com')->first();
        $pedroUser = User::where('email', 'pedro@gym.com')->first();

        UserRoutine::create([
            'user_id' => $juanUser->id,
            'routine_id' => $routine->id,
            'assigned_at' => Carbon::now()->subDays(5),
            'is_active' => true,
        ]);

        // 5. Seed Weight Logs for Students Progression & Leaderboard
        $benchPress = Exercise::where('name', 'Press de Banca Plano')->first();
        $squat = Exercise::where('name', 'Sentadilla Trasera con Barra')->first();
        $deadlift = Exercise::where('name', 'Peso Muerto Convencional')->first();
        $militaryPress = Exercise::where('name', 'Press Militar con Barra')->first();

        if ($benchPress && $squat && $deadlift) {
            // Juan's Logs (Progression over 4 weeks)
            \App\Models\WeightLog::create(['user_id' => $juanUser->id, 'exercise_id' => $benchPress->id, 'weight_kg' => 60.0, 'reps' => 5, 'one_rep_max' => \App\Models\WeightLog::calculateOneRepMax(60.0, 5), 'logged_at' => Carbon::now()->subWeeks(3), 'notes' => 'Técnica limpia']);
            \App\Models\WeightLog::create(['user_id' => $juanUser->id, 'exercise_id' => $benchPress->id, 'weight_kg' => 65.0, 'reps' => 5, 'one_rep_max' => \App\Models\WeightLog::calculateOneRepMax(65.0, 5), 'logged_at' => Carbon::now()->subWeeks(2), 'notes' => 'Sensación fuerte']);
            \App\Models\WeightLog::create(['user_id' => $juanUser->id, 'exercise_id' => $benchPress->id, 'weight_kg' => 70.0, 'reps' => 5, 'one_rep_max' => \App\Models\WeightLog::calculateOneRepMax(70.0, 5), 'logged_at' => Carbon::now()->subWeeks(1), 'notes' => 'Nuevo PR parcial']);
            \App\Models\WeightLog::create(['user_id' => $juanUser->id, 'exercise_id' => $benchPress->id, 'weight_kg' => 75.0, 'reps' => 3, 'one_rep_max' => \App\Models\WeightLog::calculateOneRepMax(75.0, 3), 'logged_at' => Carbon::now(), 'notes' => '¡Nuevo Récord Personal! 🏆']);

            \App\Models\WeightLog::create(['user_id' => $juanUser->id, 'exercise_id' => $squat->id, 'weight_kg' => 85.0, 'reps' => 5, 'one_rep_max' => \App\Models\WeightLog::calculateOneRepMax(85.0, 5), 'logged_at' => Carbon::now()->subWeeks(2)]);
            \App\Models\WeightLog::create(['user_id' => $juanUser->id, 'exercise_id' => $squat->id, 'weight_kg' => 95.0, 'reps' => 5, 'one_rep_max' => \App\Models\WeightLog::calculateOneRepMax(95.0, 5), 'logged_at' => Carbon::now(), 'notes' => 'Profundidad perfecta']);

            \App\Models\WeightLog::create(['user_id' => $juanUser->id, 'exercise_id' => $deadlift->id, 'weight_kg' => 110.0, 'reps' => 3, 'one_rep_max' => \App\Models\WeightLog::calculateOneRepMax(110.0, 3), 'logged_at' => Carbon::now()]);

            // Pedro's Logs (Heavy Lifter - Competitor)
            \App\Models\WeightLog::create(['user_id' => $pedroUser->id, 'exercise_id' => $benchPress->id, 'weight_kg' => 80.0, 'reps' => 5, 'one_rep_max' => \App\Models\WeightLog::calculateOneRepMax(80.0, 5), 'logged_at' => Carbon::now()->subWeeks(1)]);
            \App\Models\WeightLog::create(['user_id' => $pedroUser->id, 'exercise_id' => $squat->id, 'weight_kg' => 110.0, 'reps' => 5, 'one_rep_max' => \App\Models\WeightLog::calculateOneRepMax(110.0, 5), 'logged_at' => Carbon::now()]);
            \App\Models\WeightLog::create(['user_id' => $pedroUser->id, 'exercise_id' => $deadlift->id, 'weight_kg' => 140.0, 'reps' => 3, 'one_rep_max' => \App\Models\WeightLog::calculateOneRepMax(140.0, 3), 'logged_at' => Carbon::now(), 'notes' => 'Líder en Peso Muerto']);

            // María's Logs
            \App\Models\WeightLog::create(['user_id' => $mariaUser->id, 'exercise_id' => $benchPress->id, 'weight_kg' => 45.0, 'reps' => 8, 'one_rep_max' => \App\Models\WeightLog::calculateOneRepMax(45.0, 8), 'logged_at' => Carbon::now()]);
            \App\Models\WeightLog::create(['user_id' => $mariaUser->id, 'exercise_id' => $squat->id, 'weight_kg' => 65.0, 'reps' => 6, 'one_rep_max' => \App\Models\WeightLog::calculateOneRepMax(65.0, 6), 'logged_at' => Carbon::now()]);
        }
    }
}
