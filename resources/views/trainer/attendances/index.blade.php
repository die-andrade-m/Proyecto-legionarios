@extends('layouts.app')

@section('content')
<div class="row g-3 g-md-4">
    <!-- Header Page -->
    <div class="col-12">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
            <div>
                <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-50 px-2 py-1 text-uppercase font-heading mb-1" style="font-size: 10px;">
                    Control Oficial del Dojo
                </span>
                <h3 class="text-white mb-0 font-heading">Control de Asistencias 🥋</h3>
                <p class="text-secondary small mb-0">Lleva el registro transparente de asistencia con fecha, hora exacta y calendario mensual.</p>
            </div>

            <!-- Quick Action Button (Custom Date/Time) -->
            <button type="button" class="btn btn-premium btn-sm d-flex align-items-center gap-2 py-2 px-3" data-bs-toggle="modal" data-bs-target="#customAttendanceModal">
                <i data-lucide="plus-circle" style="width: 16px; height: 16px;"></i>
                <span>Registro Personalizado</span>
            </button>
        </div>
    </div>

    <!-- Quick Stats Cards (Today) -->
    <div class="col-6 col-md-4">
        <div class="glass-card p-3 p-md-4 h-100 d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-secondary small" style="font-size: 11px;">Presentes Hoy</span>
                <div class="p-2 rounded bg-success bg-opacity-10 text-success">
                    <i data-lucide="user-check" style="width: 18px; height: 18px;"></i>
                </div>
            </div>
            <div>
                <span class="stat-value text-white d-block" style="font-size: 1.5rem;">{{ $todayAttendances->count() }}</span>
                <small class="text-secondary" style="font-size: 10px;">{{ now()->translatedFormat('l, d \d\e F') }}</small>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4">
        <div class="glass-card p-3 p-md-4 h-100 d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-secondary small" style="font-size: 11px;">Total Alumnos</span>
                <div class="p-2 rounded bg-primary bg-opacity-10 text-primary">
                    <i data-lucide="users" style="width: 18px; height: 18px;"></i>
                </div>
            </div>
            <div>
                <span class="stat-value text-white d-block" style="font-size: 1.5rem;">{{ $allStudents->count() }}</span>
                <small class="text-secondary" style="font-size: 10px;">Activos en lista</small>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="glass-card p-3 p-md-4 h-100 d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-secondary small" style="font-size: 11px;">Asistencia Hoy</span>
                <div class="p-2 rounded bg-warning bg-opacity-10 text-warning">
                    <i data-lucide="percent" style="width: 18px; height: 18px;"></i>
                </div>
            </div>
            <div>
                @php
                    $todayPercent = $allStudents->count() > 0 ? round(($todayAttendances->count() / $allStudents->count()) * 100) : 0;
                @endphp
                <span class="stat-value text-white d-block" style="font-size: 1.5rem;">{{ $todayPercent }}%</span>
                <small class="text-secondary" style="font-size: 10px;">Del total del dojo</small>
            </div>
        </div>
    </div>

    <!-- Section 1: Roster for Fast 1-Click Check-in Today -->
    <div class="col-12">
        <div class="glass-card p-3 p-md-4">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
                <div>
                    <h5 class="text-white mb-0 font-heading">Lista de Alumnos (Registro Rápido de Hoy)</h5>
                    <small class="text-secondary">Haz clic en "Marcar Presente" para registrar el ingreso con la hora exacta actual.</small>
                </div>
            </div>

            <div class="row g-2 g-md-3">
                @forelse($allStudents as $st)
                    @php
                        $hasAttendedToday = in_array($st->id, $todayAttendedStudentIds);
                        $todayRecord = $todayAttendances->firstWhere('user_id', $st->id);
                    @endphp
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="p-3 rounded bg-dark bg-opacity-40 border {{ $hasAttendedToday ? 'border-success border-opacity-40' : 'border-secondary border-opacity-15' }} d-flex align-items-center justify-content-between gap-2">
                            <div class="d-flex align-items-center gap-2 overflow-hidden">
                                <img src="{{ $st->avatar_url }}" alt="Profile" class="rounded-circle border {{ $hasAttendedToday ? 'border-success' : 'border-secondary border-opacity-25' }}" style="width: 38px; height: 38px; object-fit: cover;">
                                <div class="overflow-hidden">
                                    <h6 class="text-white mb-0 font-heading small text-truncate">{{ $st->name }}</h6>
                                    @if($hasAttendedToday)
                                        <span class="badge bg-success bg-opacity-20 text-success" style="font-size: 10px;">
                                            <i data-lucide="check" class="me-1" style="width: 10px; vertical-align: middle;"></i>
                                            Presente {{ $todayRecord->checked_in_at->format('H:i:s') }}
                                        </span>
                                    @else
                                        <small class="text-secondary" style="font-size: 11px;">Pendiente de asistencia</small>
                                    @endif
                                </div>
                            </div>

                            <div class="flex-shrink-0">
                                @if($hasAttendedToday)
                                    <button class="btn btn-sm btn-outline-success border-0 text-success p-2" disabled title="Ya registrado hoy">
                                        <i data-lucide="check-circle-2" style="width: 22px; height: 22px;"></i>
                                    </button>
                                @else
                                    <form action="{{ route('trainer.attendances.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="student_id" value="{{ $st->id }}">
                                        <button type="submit" class="btn btn-sm btn-premium py-1 px-2 d-flex align-items-center gap-1" style="font-size: 12px;">
                                            <i data-lucide="check" style="width: 14px; height: 14px;"></i>
                                            <span>Presente</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 py-4 text-center text-secondary">
                        <p class="mb-0">No hay alumnos registrados en el sistema.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Section 2: Detailed Monthly Calendar by Student -->
    <div class="col-12 col-lg-7">
        <div class="glass-card p-3 p-md-4 h-100">
            <!-- Student Selector and Month Navigator -->
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
                <div>
                    <h5 class="text-white mb-0 font-heading">Calendario Mensual de Asistencias 📅</h5>
                    <small class="text-secondary">Consulta los días y horas en que vino el alumno.</small>
                </div>

                <!-- Student Filter Dropdown -->
                <form action="{{ route('trainer.attendances.index') }}" method="GET" class="d-flex align-items-center gap-2 w-100 w-sm-auto">
                    <input type="hidden" name="year" value="{{ $year }}">
                    <input type="hidden" name="month" value="{{ $month }}">
                    <select name="student_id" class="form-select form-select-sm bg-dark border-secondary border-opacity-25 text-white" onchange="this.form.submit()" style="font-size: 13px;">
                        @foreach($allStudents as $st)
                            <option value="{{ $st->id }}" {{ $selectedStudent && $selectedStudent->id === $st->id ? 'selected' : '' }}>
                                {{ $st->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            @if($selectedStudent && $calendarData)
                <!-- Calendar Month Navigation Bar -->
                <div class="d-flex justify-content-between align-items-center p-2 rounded bg-dark bg-opacity-50 border border-secondary border-opacity-20 mb-3">
                    <a href="{{ route('trainer.attendances.index', ['student_id' => $selectedStudent->id, 'year' => $calendarData['prevYear'], 'month' => $calendarData['prevMonth']]) }}" class="btn btn-sm btn-outline-secondary border-0 text-white">
                        <i data-lucide="chevron-left" style="width: 18px;"></i>
                    </a>

                    <div class="text-center">
                        <h6 class="text-warning font-heading mb-0 fs-6">{{ $calendarData['monthName'] }}</h6>
                        <small class="text-secondary" style="font-size: 11px;">
                            {{ $selectedStudent->name }} — {{ $calendarData['count'] }} asistencias ({{ $calendarData['percentage'] }}%)
                        </small>
                    </div>

                    <a href="{{ route('trainer.attendances.index', ['student_id' => $selectedStudent->id, 'year' => $calendarData['nextYear'], 'month' => $calendarData['nextMonth']]) }}" class="btn btn-sm btn-outline-secondary border-0 text-white">
                        <i data-lucide="chevron-right" style="width: 18px;"></i>
                    </a>
                </div>

                <!-- Calendar Grid Style -->
                <style>
                    .calendar-table {
                        width: 100%;
                        table-layout: fixed;
                    }
                    .calendar-th {
                        text-align: center;
                        font-size: 11px;
                        font-weight: 700;
                        color: var(--gold);
                        padding: 6px 2px;
                        text-transform: uppercase;
                    }
                    .calendar-cell {
                        height: 58px;
                        padding: 3px;
                        vertical-align: top;
                    }
                    .calendar-day-card {
                        height: 100%;
                        border-radius: 8px;
                        padding: 4px;
                        background: rgba(18, 19, 28, 0.4);
                        border: 1px solid rgba(255, 255, 255, 0.05);
                        display: flex;
                        flex-direction: column;
                        justify-content: space-between;
                        transition: all 0.2s ease;
                        font-size: 11px;
                    }
                    .calendar-day-card.attended {
                        background: rgba(34, 197, 94, 0.15);
                        border-color: rgba(34, 197, 94, 0.5);
                        box-shadow: 0 2px 8px rgba(34, 197, 94, 0.2);
                    }
                    .calendar-day-card.today {
                        border-color: var(--primary);
                        box-shadow: 0 0 0 1px var(--primary);
                    }
                    .calendar-day-number {
                        font-weight: 700;
                        line-height: 1;
                    }
                </style>

                <div class="table-responsive">
                    <table class="calendar-table">
                        <thead>
                            <tr>
                                <th class="calendar-th">Lun</th>
                                <th class="calendar-th">Mar</th>
                                <th class="calendar-th">Mié</th>
                                <th class="calendar-th">Jue</th>
                                <th class="calendar-th">Vie</th>
                                <th class="calendar-th">Sáb</th>
                                <th class="calendar-th">Dom</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($calendarData['weeks'] as $week)
                                <tr>
                                    @foreach($week as $dayData)
                                        <td class="calendar-cell">
                                            @if($dayData)
                                                <div class="calendar-day-card {{ $dayData['has_attended'] ? 'attended' : '' }} {{ $dayData['is_today'] ? 'today' : '' }}" title="{{ $dayData['has_attended'] ? 'Asistió a las ' . $dayData['time'] : 'Sin asistencia' }}">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="calendar-day-number {{ $dayData['has_attended'] ? 'text-success' : ($dayData['is_today'] ? 'text-primary' : 'text-secondary') }}">
                                                            {{ $dayData['day'] }}
                                                        </span>
                                                        @if($dayData['has_attended'])
                                                            <i data-lucide="check" class="text-success" style="width: 12px; height: 12px;"></i>
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

                <!-- Calendar Legend -->
                <div class="d-flex flex-wrap align-items-center justify-content-center gap-3 mt-3 pt-2 border-top border-secondary border-opacity-15 small text-secondary" style="font-size: 11px;">
                    <div class="d-flex align-items-center gap-1">
                        <span class="badge bg-success bg-opacity-25 border border-success p-1 rounded">✓</span>
                        <span>Asistió al dojo</span>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <span class="badge bg-primary p-1 rounded">Hoy</span>
                        <span>Día actual</span>
                    </div>
                </div>
            @else
                <div class="text-center py-5 text-secondary">
                    <p class="mb-0">Selecciona un alumno para visualizar su calendario.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Section 3: Today's Registered Attendances list (Right side) -->
    <div class="col-12 col-lg-5">
        <div class="glass-card p-3 p-md-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="text-white mb-0 font-heading">Ingresos de Hoy ({{ $todayAttendances->count() }})</h5>
                <span class="badge bg-primary bg-opacity-25 text-primary" style="font-size: 10px;">En Vivo</span>
            </div>

            <div class="d-flex flex-column gap-2 overflow-auto" style="max-height: 400px;">
                @forelse($todayAttendances as $att)
                    <div class="p-2 p-md-3 rounded bg-dark bg-opacity-40 border border-secondary border-opacity-10 d-flex align-items-center justify-content-between gap-2">
                        <div class="d-flex align-items-center gap-2 overflow-hidden">
                            <img src="{{ $att->user->avatar_url }}" alt="Profile" class="rounded-circle flex-shrink-0" style="width: 36px; height: 36px; object-fit: cover;">
                            <div class="overflow-hidden">
                                <h6 class="text-white mb-0 font-heading small text-truncate">{{ $att->user->name }}</h6>
                                <small class="text-secondary" style="font-size: 10px;">
                                    Reg: {{ $att->recorder?->name ?? 'Entrenador' }}
                                </small>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                            <span class="badge bg-success bg-opacity-20 text-success font-monospace" style="font-size: 11px;">
                                <i data-lucide="clock" class="me-1" style="width: 11px; vertical-align: middle;"></i>
                                {{ $att->checked_in_at->format('H:i:s') }}
                            </span>

                            <form action="{{ route('trainer.attendances.destroy', $att) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas anular esta asistencia?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger border-0 p-1 text-danger" title="Anular Asistencia">
                                    <i data-lucide="trash-2" style="width: 15px; height: 15px;"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-secondary">
                        <i data-lucide="calendar-x" class="mb-2" style="width: 36px; height: 36px;"></i>
                        <p class="mb-0 small">Aún no hay asistencias marcadas hoy.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Section 4: Full Searchable Historical Audit Log -->
    <div class="col-12">
        <div class="glass-card p-3 p-md-4">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
                <div>
                    <h5 class="text-white mb-0 font-heading">Historial Completo de Asistencias (Auditoría)</h5>
                    <small class="text-secondary">Registro detallado con fecha, hora exacta (segundos) y responsable.</small>
                </div>
            </div>

            <!-- Filters -->
            <form action="{{ route('trainer.attendances.index') }}" method="GET" class="row g-2 mb-3">
                <div class="col-12 col-md-5">
                    <select name="filter_student_id" class="form-select form-select-sm bg-dark border-secondary border-opacity-25 text-white">
                        <option value="">-- Todos los Alumnos --</option>
                        @foreach($allStudents as $st)
                            <option value="{{ $st->id }}" {{ request('filter_student_id') == $st->id ? 'selected' : '' }}>
                                {{ $st->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-8 col-md-4">
                    <input type="date" name="filter_date" value="{{ request('filter_date') }}" class="form-control form-control-sm bg-dark border-secondary border-opacity-25 text-white">
                </div>
                <div class="col-4 col-md-3 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-premium w-100 py-1">Filtrar</button>
                    @if(request('filter_student_id') || request('filter_date'))
                        <a href="{{ route('trainer.attendances.index') }}" class="btn btn-sm btn-outline-secondary py-1 px-2" title="Limpiar Filtros">
                            <i data-lucide="x" style="width: 14px;"></i>
                        </a>
                    @endif
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle">
                    <thead>
                        <tr class="text-secondary small" style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                            <th>Alumno</th>
                            <th>Fecha</th>
                            <th>Hora Exacta</th>
                            <th>Registrado Por</th>
                            <th>Notas</th>
                            <th class="text-end">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendanceLogs as $log)
                            <tr class="small">
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $log->user->avatar_url }}" alt="Profile" class="rounded-circle" style="width: 28px; height: 28px; object-fit: cover;">
                                        <span class="text-white fw-bold">{{ $log->user->name }}</span>
                                    </div>
                                </td>
                                <td class="text-white">{{ $log->checked_in_at->translatedFormat('d \d\e F, Y') }}</td>
                                <td>
                                    <span class="badge bg-success bg-opacity-20 text-success font-monospace px-2 py-1">
                                        <i data-lucide="clock" class="me-1" style="width: 11px; vertical-align: middle;"></i>
                                        {{ $log->checked_in_at->format('H:i:s') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-15 text-primary" style="font-size: 10px;">
                                        {{ $log->recorder?->name ?? 'Entrenador' }}
                                    </span>
                                </td>
                                <td class="text-secondary small">{{ $log->notes ?? '-' }}</td>
                                <td class="text-end">
                                    <form action="{{ route('trainer.attendances.destroy', $log) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este registro de asistencia?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0 p-1 text-danger" title="Eliminar registro">
                                            <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-secondary">No se encontraron registros de asistencia con los filtros seleccionados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($attendanceLogs->hasPages())
                <div class="mt-3 d-flex justify-content-center">
                    {{ $attendanceLogs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal for Custom Date/Time Attendance Registration -->
<div class="modal fade" id="customAttendanceModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0 text-white" style="background-color: var(--surface-light); box-shadow: 0 10px 40px rgba(0,0,0,0.7);">
            <div class="modal-header border-bottom border-secondary border-opacity-25 p-3 p-md-4">
                <h5 class="modal-title font-heading">Registrar Asistencia Personalizada</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('trainer.attendances.store') }}" method="POST">
                @csrf
                <div class="modal-body p-3 p-md-4">
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Alumno*</label>
                        <select name="student_id" class="form-select bg-dark border-secondary border-opacity-25 text-white" required>
                            <option value="">-- Selecciona Alumno --</option>
                            @foreach($allStudents as $st)
                                <option value="{{ $st->id }}">{{ $st->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label text-secondary small">Fecha*</label>
                            <input type="date" name="date" value="{{ now()->toDateString() }}" class="form-control bg-dark border-secondary border-opacity-25 text-white" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-secondary small">Hora (HH:MM)*</label>
                            <input type="time" name="time" value="{{ now()->format('H:i') }}" class="form-control bg-dark border-secondary border-opacity-25 text-white" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small">Notas u Observación (Opcional)</label>
                        <input type="text" name="notes" placeholder="Ej. Entrenamiento nocturno BJJ" class="form-control bg-dark border-secondary border-opacity-25 text-white">
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-25 p-3 p-md-4">
                    <button type="button" class="btn btn-outline-secondary border-0 text-white" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-premium px-4">Guardar Asistencia</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
