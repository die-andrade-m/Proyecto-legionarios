@extends('layouts.app')

@section('content')
<div class="row g-4">
    <!-- Page Header -->
    <div class="col-12">
        <div>
            <h3 class="text-white mb-1">Mis Alumnos Asignados</h3>
            <p class="text-secondary mb-0">Administra el entrenamiento, medidas corporales y progreso de tus alumnos.</p>
        </div>
    </div>

    <!-- Students Grid -->
    @forelse($students as $student)
        <div class="col-md-6 col-lg-4">
            <div class="glass-card p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <!-- Student Identity -->
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <img src="{{ $student->avatar_url }}" alt="Profile" class="rounded-circle border border-secondary border-opacity-25" style="width: 56px; height: 56px; object-fit: cover;">
                        <div class="overflow-hidden">
                            <h5 class="text-white mb-0 text-truncate font-heading">{{ $student->name }}</h5>
                            <span class="badge bg-{{ $student->activeMembership ? 'success' : 'danger' }} bg-opacity-15 text-{{ $student->activeMembership ? 'success' : 'danger' }} small" style="font-size: 10px;">
                                {{ $student->activeMembership ? 'Membresía Activa' : 'Membresía Inactiva' }}
                            </span>
                        </div>
                    </div>

                    <!-- Objective & Stats -->
                    <p class="text-secondary small mb-3 text-truncate-2" style="height: 40px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ $student->objective ?? 'Sin objetivo registrado.' }}
                    </p>

                    <div class="row g-2 text-center mb-3">
                        <div class="col-6">
                            <div class="p-2 bg-dark bg-opacity-30 rounded">
                                <span class="text-secondary small d-block" style="font-size: 11px;">Último Peso</span>
                                <span class="text-white fw-bold stat-value">{{ $student->latestBodyStat ? $student->latestBodyStat->weight . ' kg' : 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-dark bg-opacity-30 rounded">
                                <span class="text-secondary small d-block" style="font-size: 11px;">Última Grasa</span>
                                <span class="text-white fw-bold stat-value">{{ $student->latestBodyStat && $student->latestBodyStat->body_fat ? $student->latestBodyStat->body_fat . '%' : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="border-top border-secondary border-opacity-25 pt-3 mt-2">
                    <a href="{{ route('trainer.students.show', $student) }}" class="btn btn-premium w-100 py-2 d-flex align-items-center justify-content-center gap-2">
                        <i data-lucide="eye" style="width: 18px;"></i>
                        <span>Ver Ficha Completa</span>
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 py-5">
            <div class="glass-card p-5 text-center">
                <i data-lucide="users-round" class="text-secondary mb-3" style="width: 64px; height: 64px;"></i>
                <h4 class="text-white">Sin alumnos asignados</h4>
                <p class="text-secondary mb-0">Actualmente no tienes alumnos vinculados a tu perfil.</p>
            </div>
        </div>
    @endforelse
</div>
@endsection
