@extends('layouts.app')

@section('content')
<div class="row g-3 g-md-4">
    <!-- Welcome Header / User Profile -->
    <div class="col-12">
        <div class="glass-card p-3 p-md-4 d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 border-warning">
            <div class="d-flex flex-column flex-md-row align-items-center gap-3 text-center text-md-start">
                <img src="{{ $user->avatar_url }}" alt="Avatar" class="rounded-circle border border-warning border-2 shadow" style="width: 64px; height: 64px; object-fit: cover;">
                <div>
                    <span class="badge bg-danger bg-opacity-25 text-danger border border-danger px-2 py-1 text-uppercase font-heading mb-1" style="font-size: 9px;">
                        Legionario de Honor
                    </span>
                    <h4 class="mb-1 text-white font-heading">¡A entrenar con honor, {{ explode(' ', $user->name)[0] }}! ⚔️</h4>
                    <p class="mb-0 text-secondary small" style="font-size: 12px;">
                        <i data-lucide="target" class="me-1 text-warning" style="width: 14px; height: 14px;"></i>
                        <strong>Mi Objetivo:</strong> {{ $user->objective ?? 'Superar mis límites en el tatami' }}
                    </p>
                </div>
            </div>

            <!-- Attendance Button & Quick Links -->
            <div class="d-flex align-items-center gap-2 flex-wrap justify-content-center w-100 w-md-auto">
                @if($checkedInToday)
                    <button class="btn btn-premium bg-success bg-opacity-25 text-success border border-success border-opacity-50 py-2 px-3 d-flex align-items-center justify-content-center gap-2 w-100 w-md-auto" disabled style="font-size: 14px;">
                        <i data-lucide="check-circle" style="width: 18px; height: 18px;"></i>
                        <span>¡Asistencia Registrada Hoy!</span>
                    </button>
                @else
                    <form method="POST" action="{{ route('student.attendance.store') }}" class="w-100 w-md-auto">
                        @csrf
                        <button type="submit" class="btn btn-premium btn-pulse py-2 px-4 fs-6 d-flex align-items-center justify-content-center gap-2 w-100">
                            <i data-lucide="qr-code" style="width: 20px; height: 20px;"></i>
                            <span>Registrar Asistencia</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Stats Row (Responsive grid) -->
    <div class="col-12 col-md-4">
        <!-- Membership Status -->
        <div class="glass-card p-3 p-md-4 h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="text-white mb-0 font-heading">Membresía</h6>
                    <span class="badge bg-{{ $membership ? $membership->status_color : 'danger' }} bg-opacity-25 text-{{ $membership ? $membership->status_color : 'danger' }} px-2 py-1" style="font-size: 10px;">
                        {{ $membership ? $membership->status_label : 'Sin Plan' }}
                    </span>
                </div>
                
                @if($membership)
                    <h5 class="text-white font-heading mb-1 fs-6">{{ $membership->plan->name }}</h5>
                    <p class="text-secondary small mb-2" style="font-size: 11px;">Vence el {{ $membership->end_date->format('d/m/Y') }}</p>
                    
                    <div class="progress bg-dark bg-opacity-50 mb-1" style="height: 8px; border-radius: 4px;">
                        <div class="progress-bar bg-{{ $membership->status_color }}" role="progressbar" style="width: {{ 100 - $membership->progress_percentage }}%" aria-valuenow="{{ 100 - $membership->progress_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between text-secondary" style="font-size: 10px;">
                        <span>{{ $membership->days_remaining }} días restantes</span>
                        <span>{{ $membership->progress_percentage }}% transcurrido</span>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i data-lucide="alert-octagon" class="text-danger mb-1" style="width: 32px; height: 32px;"></i>
                        <p class="text-secondary small mb-0">No posees una membresía activa actualmente.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4">
        <!-- Attendance Stats -->
        <div class="glass-card p-3 p-md-4 h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-secondary small" style="font-size: 11px;">Asistencias Mes</span>
                    <i data-lucide="calendar" class="text-primary" style="width: 18px; height: 18px;"></i>
                </div>
                
                <div class="d-flex align-items-baseline gap-1 mb-1">
                    <span class="stat-value text-white" style="font-size: 1.5rem;">{{ $attendancesThisMonth }}</span>
                    <span class="text-secondary small" style="font-size: 11px;">días</span>
                </div>
                
                <div class="p-2 rounded bg-dark bg-opacity-40 border border-secondary border-opacity-10 d-flex align-items-center gap-2 mt-2">
                    <span style="font-size: 18px;">🔥</span>
                    <div class="overflow-hidden">
                        <small class="text-white font-heading d-block lh-1 text-truncate" style="font-size: 11px;">Racha: {{ $currentStreak }} días</small>
                        <small class="text-secondary" style="font-size: 9px;">¡No pares!</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4">
        <!-- Evolution / Weight -->
        <div class="glass-card p-3 p-md-4 h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-secondary small" style="font-size: 11px;">Última Medición</span>
                    <i data-lucide="trending-down" class="text-success" style="width: 18px; height: 18px;"></i>
                </div>

                @if($latestStat)
                    <div class="d-flex align-items-baseline gap-1 mb-1">
                        <span class="stat-value text-white" style="font-size: 1.5rem;">{{ $latestStat->weight }}</span>
                        <span class="text-secondary small" style="font-size: 11px;">kg</span>
                    </div>

                    <div class="d-flex justify-content-between text-secondary mt-2" style="font-size: 10px;">
                        <span>Grasa: <strong class="text-white">{{ $latestStat->body_fat ?? '-' }}%</strong></span>
                        <span>IMC: <span class="badge bg-{{ $latestStat->bmi_color }}" style="font-size: 9px;">{{ $latestStat->bmi }}</span></span>
                    </div>
                @else
                    <div class="text-center py-2 my-auto">
                        <i data-lucide="scale" class="text-secondary mb-1" style="width: 28px; height: 28px;"></i>
                        <p class="text-secondary small mb-0" style="font-size: 10px;">Sin medidas registradas.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Active Routine View -->
    <div class="col-12 col-md-6">
        <div class="glass-card p-3 p-md-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="text-white mb-0 font-heading">Mi Rutina de Hoy</h6>
                <a href="{{ route('student.routine') }}" class="btn btn-sm btn-outline-primary border-0 d-flex align-items-center gap-1 p-1" style="font-size: 12px;">
                    Ver Completa
                    <i data-lucide="arrow-right" style="width: 12px; height: 12px;"></i>
                </a>
            </div>

            @if($todayRoutineDay)
                <div class="p-2 p-md-3 rounded bg-primary bg-opacity-10 border border-primary border-opacity-20 mb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="text-white mb-0 font-heading small">{{ $todayRoutineDay->name }}</h6>
                        <span class="badge bg-primary" style="font-size: 10px;">{{ $todayRoutineDay->focus_area ?? 'General' }}</span>
                    </div>
                    @if($todayRoutineDay->notes)
                        <small class="text-secondary d-block mt-1" style="font-size: 11px;">{{ $todayRoutineDay->notes }}</small>
                    @endif
                </div>

                <div class="d-flex flex-column gap-2 overflow-auto" style="max-height: 220px;">
                    @forelse($todayRoutineDay->exercises as $routineEx)
                        <div class="p-2 p-md-3 rounded bg-dark bg-opacity-40 border border-secondary border-opacity-10 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-white mb-0 font-heading small">{{ $routineEx->exercise->name }}</h6>
                                <div class="d-flex gap-2 text-secondary" style="font-size: 11px;">
                                    <span><strong>Series:</strong> {{ $routineEx->sets }}</span>
                                    <span><strong>Reps:</strong> {{ $routineEx->reps }}</span>
                                    @if($routineEx->weight_kg)
                                        <span><strong>Peso:</strong> {{ $routineEx->weight_kg }}kg</span>
                                    @endif
                                </div>
                            </div>
                            <span class="badge bg-dark text-secondary px-2 py-1" style="font-size: 10px;">
                                <i data-lucide="timer" class="me-1 text-primary" style="width: 10px; vertical-align: middle;"></i>{{ $routineEx->rest_label }}
                            </span>
                        </div>
                    @empty
                        <p class="text-secondary small mb-0">Día de descanso.</p>
                    @endforelse
                </div>
            @else
                <div class="text-center py-4 my-auto">
                    <i data-lucide="calendar-days" class="text-secondary mb-1" style="width: 32px; height: 32px;"></i>
                    <p class="text-secondary small mb-0">Hoy no tienes asignado entrenamiento o es tu día de descanso.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Coach Observations -->
    <div class="col-12 col-md-6">
        <div class="glass-card p-3 p-md-4 h-100">
            <h6 class="text-white mb-2 font-heading">Observaciones del Entrenador</h6>

            <div class="d-flex flex-column gap-2 overflow-auto" style="max-height: 250px;">
                @forelse($observations as $obs)
                    <div class="p-2 p-md-3 rounded bg-dark bg-opacity-40 border-start border-{{ $obs->category_color }} border-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge bg-{{ $obs->category_color }} bg-opacity-25 text-{{ $obs->category_color }}" style="font-size: 9px;">
                                {{ $obs->category_icon }} {{ $obs->category_label }}
                            </span>
                            <small class="text-secondary" style="font-size: 10px;">{{ $obs->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="text-white small mb-1" style="font-size: 12px;">{{ $obs->content }}</p>
                        <small class="text-secondary" style="font-size: 10px;">Prof. {{ $obs->trainer->name }}</small>
                    </div>
                @empty
                    <div class="text-center py-4 my-auto">
                        <i data-lucide="message-square-off" class="text-secondary mb-1" style="width: 32px; height: 32px;"></i>
                        <p class="text-secondary small mb-0">No tienes nuevas observaciones del entrenador.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 10 Pilares Banner Card -->
    <div class="col-12">
        <div class="glass-card p-3 p-md-4 border-warning bg-black bg-opacity-40">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 text-center text-md-start">
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset('images/logo-mma-gold.jpg') }}" alt="Legionarios" class="rounded-circle border border-warning flex-shrink-0" style="width: 44px; height: 44px; object-fit: cover;">
                    <div>
                        <h6 class="text-warning font-heading mb-0">Filosofía Legionaria: Los 10 Pilares 📜</h6>
                        <p class="text-secondary small mb-0" style="font-size: 11px;">"Un Dojo, Un Espíritu. Caemos juntos, nos levantamos unidos."</p>
                    </div>
                </div>
                <div class="d-flex gap-2 w-100 w-md-auto justify-content-center">
                    <button type="button" class="btn btn-sm btn-outline-warning font-heading fw-bold px-3 py-2 flex-grow-1 flex-md-grow-0" data-bs-toggle="modal" data-bs-target="#pilaresStudentModal" style="font-size: 11px;">
                        <i data-lucide="scroll" class="me-1" style="width: 14px;"></i> Ver 10 Pilares
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-light font-heading fw-bold px-3 py-2 flex-grow-1 flex-md-grow-0" data-bs-toggle="modal" data-bs-target="#checklistStudentModal" style="font-size: 11px;">
                        <i data-lucide="check-square" class="me-1" style="width: 14px;"></i> Checklist
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for 10 Pilares -->
    <div class="modal fade" id="pilaresStudentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content glass-card border-warning">
                <div class="modal-header border-bottom border-warning border-opacity-25 py-2 px-3">
                    <h6 class="modal-title text-warning font-heading fw-bold mb-0">Los 10 Pilares de la Familia Legionarios</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-2 p-md-3">
                    <img src="{{ asset('images/10-pilares.jpg') }}" alt="10 Pilares Artwork" class="img-fluid rounded border border-secondary shadow-lg" style="max-height: 80vh; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Checklist -->
    <div class="modal fade" id="checklistStudentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content glass-card border-warning">
                <div class="modal-header border-bottom border-warning border-opacity-25 py-2 px-3">
                    <h6 class="modal-title text-warning font-heading fw-bold mb-0">Checklist para tu Primera Semana</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-2 p-md-3">
                    <img src="{{ asset('images/checklist-semana.jpg') }}" alt="Checklist Artwork" class="img-fluid rounded border border-secondary shadow-lg" style="max-height: 80vh; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>

    <!-- Achievements / Gamification Badges -->
    <div class="col-12">
        <div class="glass-card p-3 p-md-4">
            <h6 class="text-white mb-3 font-heading">Mis Logros y Medallas 🏆</h6>
            
            <div class="row g-3 row-cols-3 row-cols-md-4 row-cols-lg-6 text-center justify-content-center">
                @forelse($achievements as $ach)
                    <div class="col">
                        <div class="badge-achievement mx-auto mb-1" style="width: 50px; height: 50px; font-size: 22px; background-color: {{ $ach->badge_color }}22; border: 2px solid {{ $ach->badge_color }};">
                            {{ $ach->icon }}
                        </div>
                        <h6 class="text-white mb-0 font-heading" style="font-size: 11px;">{{ $ach->name }}</h6>
                        <small class="text-secondary d-none d-sm-block" style="font-size: 10px; line-height: 1.1;">{{ $ach->description }}</small>
                        <span class="badge bg-warning bg-opacity-15 text-warning mt-1" style="font-size: 9px;">+{{ $ach->points }} PTS</span>
                    </div>
                @empty
                    <div class="col-12 py-3 text-center">
                        <p class="text-secondary small mb-0">¡Registra tu asistencia y entrena para desbloquear tus primeras insignias!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
