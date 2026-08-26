@extends('layouts.app')

@section('content')
<div class="row g-4">
    <!-- Header Page -->
    <div class="col-12">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <a href="{{ route('trainer.routines.index') }}" class="text-secondary text-decoration-none small d-flex align-items-center gap-1 mb-2">
                    <i data-lucide="arrow-left" style="width: 14px;"></i> Volver a listado
                </a>
                <h3 class="text-white mb-0 font-heading">{{ $routine->name }}</h3>
            </div>

            <!-- Actions Header -->
            <div class="d-flex gap-2">
                <form action="{{ route('trainer.routines.duplicate', $routine) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary border-secondary border-opacity-25 text-white d-flex align-items-center gap-2">
                        <i data-lucide="copy" style="width: 16px;"></i>
                        <span>Duplicar Rutina</span>
                    </button>
                </form>

                <button type="button" class="btn btn-premium d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#assignRoutineModal">
                    <i data-lucide="user-plus" style="width: 16px;"></i>
                    <span>Asignar a Alumno</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Details Card (Left) -->
    <div class="col-lg-4">
        <div class="glass-card p-4">
            <h5 class="text-white mb-3">Especificaciones</h5>
            
            <div class="d-flex flex-column gap-3">
                <div>
                    <span class="text-secondary small d-block mb-1">Dificultad</span>
                    <span class="badge bg-{{ $routine->difficulty_color }} bg-opacity-25 text-{{ $routine->difficulty_color }} px-3 py-1 text-capitalize font-heading">
                        {{ $routine->difficulty_label }}
                    </span>
                </div>

                <div>
                    <span class="text-secondary small d-block mb-1">Objetivo</span>
                    <span class="text-white fw-bold text-capitalize font-heading">
                        🎯 {{ str_replace('_', ' ', $routine->goal) }}
                    </span>
                </div>

                <div>
                    <span class="text-secondary small d-block mb-1">Duración sugerida</span>
                    <span class="text-white fw-bold font-heading">
                        📅 {{ $routine->duration_weeks ?? '8' }} Semanas
                    </span>
                </div>

                @if($routine->is_template)
                    <div>
                        <span class="badge bg-info bg-opacity-15 text-info font-heading">
                            <i data-lucide="copy-check" class="me-1" style="width: 12px; vertical-align: middle;"></i>Esta rutina es una plantilla pública
                        </span>
                    </div>
                @endif

                <hr class="border-secondary border-opacity-25 my-2">

                <div>
                    <span class="text-secondary small d-block mb-1">Descripción</span>
                    <p class="text-white small mb-0">{{ $routine->description ?? 'Sin descripción adicional para esta rutina.' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Routine Days Structure (Right) -->
    <div class="col-lg-8">
        <div class="glass-card p-4">
            <h5 class="text-white mb-4">Estructura de Días y Ejercicios</h5>

            <div class="accordion accordion-flush" id="routineShowAccordion">
                @forelse($routine->days as $day)
                    <div class="accordion-item bg-transparent border-bottom border-secondary border-opacity-25 py-2">
                        <h2 class="accordion-header" id="heading-day-{{ $day->id }}">
                            <button class="accordion-button bg-transparent text-white border-0 px-0 d-flex justify-content-between align-items-center collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-day-{{ $day->id }}" aria-expanded="false" aria-controls="collapse-day-{{ $day->id }}">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge bg-primary bg-opacity-25 text-primary fs-6 py-2 px-3 font-heading" style="min-width: 100px;">
                                        {{ $day->day_name }}
                                    </span>
                                    <div>
                                        <span class="fw-bold fs-5 font-heading text-white">{{ $day->name }}</span>
                                        @if($day->focus_area)
                                            <span class="badge bg-secondary bg-opacity-25 text-secondary ms-2 small">{{ $day->focus_area }}</span>
                                        @endif
                                    </div>
                                </div>
                            </button>
                        </h2>
                        
                        <div id="collapse-day-{{ $day->id }}" class="accordion-collapse collapse" aria-labelledby="heading-day-{{ $day->id }}" data-bs-parent="#routineShowAccordion">
                            <div class="accordion-body px-0 pt-3 pb-2">
                                @if($day->is_rest_day)
                                    <div class="text-center py-4 bg-dark bg-opacity-20 rounded">
                                        <span class="fs-1">😴</span>
                                        <h6 class="text-secondary mt-2 mb-0">Día de descanso programado.</h6>
                                    </div>
                                @else
                                    @if($day->notes)
                                        <div class="p-3 bg-dark bg-opacity-20 border-start border-primary border-2 rounded mb-3 text-secondary small">
                                            <strong>Notas del día:</strong> {{ $day->notes }}
                                        </div>
                                    @endif

                                    <div class="d-flex flex-column gap-3">
                                        @forelse($day->exercises as $routineEx)
                                            <div class="p-3 rounded bg-dark bg-opacity-40 border border-secondary border-opacity-10 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                                                <div>
                                                    <h6 class="text-white mb-2 font-heading fs-5">{{ $routineEx->exercise->name }}</h6>
                                                    
                                                    <div class="d-flex flex-wrap gap-3 text-secondary small">
                                                        <span><strong>Series:</strong> {{ $routineEx->sets }}</span>
                                                        <span><strong>Reps:</strong> {{ $routineEx->reps }}</span>
                                                        @if($routineEx->weight_kg)
                                                            <span><strong>Peso:</strong> {{ $routineEx->weight_kg }} kg</span>
                                                        @endif
                                                        <span><strong>Descanso:</strong> {{ $routineEx->rest_label }}</span>
                                                    </div>

                                                    @if($routineEx->notes)
                                                        <div class="mt-2 text-warning small">
                                                            <i data-lucide="info" class="me-1" style="width: 12px; vertical-align: middle;"></i>
                                                            {{ $routineEx->notes }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-secondary small">No hay ejercicios asignados.</p>
                                        @endforelse
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-secondary py-3 mb-0">Esta rutina no tiene días configurados.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Modal para asignar rutina a un alumno -->
<div class="modal fade" id="assignRoutineModal" tabindex="-1" aria-labelledby="assignRoutineModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0 text-white" style="background-color: var(--surface-light);">
            <div class="modal-header border-bottom border-secondary border-opacity-25 p-4">
                <h5 class="modal-title font-heading" id="assignRoutineModalLabel">Asignar Rutina a Alumno</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('trainer.routines.assign', $routine) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="student_id" class="form-label text-secondary small">Seleccionar Alumno</label>
                        <select class="form-select bg-dark border-secondary border-opacity-25 text-white" id="student_id" name="student_id" required>
                            <option value="">-- Elige un alumno --</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="duration_weeks" class="form-label text-secondary small">Duración (Semanas)</label>
                        <input type="number" class="form-control bg-dark border-secondary border-opacity-25 text-white" id="duration_weeks" name="duration_weeks" value="{{ $routine->duration_weeks ?? '8' }}" required>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-25 p-4">
                    <button type="button" class="btn btn-outline-secondary border-0 text-white" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-premium px-4">Asignar Rutina</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
