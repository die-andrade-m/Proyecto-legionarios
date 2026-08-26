@extends('layouts.app')

@section('content')
<div class="row g-4" x-data="routineCreator()">
    <!-- Header Page -->
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <a href="{{ route('trainer.routines.index') }}" class="text-secondary text-decoration-none small d-flex align-items-center gap-1 mb-2">
                    <i data-lucide="arrow-left" style="width: 14px;"></i> Volver a listado
                </a>
                <h3 class="text-white mb-0 font-heading">Crear Nueva Rutina</h3>
            </div>
        </div>
    </div>

    <!-- Form Creator -->
    <div class="col-12">
        <form action="{{ route('trainer.routines.store') }}" method="POST">
            @csrf
            
            <div class="row g-4">
                <!-- Specifications Card -->
                <div class="col-lg-4">
                    <div class="glass-card p-4">
                        <h5 class="text-white mb-4">Especificaciones Generales</h5>
                        
                        <div class="mb-3">
                            <label class="form-label text-secondary small">Nombre de la Rutina*</label>
                            <input type="text" class="form-control bg-dark border-secondary border-opacity-25 text-white" name="name" placeholder="Ej. Hipertrofia Tren Superior" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary small">Descripción</label>
                            <textarea class="form-control bg-dark border-secondary border-opacity-25 text-white" name="description" rows="3" placeholder="Detalles o instrucciones globales..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary small">Dificultad*</label>
                            <select class="form-select bg-dark border-secondary border-opacity-25 text-white" name="difficulty" required>
                                <option value="beginner">Principiante</option>
                                <option value="intermediate" selected>Intermedio</option>
                                <option value="advanced">Avanzado</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary small">Objetivo Principal*</label>
                            <select class="form-select bg-dark border-secondary border-opacity-25 text-white" name="goal" required>
                                <option value="hypertrophy">Hipertrofia (Ganancia muscular)</option>
                                <option value="strength">Fuerza Máxima</option>
                                <option value="endurance">Resistencia Cardiovascular</option>
                                <option value="weight_loss">Pérdida de Peso</option>
                                <option value="general">Acondicionamiento General</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary small">Duración sugerida (Semanas)</label>
                            <input type="number" class="form-control bg-dark border-secondary border-opacity-25 text-white" name="duration_weeks" value="8">
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-secondary small">¿Es una plantilla pública?*</label>
                            <select class="form-select bg-dark border-secondary border-opacity-25 text-white" name="is_template" required>
                                <option value="0">No, es una rutina para asignar directo</option>
                                <option value="1" selected>Sí, es una plantilla reusable</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-premium w-100 py-3">
                            Guardar Rutina
                        </button>
                    </div>
                </div>

                <!-- Days and Exercises Builder -->
                <div class="col-lg-8">
                    <div class="glass-card p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="text-white mb-0">Planificación de Días</h5>
                            <button type="button" @click="addDay()" class="btn btn-sm btn-outline-primary border-primary border-opacity-20 text-white py-2 px-3 rounded-3 d-flex align-items-center gap-1">
                                <i data-lucide="plus" style="width: 16px;"></i>
                                Agregar Día
                            </button>
                        </div>

                        <div class="d-flex flex-column gap-4">
                            <template x-for="(day, dayIdx) in days" :key="dayIdx">
                                <div class="p-4 rounded bg-dark bg-opacity-35 border border-secondary border-opacity-10">
                                    <!-- Day Info -->
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex align-items-center gap-2 flex-grow-1 me-3">
                                            <span class="badge bg-primary px-3 py-2" x-text="'Día ' + (dayIdx + 1)"></span>
                                            <input type="text" :name="'days['+dayIdx+'][name]'" class="form-control form-control-sm bg-transparent border-0 border-bottom border-secondary text-white fw-bold fs-5 px-1 py-0 w-50" placeholder="Ej. Lunes - Pecho" x-model="day.name" required>
                                        </div>
                                        
                                        <button type="button" @click="removeDay(dayIdx)" class="btn btn-sm btn-link text-danger border-0 p-0" title="Eliminar Día">
                                            <i data-lucide="trash" style="width: 18px;"></i>
                                        </button>
                                    </div>

                                    <div class="row g-2 mb-3">
                                        <div class="col-sm-6">
                                            <label class="form-label text-secondary small">Zona de enfoque (Ej. Pectoral, Dorsales)</label>
                                            <input type="text" :name="'days['+dayIdx+'][focus_area]'" class="form-control form-control-sm bg-dark border-secondary border-opacity-25 text-white" placeholder="Ej. Pecho / Tríceps" x-model="day.focus_area">
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label text-secondary small">¿Es día de descanso?</label>
                                            <select :name="'days['+dayIdx+'][is_rest_day]'" class="form-select form-select-sm bg-dark border-secondary border-opacity-25 text-white" x-model="day.is_rest_day">
                                                <option value="0">No, día de entrenamiento</option>
                                                <option value="1">Sí, día de descanso</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Exercises Builder (Hidden if Rest Day) -->
                                    <div x-show="day.is_rest_day == '0'" class="border-top border-secondary border-opacity-25 pt-3">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="text-secondary small mb-0">Ejercicios del Día</h6>
                                            <button type="button" @click="addExercise(dayIdx)" class="btn btn-xs btn-outline-secondary border-secondary border-opacity-25 text-white py-1 px-2.5 rounded-2 d-flex align-items-center gap-1" style="font-size: 11px;">
                                                <i data-lucide="plus-circle" style="width: 14px;"></i>
                                                Añadir Ejercicio
                                            </button>
                                        </div>

                                        <div class="d-flex flex-column gap-3">
                                            <template x-for="(ex, exIdx) in day.exercises" :key="exIdx">
                                                <div class="p-3 rounded bg-dark bg-opacity-40 border border-secondary border-opacity-5 d-flex flex-column gap-2">
                                                    <!-- Exercise Header & Select -->
                                                    <div class="d-flex justify-content-between align-items-center gap-3">
                                                        <select :name="'days['+dayIdx+'][exercises]['+exIdx+'][exercise_id]'" class="form-select form-select-sm bg-dark border-secondary border-opacity-25 text-white flex-grow-1" x-model="ex.exercise_id" required>
                                                            <option value="">-- Elige Ejercicio --</option>
                                                            @foreach($exercises as $exercise)
                                                                <option value="{{ $exercise->id }}">{{ $exercise->name }} ({{ $exercise->muscle_group_label }})</option>
                                                            @endforeach
                                                        </select>
                                                        
                                                        <button type="button" @click="removeExercise(dayIdx, exIdx)" class="btn btn-sm btn-link text-danger border-0 p-0" title="Quitar Ejercicio">
                                                            <i data-lucide="x" style="width: 16px;"></i>
                                                        </button>
                                                    </div>

                                                    <!-- Parameters Row -->
                                                    <div class="row g-2">
                                                        <div class="col-3">
                                                            <label class="form-label text-secondary" style="font-size: 10px;">Series</label>
                                                            <input type="number" :name="'days['+dayIdx+'][exercises]['+exIdx+'][sets]'" class="form-control form-control-sm bg-dark border-secondary border-opacity-25 text-white text-center" x-model="ex.sets" required>
                                                        </div>
                                                        <div class="col-3">
                                                            <label class="form-label text-secondary" style="font-size: 10px;">Reps</label>
                                                            <input type="text" :name="'days['+dayIdx+'][exercises]['+exIdx+'][reps]'" class="form-control form-control-sm bg-dark border-secondary border-opacity-25 text-white text-center" placeholder="Ej. 10 o 8-12" x-model="ex.reps" required>
                                                        </div>
                                                        <div class="col-3">
                                                            <label class="form-label text-secondary" style="font-size: 10px;">Peso (kg)</label>
                                                            <input type="number" step="0.5" :name="'days['+dayIdx+'][exercises]['+exIdx+'][weight_kg]'" class="form-control form-control-sm bg-dark border-secondary border-opacity-25 text-white text-center" placeholder="Opcional" x-model="ex.weight_kg">
                                                        </div>
                                                        <div class="col-3">
                                                            <label class="form-label text-secondary" style="font-size: 10px;">Descanso (seg)</label>
                                                            <input type="number" :name="'days['+dayIdx+'][exercises]['+exIdx+'][rest_seconds]'" class="form-control form-control-sm bg-dark border-secondary border-opacity-25 text-white text-center" x-model="ex.rest_seconds" required>
                                                        </div>
                                                    </div>

                                                    <!-- Notes Form -->
                                                    <div class="mb-0">
                                                        <input type="text" :name="'days['+dayIdx+'][exercises]['+exIdx+'][notes]'" class="form-control form-control-sm bg-dark border-secondary border-opacity-25 text-white" placeholder="Observaciones específicas del ejercicio (Ej. Fase concéntrica explosiva)" x-model="ex.notes">
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function routineCreator() {
        return {
            days: [
                {
                    name: 'Lunes - Empuje',
                    focus_area: 'Pecho / Tríceps',
                    is_rest_day: '0',
                    exercises: [
                        { exercise_id: '', sets: 4, reps: '10', weight_kg: '', rest_seconds: 60, notes: '' }
                    ]
                }
            ],
            addDay() {
                this.days.push({
                    name: 'Día Nuevo',
                    focus_area: '',
                    is_rest_day: '0',
                    exercises: [
                        { exercise_id: '', sets: 3, reps: '10', weight_kg: '', rest_seconds: 60, notes: '' }
                    ]
                });
                this.$nextTick(() => lucide.createIcons());
            },
            removeDay(index) {
                if(this.days.length > 1) {
                    this.days.splice(index, 1);
                }
            },
            addExercise(dayIndex) {
                this.days[dayIndex].exercises.push({
                    exercise_id: '', sets: 3, reps: '10', weight_kg: '', rest_seconds: 60, notes: ''
                });
                this.$nextTick(() => lucide.createIcons());
            },
            removeExercise(dayIndex, exIndex) {
                this.days[dayIndex].exercises.splice(exIndex, 1);
            }
        }
    }
</script>
@endsection
