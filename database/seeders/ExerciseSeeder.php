<?php

namespace Database\Seeders;

use App\Models\Exercise;
use Illuminate\Database\Seeder;

class ExerciseSeeder extends Seeder
{
    public function run(): void
    {
        $exercises = [
            // Pecho (Chest)
            [
                'name' => 'Press de Banca Plano',
                'description' => 'Acuéstese en un banco plano, sostenga la barra con los brazos extendidos sobre el pecho y bájela controladamente hasta tocar el pecho.',
                'muscle_group' => 'chest',
                'secondary_muscle_group' => 'arms',
                'equipment' => 'barbell',
                'difficulty' => 'intermediate',
            ],
            [
                'name' => 'Aperturas con Mancuernas',
                'description' => 'Acuéstese en un banco plano, sostenga mancuernas sobre el pecho con codos levemente flectados y abra los brazos hacia los costados.',
                'muscle_group' => 'chest',
                'secondary_muscle_group' => 'shoulders',
                'equipment' => 'dumbbell',
                'difficulty' => 'beginner',
            ],
            [
                'name' => 'Cruce de Poleas',
                'description' => 'De pie en el centro de dos poleas altas, junte las manos al frente y abajo contrayendo los pectorales.',
                'muscle_group' => 'chest',
                'secondary_muscle_group' => 'shoulders',
                'equipment' => 'cable',
                'difficulty' => 'intermediate',
            ],
            [
                'name' => 'Flexiones de Brazo (Push-ups)',
                'description' => 'En posición de plancha, baje el cuerpo flexionando los brazos hasta tocar el suelo con el pecho y suba empujando.',
                'muscle_group' => 'chest',
                'secondary_muscle_group' => 'core',
                'equipment' => 'bodyweight',
                'difficulty' => 'beginner',
            ],

            // Espalda (Back)
            [
                'name' => 'Dominadas (Pull-ups)',
                'description' => 'Cuélguese de una barra con agarre prono y suba el cuerpo hasta que el mentón pase la barra.',
                'muscle_group' => 'back',
                'secondary_muscle_group' => 'arms',
                'equipment' => 'bodyweight',
                'difficulty' => 'advanced',
            ],
            [
                'name' => 'Jalón al Pecho',
                'description' => 'Sentado en la polea alta con agarre ancho, tire de la barra hacia la parte superior del pecho.',
                'muscle_group' => 'back',
                'secondary_muscle_group' => 'arms',
                'equipment' => 'machine',
                'difficulty' => 'beginner',
            ],
            [
                'name' => 'Remo con Barra',
                'description' => 'Con rodillas flexionadas e inclinando el torso hacia adelante, tire de la barra hacia el abdomen bajo.',
                'muscle_group' => 'back',
                'secondary_muscle_group' => 'arms',
                'equipment' => 'barbell',
                'difficulty' => 'intermediate',
            ],
            [
                'name' => 'Remo Sentado en Polea',
                'description' => 'Sentado de cara a la polea baja, tire de los agarres hacia el abdomen contrayendo la espalda dorsal.',
                'muscle_group' => 'back',
                'secondary_muscle_group' => 'arms',
                'equipment' => 'cable',
                'difficulty' => 'beginner',
            ],

            // Piernas (Legs)
            [
                'name' => 'Sentadillas Trasera con Barra (Squat)',
                'description' => 'Coloque la barra en los hombros, baje la cadera manteniendo la espalda recta e inclinando las rodillas sin pasar la punta de los pies.',
                'muscle_group' => 'legs',
                'secondary_muscle_group' => 'core',
                'equipment' => 'barbell',
                'difficulty' => 'intermediate',
            ],
            [
                'name' => 'Prensa de Piernas',
                'description' => 'Sentado en la máquina prensa, empuje la plataforma con los pies controladamente.',
                'muscle_group' => 'legs',
                'secondary_muscle_group' => 'gluteus',
                'equipment' => 'machine',
                'difficulty' => 'beginner',
            ],
            [
                'name' => 'Extensión de Cuádriceps',
                'description' => 'Sentado en la máquina, extienda las piernas para levantar el rodillo.',
                'muscle_group' => 'legs',
                'secondary_muscle_group' => 'none',
                'equipment' => 'machine',
                'difficulty' => 'beginner',
            ],
            [
                'name' => 'Curl de Pierna Acostado',
                'description' => 'Acuéstese boca abajo y flexione las rodillas trayendo el rodillo hacia los glúteos.',
                'muscle_group' => 'legs',
                'secondary_muscle_group' => 'gluteus',
                'equipment' => 'machine',
                'difficulty' => 'beginner',
            ],
            [
                'name' => 'Peso Muerto Rumano',
                'description' => 'De pie con barra, baje el torso manteniendo la espalda recta y empujando la cadera hacia atrás con rodillas semiflexionadas.',
                'muscle_group' => 'legs',
                'secondary_muscle_group' => 'back',
                'equipment' => 'barbell',
                'difficulty' => 'intermediate',
            ],

            // Hombros (Shoulders)
            [
                'name' => 'Press Militar con Barra',
                'description' => 'Empuje la barra de manera vertical sobre la cabeza desde la posición de los hombros de pie.',
                'muscle_group' => 'shoulders',
                'secondary_muscle_group' => 'arms',
                'equipment' => 'barbell',
                'difficulty' => 'intermediate',
            ],
            [
                'name' => 'Vuelos Laterales con Mancuernas',
                'description' => 'De pie, levante las mancuernas hacia los lados hasta que los brazos queden paralelos al suelo.',
                'muscle_group' => 'shoulders',
                'secondary_muscle_group' => 'none',
                'equipment' => 'dumbbell',
                'difficulty' => 'beginner',
            ],
            [
                'name' => 'Pájaros con Mancuerna (Posterior)',
                'description' => 'Incline el torso hacia adelante y levante las mancuernas lateralmente enfocando el deltoides posterior.',
                'muscle_group' => 'shoulders',
                'secondary_muscle_group' => 'back',
                'equipment' => 'dumbbell',
                'difficulty' => 'intermediate',
            ],

            // Brazos (Arms)
            [
                'name' => 'Curl de Bíceps con Barra',
                'description' => 'De pie con agarre supino de barra, flexione los codos subiendo la barra controladamente.',
                'muscle_group' => 'arms',
                'secondary_muscle_group' => 'forearms',
                'equipment' => 'barbell',
                'difficulty' => 'beginner',
            ],
            [
                'name' => 'Curl de Bíceps Alternado con Mancuernas',
                'description' => 'Curl alternando cada brazo girando la muñeca al subir.',
                'muscle_group' => 'arms',
                'secondary_muscle_group' => 'forearms',
                'equipment' => 'dumbbell',
                'difficulty' => 'beginner',
            ],
            [
                'name' => 'Extensión de Tríceps en Polea',
                'description' => 'Empuje el manillar de la polea hacia abajo manteniendo los codos pegados al cuerpo.',
                'muscle_group' => 'arms',
                'secondary_muscle_group' => 'none',
                'equipment' => 'cable',
                'difficulty' => 'beginner',
            ],
            [
                'name' => 'Fondos de Tríceps en Banco',
                'description' => 'Apoye las manos en el borde de un banco a su espalda, baje y suba el cuerpo flexionando los codos.',
                'muscle_group' => 'arms',
                'secondary_muscle_group' => 'chest',
                'equipment' => 'bodyweight',
                'difficulty' => 'beginner',
            ],

            // Core
            [
                'name' => 'Abdominales Crunch',
                'description' => 'Acuéstese boca arriba, flexione el torso elevando ligeramente los hombros del suelo.',
                'muscle_group' => 'core',
                'secondary_muscle_group' => 'none',
                'equipment' => 'bodyweight',
                'difficulty' => 'beginner',
            ],
            [
                'name' => 'Plancha Abdominal',
                'description' => 'Posición estática apoyado sobre antebrazos y puntas de pie, manteniendo el cuerpo recto y core contraído.',
                'muscle_group' => 'core',
                'secondary_muscle_group' => 'back',
                'equipment' => 'bodyweight',
                'difficulty' => 'beginner',
            ],
            [
                'name' => 'Elevación de Piernas Colgado',
                'description' => 'Colgado de la barra, eleve las piernas extendidas o flectando rodillas hacia el pecho.',
                'muscle_group' => 'core',
                'secondary_muscle_group' => 'legs',
                'equipment' => 'bodyweight',
                'difficulty' => 'intermediate',
            ],

            // Cardio
            [
                'name' => 'Trotadora / Running',
                'description' => 'Trote o carrera continua en trotadora.',
                'muscle_group' => 'cardio',
                'secondary_muscle_group' => 'legs',
                'equipment' => 'machine',
                'difficulty' => 'beginner',
            ],
            [
                'name' => 'Bicicleta Estática',
                'description' => 'Pedaleo continuo a intensidad variable.',
                'muscle_group' => 'cardio',
                'secondary_muscle_group' => 'legs',
                'equipment' => 'machine',
                'difficulty' => 'beginner',
            ],
        ];

        foreach ($exercises as $exercise) {
            Exercise::firstOrCreate(['name' => $exercise['name']], $exercise);
        }
    }
}
