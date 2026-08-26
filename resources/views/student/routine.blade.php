@extends('layouts.app')

@section('content')
<div class="row g-4">
    <!-- Page Header -->
    <div class="col-12">
        <div>
            <h3 class="text-white mb-1">Mi Plan de Entrenamiento</h3>
            <p class="text-secondary mb-0">Consulta tu rutina diaria asignada por tu entrenador.</p>
        </div>
    </div>

    @if($routine)
        <!-- Routine Details Card -->
        <div class="col-lg-4">
            <div class="glass-card p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-{{ $routine->difficulty_color }} bg-opacity-25 text-{{ $routine->difficulty_color }} px-3 py-1 font-heading text-capitalize">
                            {{ $routine->difficulty_label }}
                        </span>
                        <span class="text-secondary small">
                            <i data-lucide="calendar" class="me-1" style="width: 14px; vertical-align: middle;"></i>
                            {{ $routine->duration_weeks ?? '8' }} semanas
                        </span>
                    </div>

                    <h4 class="text-white font-heading mb-2">{{ $routine->name }}</h4>
                    <p class="text-secondary small mb-4">{{ $routine->description ?? 'Sin descripción adicional para esta rutina.' }}</p>

                    <div class="p-3 rounded bg-dark bg-opacity-40 border border-secondary border-opacity-10 mb-3">
                        <span class="text-secondary small d-block mb-1">Objetivo de la rutina:</span>
                        <span class="text-white fw-bold text-capitalize">
                            <i data-lucide="target" class="text-primary me-2" style="width: 18px; vertical-align: text-bottom;"></i>
                            {{ str_replace('_', ' ', $routine->goal ?? 'General') }}
                        </span>
                    </div>
                </div>

                <div class="text-secondary small mt-3 border-top border-secondary border-opacity-25 pt-3">
                    Asignado por: <strong>{{ $routine->trainer->name }}</strong>
                </div>
            </div>
        </div>

        <!-- Routine Days Accordeon / Tabs -->
        <div class="col-lg-8">
            <div class="glass-card p-4">
                <h5 class="text-white mb-4">Días de Entrenamiento</h5>

                <div class="accordion accordion-flush" id="routineAccordion">
                    @foreach($routine->days as $day)
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
                            
                            <div id="collapse-day-{{ $day->id }}" class="accordion-collapse collapse {{ $day->day_number == $todayDayNumber ? 'show' : '' }}" aria-labelledby="heading-day-{{ $day->id }}" data-bs-parent="#routineAccordion">
                                <div class="accordion-body px-0 pt-3 pb-2">
                                    @if($day->is_rest_day)
                                        <div class="text-center py-4 bg-dark bg-opacity-20 rounded">
                                            <span class="fs-1">😴</span>
                                            <h6 class="text-secondary mt-2 mb-0">Día de descanso y recuperación programado.</h6>
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
                                                            <span class="d-flex align-items-center gap-1">
                                                                <i data-lucide="refresh-cw" class="text-primary" style="width: 14px;"></i>
                                                                <strong>Series:</strong> {{ $routineEx->sets }}
                                                            </span>
                                                            <span class="d-flex align-items-center gap-1">
                                                                <i data-lucide="hash" class="text-primary" style="width: 14px;"></i>
                                                                <strong>Repeticiones:</strong> {{ $routineEx->reps }}
                                                            </span>
                                                            @if($routineEx->weight_kg)
                                                                <span class="d-flex align-items-center gap-1">
                                                                    <i data-lucide="dumbbell" class="text-primary" style="width: 14px;"></i>
                                                                    <strong>Peso:</strong> {{ $routineEx->weight_kg }} kg
                                                                </span>
                                                            @endif
                                                            <span class="d-flex align-items-center gap-1">
                                                                <i data-lucide="timer" class="text-primary" style="width: 14px;"></i>
                                                                <strong>Descanso:</strong> {{ $routineEx->rest_label }}
                                                            </span>
                                                        </div>

                                                        @if($routineEx->notes)
                                                            <div class="mt-2 text-warning small">
                                                                <i data-lucide="info" class="me-1" style="width: 12px; vertical-align: middle;"></i>
                                                                {{ $routineEx->notes }}
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- External Video link if available -->
                                                    @if($routineEx->exercise->video_url)
                                                        <div>
                                                            <a href="{{ $routineEx->exercise->video_url }}" target="_blank" class="btn btn-sm btn-outline-secondary border-secondary border-opacity-25 text-white d-flex align-items-center gap-1 px-3 py-2 rounded-3">
                                                                <i data-lucide="play-circle" class="text-danger" style="width: 16px;"></i>
                                                                Ver Video
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            @empty
                                                <p class="text-secondary small">No hay ejercicios asignados para este día de entrenamiento.</p>
                                            @endforelse
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="col-12">
            <div class="glass-card p-5 text-center">
                <i data-lucide="dumbbell" class="text-secondary mb-3" style="width: 64px; height: 64px;"></i>
                <h4 class="text-white">Sin rutina activa</h4>
                <p class="text-secondary mb-0">Tu entrenador todavía no te ha asignado un plan de entrenamiento activo.</p>
            </div>
        </div>
    @endif
</div>
@endsection
