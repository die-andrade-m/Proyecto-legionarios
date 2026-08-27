@extends('layouts.app')

@section('content')
<div class="row g-3 g-md-4">
    <!-- Page Header -->
    <div class="col-12">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
            <div>
                <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-50 px-2 py-1 text-uppercase font-heading mb-1" style="font-size: 10px;">
                    Transparencia y Registro de Honor
                </span>
                <h3 class="text-white mb-0 font-heading">Mi Calendario de Asistencias 📅</h3>
                <p class="text-secondary small mb-0">Revisa todas tus asistencias registradas por los entrenadores del dojo con fecha y hora exacta.</p>
            </div>

            <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary btn-sm text-white border-secondary border-opacity-25 py-2 px-3">
                <i data-lucide="arrow-left" class="me-1" style="width: 14px; vertical-align: middle;"></i>
                Volver a Mi Panel
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards (Month Summary) -->
    <div class="col-6 col-md-4">
        <div class="glass-card p-3 p-md-4 h-100 d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-secondary small" style="font-size: 11px;">Asistencias del Mes</span>
                <div class="p-2 rounded bg-primary bg-opacity-10 text-primary">
                    <i data-lucide="calendar-check" style="width: 18px; height: 18px;"></i>
                </div>
            </div>
            <div>
                <span class="stat-value text-white d-block" style="font-size: 1.5rem;">{{ $calendarData['count'] }}</span>
                <small class="text-secondary" style="font-size: 10px;">En {{ $calendarData['monthName'] }}</small>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4">
        <div class="glass-card p-3 p-md-4 h-100 d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-secondary small" style="font-size: 11px;">Racha Actual</span>
                <div class="p-2 rounded bg-warning bg-opacity-10 text-warning">
                    <span style="font-size: 16px;">🔥</span>
                </div>
            </div>
            <div>
                <span class="stat-value text-white d-block" style="font-size: 1.5rem;">{{ $calendarData['currentStreak'] }} días</span>
                <small class="text-secondary" style="font-size: 10px;">¡No rompas la disciplina!</small>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="glass-card p-3 p-md-4 h-100 d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-secondary small" style="font-size: 11px;">Constancia Mensual</span>
                <div class="p-2 rounded bg-success bg-opacity-10 text-success">
                    <i data-lucide="activity" style="width: 18px; height: 18px;"></i>
                </div>
            </div>
            <div>
                <span class="stat-value text-white d-block" style="font-size: 1.5rem;">{{ $calendarData['percentage'] }}%</span>
                <div class="progress bg-dark bg-opacity-50 mt-1" style="height: 6px; border-radius: 3px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $calendarData['percentage'] }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive Visual Calendar -->
    <div class="col-12 col-lg-8">
        <div class="glass-card p-3 p-md-4 h-100">
            <!-- Month Navigator Header -->
            <div class="d-flex justify-content-between align-items-center p-2 p-md-3 rounded bg-dark bg-opacity-50 border border-secondary border-opacity-20 mb-3">
                <a href="{{ route('student.attendances.index', ['year' => $calendarData['prevYear'], 'month' => $calendarData['prevMonth']]) }}" class="btn btn-sm btn-outline-secondary border-0 text-white d-flex align-items-center gap-1">
                    <i data-lucide="chevron-left" style="width: 18px;"></i>
                    <span class="d-none d-sm-inline">Mes Anterior</span>
                </a>

                <div class="text-center">
                    <h5 class="text-warning font-heading mb-0 fs-5">{{ $calendarData['monthName'] }}</h5>
                    <small class="text-secondary" style="font-size: 11px;">
                        {{ $calendarData['count'] }} entrenamientos registrados
                    </small>
                </div>

                <a href="{{ route('student.attendances.index', ['year' => $calendarData['nextYear'], 'month' => $calendarData['nextMonth']]) }}" class="btn btn-sm btn-outline-secondary border-0 text-white d-flex align-items-center gap-1">
                    <span class="d-none d-sm-inline">Mes Siguiente</span>
                    <i data-lucide="chevron-right" style="width: 18px;"></i>
                </a>
            </div>

            <!-- Calendar Table -->
            <style>
                .student-cal-table {
                    width: 100%;
                    table-layout: fixed;
                }
                .student-cal-th {
                    text-align: center;
                    font-size: 11px;
                    font-weight: 700;
                    color: var(--gold);
                    padding: 8px 2px;
                    text-transform: uppercase;
                }
                .student-cal-cell {
                    height: 64px;
                    padding: 3px;
                    vertical-align: top;
                }
                .student-day-box {
                    height: 100%;
                    border-radius: 8px;
                    padding: 4px 6px;
                    background: rgba(18, 19, 28, 0.4);
                    border: 1px solid rgba(255, 255, 255, 0.05);
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                    transition: all 0.2s ease;
                }
                .student-day-box.attended {
                    background: rgba(34, 197, 94, 0.15);
                    border-color: rgba(34, 197, 94, 0.6);
                    box-shadow: 0 2px 10px rgba(34, 197, 94, 0.25);
                }
                .student-day-box.today {
                    border-color: var(--primary);
                    box-shadow: 0 0 0 1px var(--primary);
                }
            </style>

            <div class="table-responsive">
                <table class="student-cal-table">
                    <thead>
                        <tr>
                            <th class="student-cal-th">Lun</th>
                            <th class="student-cal-th">Mar</th>
                            <th class="student-cal-th">Mié</th>
                            <th class="student-cal-th">Jue</th>
                            <th class="student-cal-th">Vie</th>
                            <th class="student-cal-th">Sáb</th>
                            <th class="student-cal-th">Dom</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($calendarData['weeks'] as $week)
                            <tr>
                                @foreach($week as $dayData)
                                    <td class="student-cal-cell">
                                        @if($dayData)
                                            <div class="student-day-box {{ $dayData['has_attended'] ? 'attended' : '' }} {{ $dayData['is_today'] ? 'today' : '' }}">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold {{ $dayData['has_attended'] ? 'text-success' : ($dayData['is_today'] ? 'text-primary' : 'text-secondary') }}" style="font-size: 11px;">
                                                        {{ $dayData['day'] }}
                                                    </span>
                                                    @if($dayData['has_attended'])
                                                        <i data-lucide="check-circle" class="text-success" style="width: 12px; height: 12px;"></i>
                                                    @endif
                                                </div>

                                                @if($dayData['has_attended'])
                                                    <div class="text-center">
                                                        <span class="badge bg-success text-white px-1 py-0" style="font-size: 9px;">
                                                            {{ $dayData['short_time'] }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Legend & Notice -->
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-3 pt-3 border-top border-secondary border-opacity-15 small text-secondary">
                <div class="d-flex align-items-center gap-3" style="font-size: 11px;">
                    <div class="d-flex align-items-center gap-1">
                        <span class="badge bg-success bg-opacity-25 border border-success p-1 rounded">✓</span>
                        <span>Asististe al entrenamiento</span>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <span class="badge bg-primary p-1 rounded">Hoy</span>
                        <span>Día actual</span>
                    </div>
                </div>

                <small style="font-size: 10px;">* Registrado oficialmente por los profesores del dojo</small>
            </div>
        </div>
    </div>

    <!-- Monthly Attendance Logs list (Right side) -->
    <div class="col-12 col-lg-4">
        <div class="glass-card p-3 p-md-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="text-white mb-0 font-heading">Entrenamientos del Mes</h5>
                <span class="badge bg-success bg-opacity-20 text-success" style="font-size: 11px;">{{ $calendarData['count'] }} días</span>
            </div>

            <div class="d-flex flex-column gap-2 overflow-auto" style="max-height: 420px;">
                @forelse($calendarData['attendances'] as $att)
                    <div class="p-2 p-md-3 rounded bg-dark bg-opacity-40 border border-success border-opacity-25 d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white mb-0 font-heading small">{{ $att->checked_in_at->translatedFormat('l, d \d\e F') }}</h6>
                            <small class="text-secondary" style="font-size: 10px;">
                                Prof: {{ $att->recorder?->name ?? 'Entrenador' }}
                            </small>
                        </div>
                        <span class="badge bg-success bg-opacity-25 text-success font-monospace" style="font-size: 11px;">
                            <i data-lucide="clock" class="me-1" style="width: 10px; vertical-align: middle;"></i>
                            {{ $att->checked_in_at->format('H:i:s') }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-5 text-secondary my-auto">
                        <i data-lucide="calendar-x" class="mb-2" style="width: 40px; height: 40px;"></i>
                        <p class="mb-0 small">No registras asistencias en este mes.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Historical Log Table -->
    <div class="col-12">
        <div class="glass-card p-3 p-md-4">
            <h5 class="text-white mb-3 font-heading">Historial Completo de Asistencias 📜</h5>

            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle">
                    <thead>
                        <tr class="text-secondary small" style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                            <th>Fecha</th>
                            <th>Hora Exacta de Ingreso</th>
                            <th>Registrado Por</th>
                            <th>Estado</th>
                            <th>Notas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendancesList as $log)
                            <tr class="small">
                                <td class="text-white fw-bold">{{ $log->checked_in_at->translatedFormat('d \d\e F, Y (l)') }}</td>
                                <td>
                                    <span class="badge bg-success bg-opacity-20 text-success font-monospace px-2 py-1" style="font-size: 12px;">
                                        <i data-lucide="clock" class="me-1" style="width: 11px; vertical-align: middle;"></i>
                                        {{ $log->checked_in_at->format('H:i:s') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-15 text-primary" style="font-size: 11px;">
                                        <i data-lucide="shield-check" class="me-1" style="width: 11px; vertical-align: middle;"></i>
                                        {{ $log->recorder?->name ?? 'Entrenador del Dojo' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success text-white" style="font-size: 10px;">Presente</span>
                                </td>
                                <td class="text-secondary">{{ $log->notes ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-secondary">Aún no tienes asistencias registradas en tu historial.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($attendancesList->hasPages())
                <div class="mt-3 d-flex justify-content-center">
                    {{ $attendancesList->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
