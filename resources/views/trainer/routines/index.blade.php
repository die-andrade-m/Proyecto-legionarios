@extends('layouts.app')

@section('content')
<div class="row g-4">
    <!-- Page Header -->
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="text-white mb-1">Gestión de Rutinas y Plantillas</h3>
                <p class="text-secondary mb-0">Crea, duplica y asigna planes de entrenamiento a tus alumnos.</p>
            </div>
            
            <a href="{{ route('trainer.routines.create') }}" class="btn btn-premium d-flex align-items-center gap-2">
                <i data-lucide="plus-circle" style="width: 18px;"></i>
                <span>Crear Rutina</span>
            </a>
        </div>
    </div>

    <!-- Routines Grid -->
    @forelse($routines as $routine)
        <div class="col-md-6 col-lg-4">
            <div class="glass-card p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <!-- Routine Title & Badges -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-{{ $routine->difficulty_color }} bg-opacity-25 text-{{ $routine->difficulty_color }} px-2.5 py-1 text-capitalize">
                            {{ $routine->difficulty_label }}
                        </span>
                        
                        @if($routine->is_template)
                            <span class="badge bg-info bg-opacity-15 text-info">
                                <i data-lucide="copy" class="me-1" style="width: 10px; vertical-align: middle;"></i>Plantilla
                            </span>
                        @endif
                    </div>

                    <h5 class="text-white mb-2 font-heading">{{ $routine->name }}</h5>
                    <p class="text-secondary small mb-3 text-truncate-3" style="height: 60px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                        {{ $routine->description ?? 'Sin descripción para esta rutina.' }}
                    </p>

                    <!-- Goal / Weeks -->
                    <div class="d-flex gap-2 mb-3">
                        <span class="badge bg-dark text-secondary text-capitalize px-2 py-1.5" style="font-size: 11px;">
                            🎯 {{ str_replace('_', ' ', $routine->goal) }}
                        </span>
                        <span class="badge bg-dark text-secondary px-2 py-1.5" style="font-size: 11px;">
                            📅 {{ $routine->duration_weeks ?? '8' }} Semanas
                        </span>
                    </div>
                </div>

                <!-- Footer Stats & Actions -->
                <div class="border-top border-secondary border-opacity-25 pt-3 mt-2">
                    <div class="d-flex justify-content-between align-items-center mb-3 text-secondary small">
                        <span>Asignada a:</span>
                        <span class="text-white fw-bold">{{ $routine->user_routines_count }} alumnos</span>
                    </div>

                    <a href="{{ route('trainer.routines.show', $routine) }}" class="btn btn-outline-primary border-secondary border-opacity-25 text-white w-100 py-2 d-flex align-items-center justify-content-center gap-2">
                        <i data-lucide="info" style="width: 16px;"></i>
                        <span>Ver Estructura</span>
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 py-5">
            <div class="glass-card p-5 text-center">
                <i data-lucide="dumbbell" class="text-secondary mb-3" style="width: 64px; height: 64px;"></i>
                <h4 class="text-white">Sin rutinas registradas</h4>
                <p class="text-secondary mb-0">Crea tu primera plantilla o rutina personalizada.</p>
            </div>
        </div>
    @endforelse
</div>
@endsection
