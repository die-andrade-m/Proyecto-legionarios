@extends('layouts.app')

@section('content')
<div class="row g-3 g-md-4">
    <!-- Header Page -->
    <div class="col-12">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
            <div>
                <h4 class="text-white mb-0 font-heading">Mi Panel de Entrenador</h4>
                <p class="text-secondary small mb-0">Monitorea la asistencia, membresías y evolución de tus alumnos.</p>
            </div>

            <a href="{{ route('trainer.attendances.index') }}" class="btn btn-premium btn-sm d-flex align-items-center gap-2 py-2 px-3">
                <i data-lucide="calendar-check-2" style="width: 16px; height: 16px;"></i>
                <span>Control de Asistencias</span>
            </a>
        </div>
    </div>

    <!-- Counters Row (2x2 grid on mobile) -->
    <div class="col-6 col-md-4">
        <div class="glass-card p-3 p-md-4 h-100 d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-secondary small" style="font-size: 11px;">Alumnos Activos</span>
                <div class="p-2 rounded bg-primary bg-opacity-10 text-primary">
                    <i data-lucide="users" style="width: 18px; height: 18px;"></i>
                </div>
            </div>
            <div>
                <span class="stat-value text-white d-block" style="font-size: 1.35rem;">{{ $activeStudentsCount }}</span>
                <small class="text-secondary" style="font-size: 10px;">Asignados</small>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4">
        <div class="glass-card p-3 p-md-4 h-100 d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-secondary small" style="font-size: 11px;">Asistencias Hoy</span>
                <div class="p-2 rounded bg-success bg-opacity-10 text-success">
                    <i data-lucide="calendar-check" style="width: 18px; height: 18px;"></i>
                </div>
            </div>
            <div>
                <span class="stat-value text-white d-block" style="font-size: 1.35rem;">{{ $todayAttendances->count() }}</span>
                <small class="text-secondary" style="font-size: 10px;">Registradas</small>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="glass-card p-3 p-md-4 h-100 d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-secondary small" style="font-size: 11px;">Ausentes (+7 días)</span>
                <div class="p-2 rounded bg-warning bg-opacity-10 text-warning">
                    <i data-lucide="user-x" style="width: 18px; height: 18px;"></i>
                </div>
            </div>
            <div>
                <span class="stat-value text-white d-block" style="font-size: 1.35rem;">{{ $inactiveStudents->count() }}</span>
                <small class="text-secondary" style="font-size: 10px;">Requieren seguimiento</small>
            </div>
        </div>
    </div>

    <!-- Today's Attendance list -->
    <div class="col-12 col-lg-6">
        <div class="glass-card p-3 p-md-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="text-white mb-0 font-heading">Ingresos de Hoy</h6>
                <a href="{{ route('trainer.attendances.index') }}" class="btn btn-sm btn-outline-primary border-0 py-0 px-1 small" style="font-size: 11px;">
                    Ver Todo <i data-lucide="arrow-right" style="width: 12px; vertical-align: middle;"></i>
                </a>
            </div>
            
            <div class="d-flex flex-column gap-2 overflow-auto" style="max-height: 350px;">
                @forelse($todayAttendances as $att)
                    <div class="p-2 p-md-3 rounded bg-dark bg-opacity-40 border border-secondary border-opacity-10 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2 overflow-hidden me-2">
                            <img src="{{ $att->user->avatar_url }}" alt="Profile" class="rounded-circle flex-shrink-0" style="width: 36px; height: 36px; object-fit: cover;">
                            <div class="overflow-hidden">
                                <h6 class="text-white mb-0 font-heading small text-truncate">
                                    <a href="{{ route('trainer.students.show', $att->user) }}" class="text-white text-decoration-none">
                                        {{ $att->user->name }}
                                    </a>
                                </h6>
                                <small class="text-secondary" style="font-size: 11px;">{{ $att->user->email }}</small>
                            </div>
                        </div>
                        <div class="text-end flex-shrink-0">
                            <span class="badge bg-primary bg-opacity-25 text-primary" style="font-size: 10px;">
                                <i data-lucide="clock" class="me-1" style="width: 10px; vertical-align: middle;"></i>
                                {{ $att->checked_in_at->format('H:i') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 my-auto text-secondary">
                        <i data-lucide="calendar-days" class="mb-2" style="width: 36px; height: 36px;"></i>
                        <p class="mb-0 small">Aún no hay asistencias registradas hoy.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Inactive Students Alerts -->
    <div class="col-12 col-lg-6">
        <div class="glass-card p-3 p-md-4 h-100">
            <h6 class="text-white mb-3 font-heading">Faltan Frecuentemente (Alertas)</h6>
            
            <div class="d-flex flex-column gap-2 overflow-auto" style="max-height: 350px;">
                @forelse($inactiveStudents as $student)
                    @php
                        $lastAttendance = $student->attendances->first();
                    @endphp
                    <div class="p-2 p-md-3 rounded bg-warning bg-opacity-10 border border-warning border-opacity-20 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2 overflow-hidden me-2">
                            <img src="{{ $student->avatar_url }}" alt="Profile" class="rounded-circle flex-shrink-0" style="width: 36px; height: 36px; object-fit: cover;">
                            <div class="overflow-hidden">
                                <h6 class="text-white mb-0 font-heading small text-truncate">
                                    <a href="{{ route('trainer.students.show', $student) }}" class="text-white text-decoration-none">
                                        {{ $student->name }}
                                    </a>
                                </h6>
                                <small class="text-secondary" style="font-size: 11px;">
                                    Última vez: {{ $lastAttendance ? $lastAttendance->checked_in_at->diffForHumans() : 'Nunca' }}
                                </small>
                            </div>
                        </div>
                        <a href="{{ route('trainer.students.show', $student) }}" class="btn btn-sm btn-outline-warning text-nowrap py-1 px-2 flex-shrink-0" style="font-size: 11px;">
                            Ver Ficha
                        </a>
                    </div>
                @empty
                    <div class="text-center py-4 my-auto text-secondary">
                        <i data-lucide="smile" class="text-success mb-2" style="width: 36px; height: 36px;"></i>
                        <p class="mb-0 small">¡Excelente! Todos tus alumnos están asistiendo con frecuencia.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
