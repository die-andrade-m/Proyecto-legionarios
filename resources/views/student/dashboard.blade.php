@extends('layouts.app')

@section('content')
<div class="row g-4">
    <!-- Welcome Header / User Profile -->
    <div class="col-12">
        <div class="glass-card p-4 d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 border-warning">
            <div class="d-flex flex-column flex-md-row align-items-center gap-3 text-center text-md-start">
                <img src="{{ $user->avatar_url }}" alt="Avatar" class="rounded-circle border border-warning border-3 shadow" style="width: 80px; height: 80px; object-fit: cover;">
                <div>
                    <span class="badge bg-danger bg-opacity-25 text-danger border border-danger px-2 py-1 text-uppercase font-heading mb-1" style="font-size: 10px;">
                        Legionario de Honor
                    </span>
                    <h3 class="mb-1 text-white font-heading">¡A entrenar con honor, {{ explode(' ', $user->name)[0] }}! ⚔️</h3>
                    <p class="mb-0 text-secondary small">
                        <i data-lucide="target" class="me-1 text-warning" style="width: 15px;"></i>
                        <strong>Mi Objetivo:</strong> {{ $user->objective ?? 'Superar mis límites en el tatami' }}
                    </p>
                </div>
            </div>

            <!-- Attendance Button & Quick Links -->
            <div class="d-flex align-items-center gap-2 flex-wrap justify-content-center">
                @if($checkedInToday)
                    <button class="btn btn-premium bg-success bg-opacity-25 text-success border border-success border-opacity-50 py-3 px-4 d-flex align-items-center gap-2" disabled>
                        <i data-lucide="check-circle" style="width: 24px; height: 24px;"></i>
                        <span>¡Asistencia Registrada Hoy!</span>
                    </button>
                @else
                    <form method="POST" action="{{ route('student.attendance.store') }}">
                        @csrf
                        <button type="submit" class="btn btn-premium btn-pulse py-3 px-4 fs-5 d-flex align-items-center gap-2">
                            <i data-lucide="qr-code" style="width: 24px; height: 24px;"></i>
                            <span>Registrar Asistencia</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="col-md-4">
        <!-- Membership Status -->
        <div class="glass-card p-4 h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="text-white mb-0">Membresía</h5>
                    <span class="badge bg-{{ $membership ? $membership->status_color : 'danger' }} bg-opacity-25 text-{{ $membership ? $membership->status_color : 'danger' }} px-3 py-1">
                        {{ $membership ? $membership->status_label : 'Sin Plan' }}
                    </span>
                </div>
                
                @if($membership)
                    <h4 class="text-white font-heading mb-1">{{ $membership->plan->name }}</h4>
                    <p class="text-secondary small mb-3">Vence el {{ $membership->end_date->format('d/m/Y') }}</p>
                    
                    <div class="progress bg-dark bg-opacity-50 mb-2" style="height: 10px; border-radius: 5px;">
                        <div class="progress-bar bg-{{ $membership->status_color }}" role="progressbar" style="width: {{ 100 - $membership->progress_percentage }}%" aria-valuenow="{{ 100 - $membership->progress_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between text-secondary small">
                        <span>{{ $membership->days_remaining }} días restantes</span>
                        <span>{{ $membership->progress_percentage }}% transcurrido</span>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i data-lucide="alert-octagon" class="text-danger mb-2" style="width: 48px; height: 48px;"></i>
                        <p class="text-secondary mb-0">No posees una membresía activa actualmente.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Attendance Stats -->
        <div class="glass-card p-4 h-100 d-flex flex-column justify-content-between">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="text-white mb-0">Asistencia del Mes</h5>
                <i data-lucide="calendar" class="text-primary" style="width: 22px;"></i>
            </div>
            
            <div class="d-flex align-items-baseline gap-2 mb-2">
                <span class="display-5 stat-value text-white">{{ $attendancesThisMonth }}</span>
                <span class="text-secondary">asistencias</span>
            </div>
            
            <p class="text-secondary small mb-3">Estás asistiendo al {{ $monthlyStats['percentage'] }}% de los días de este mes.</p>
            
            <!-- Streak Indicator -->
            <div class="d-flex align-items-center gap-3 p-3 rounded bg-dark bg-opacity-40 border border-secondary border-opacity-10">
                <div class="fs-1">🔥</div>
                <div>
                    <h6 class="text-white mb-0 font-heading">Racha actual: <span class="text-secondary stat-value">{{ $currentStreak }} días</span></h6>
                    <small class="text-secondary">¡Sigue así, no rompas la cadena!</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Evolution / Weight -->
        <div class="glass-card p-4 h-100 d-flex flex-column justify-content-between">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="text-white mb-0">Última Evaluación</h5>
                <i data-lucide="trending-down" class="text-success" style="width: 22px;"></i>
            </div>

            @if($latestStat)
                <div class="row g-2 text-center my-auto">
                    <div class="col-6">
                        <div class="p-3 bg-dark bg-opacity-40 rounded">
                            <span class="text-secondary small d-block">Peso</span>
                            <span class="fs-3 stat-value text-white">{{ $latestStat->weight }} kg</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-dark bg-opacity-40 rounded">
                            <span class="text-secondary small d-block">Grasa Corp.</span>
                            <span class="fs-3 stat-value text-white">{{ $latestStat->body_fat ?? 'N/A' }}%</span>
                        </div>
                    </div>
                    <div class="col-6 mt-2">
                        <div class="p-2 bg-dark bg-opacity-20 rounded">
                            <span class="text-secondary small d-block">Masa Muscular</span>
                            <span class="fw-bold text-white">{{ $latestStat->muscle_mass ?? 'N/A' }} kg</span>
                        </div>
                    </div>
                    <div class="col-6 mt-2">
                        <div class="p-2 bg-dark bg-opacity-20 rounded">
                            <span class="text-secondary small d-block">IMC</span>
                            <span class="badge bg-{{ $latestStat->bmi_color }} text-white fw-bold">{{ $latestStat->bmi }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-3 text-center small text-secondary">
                    @if($weightDiff < 0)
                        <span class="text-success fw-bold">📉 Has bajado {{ abs($weightDiff) }} kg</span> desde tu ingreso.
                    @elseif($weightDiff > 0)
                        <span class="text-primary fw-bold">📈 Has subido {{ $weightDiff }} kg</span> desde tu ingreso.
                    @else
                        Mantienes tu peso inicial estable.
                    @endif
                </div>
            @else
                <div class="text-center py-4 my-auto">
                    <i data-lucide="scale" class="text-secondary mb-2" style="width: 44px; height: 44px;"></i>
                    <p class="text-secondary small mb-0">Aún no registras medidas corporales.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Active Routine View -->
    <div class="col-md-6">
        <div class="glass-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="text-white mb-0">Mi Rutina de Hoy</h5>
                <a href="{{ route('student.routine') }}" class="btn btn-sm btn-outline-primary border-0 d-flex align-items-center gap-1">
                    Ver Completa
                    <i data-lucide="arrow-right" style="width: 14px;"></i>
                </a>
            </div>

            @if($todayRoutineDay)
                <div class="p-3 rounded bg-primary bg-opacity-10 border border-primary border-opacity-20 mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="text-white mb-0 font-heading">{{ $todayRoutineDay->name }}</h6>
                        <span class="badge bg-primary">{{ $todayRoutineDay->focus_area ?? 'General' }}</span>
                    </div>
                    @if($todayRoutineDay->notes)
                        <small class="text-secondary d-block mt-1">{{ $todayRoutineDay->notes }}</small>
                    @endif
                </div>

                <div class="d-flex flex-column gap-2 overflow-auto" style="max-height: 250px;">
                    @forelse($todayRoutineDay->exercises as $routineEx)
                        <div class="p-3 rounded bg-dark bg-opacity-40 border border-secondary border-opacity-10 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-white mb-1 font-heading">{{ $routineEx->exercise->name }}</h6>
                                <div class="d-flex gap-3 text-secondary small">
                                    <span><strong>Series:</strong> {{ $routineEx->sets }}</span>
                                    <span><strong>Reps:</strong> {{ $routineEx->reps }}</span>
                                    @if($routineEx->weight_kg)
                                        <span><strong>Peso:</strong> {{ $routineEx->weight_kg }} kg</span>
                                    @endif
                                </div>
                            </div>
                            <span class="badge bg-dark text-secondary px-2 py-1"><i data-lucide="timer" class="me-1 text-primary" style="width: 12px; vertical-align: middle;"></i>{{ $routineEx->rest_label }}</span>
                        </div>
                    @empty
                        <p class="text-secondary small">Día de descanso.</p>
                    @endforelse
                </div>
            @else
                <div class="text-center py-5 my-auto">
                    <i data-lucide="calendar-days" class="text-secondary mb-2" style="width: 48px; height: 48px;"></i>
                    <p class="text-secondary mb-0">Hoy no tienes asignado entrenamiento o es tu día de descanso programado.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Coach Observations -->
    <div class="col-md-6">
        <div class="glass-card p-4 h-100">
            <h5 class="text-white mb-3">Observaciones del Entrenador</h5>

            @forelse($observations as $obs)
                <div class="p-3 rounded bg-dark bg-opacity-40 border-start border-{{ $obs->category_color }} border-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-{{ $obs->category_color }} bg-opacity-25 text-{{ $obs->category_color }} px-2 py-1">
                            {{ $obs->category_icon }} {{ $obs->category_label }}
                        </span>
                        <small class="text-secondary">{{ $obs->created_at->diffForHumans() }}</small>
                    </div>
                    <p class="text-white small mb-1">{{ $obs->content }}</p>
                    <small class="text-secondary">Firmado por: {{ $obs->trainer->name }}</small>
                </div>
            @empty
                <div class="text-center py-5 my-auto">
                    <i data-lucide="message-square-off" class="text-secondary mb-2" style="width: 48px; height: 48px;"></i>
                    <p class="text-secondary mb-0">No tienes nuevas observaciones del entrenador.</p>
                </div>
            @endif
    </div>

    <!-- 10 Pilares Banner Card -->
    <div class="col-12">
        <div class="glass-card p-4 border-warning bg-black bg-opacity-40">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3 text-center text-md-start">
                    <img src="{{ asset('images/logo-mma-gold.jpg') }}" alt="Legionarios" class="rounded-circle border border-warning" style="width: 55px; height: 55px; object-fit: cover;">
                    <div>
                        <h5 class="text-warning font-heading mb-1">Filosofía Legionaria: Los 10 Pilares 📜</h5>
                        <p class="text-secondary small mb-0">"Un Dojo, Un Espíritu. Caemos juntos, nos levantamos unidos."</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-warning font-heading fw-bold px-3 py-2" data-bs-toggle="modal" data-bs-target="#pilaresStudentModal">
                        <i data-lucide="scroll" class="me-1" style="width: 14px;"></i> Ver 10 Pilares
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-light font-heading fw-bold px-3 py-2" data-bs-toggle="modal" data-bs-target="#checklistStudentModal">
                        <i data-lucide="check-square" class="me-1" style="width: 14px;"></i> Checklist 1ª Semana
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for 10 Pilares -->
    <div class="modal fade" id="pilaresStudentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content glass-card border-warning">
                <div class="modal-header border-bottom border-warning border-opacity-25">
                    <h5 class="modal-title text-warning font-heading fw-bold">Los 10 Pilares de la Familia Legionarios</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-3">
                    <img src="{{ asset('images/10-pilares.jpg') }}" alt="10 Pilares Artwork" class="img-fluid rounded border border-secondary shadow-lg">
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Checklist -->
    <div class="modal fade" id="checklistStudentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content glass-card border-warning">
                <div class="modal-header border-bottom border-warning border-opacity-25">
                    <h5 class="modal-title text-warning font-heading fw-bold">Checklist para tu Primera Semana</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-3">
                    <img src="{{ asset('images/checklist-semana.jpg') }}" alt="Checklist Artwork" class="img-fluid rounded border border-secondary shadow-lg">
                </div>
            </div>
        </div>
    </div>

    <!-- Achievements / Gamification Badges -->
    <div class="col-12">
        <div class="glass-card p-4">
            <h5 class="text-white mb-4">Mis Logros y Medallas 🏆</h5>
            
            <div class="row g-4 row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 text-center justify-content-center">
                @forelse($achievements as $ach)
                    <div class="col">
                        <div class="badge-achievement" style="background-color: {{ $ach->badge_color }}22; border: 2px solid {{ $ach->badge_color }};">
                            {{ $ach->icon }}
                        </div>
                        <h6 class="text-white mb-1 font-heading">{{ $ach->name }}</h6>
                        <small class="text-secondary d-block" style="font-size: 11px; line-height: 1.2;">{{ $ach->description }}</small>
                        <span class="badge bg-warning bg-opacity-15 text-warning mt-1" style="font-size: 9px;">+{{ $ach->points }} PTS</span>
                    </div>
                @empty
                    <div class="col-12 py-3 text-center">
                        <p class="text-secondary mb-0">¡Registra tu asistencia y entrena para desbloquear tus primeras insignias!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
