@extends('layouts.app')

@section('content')
<div class="row g-4">
    <!-- Header Page -->
    <div class="col-12">
        <div>
            <h3 class="text-white mb-1">Mi Panel de Entrenador</h3>
            <p class="text-secondary mb-0">Monitorea la asistencia, membresías y evolución de tus alumnos.</p>
        </div>
    </div>

    <!-- Counters Row -->
    <div class="col-md-4">
        <div class="glass-card p-4 h-100 d-flex align-items-center justify-content-between">
            <div>
                <span class="text-secondary small d-block mb-1">Alumnos Activos</span>
                <span class="display-4 stat-value text-white">{{ $activeStudentsCount }}</span>
            </div>
            <div class="p-3 rounded bg-primary bg-opacity-10 text-primary">
                <i data-lucide="users" style="width: 32px; height: 32px;"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="glass-card p-4 h-100 d-flex align-items-center justify-content-between">
            <div>
                <span class="text-secondary small d-block mb-1">Asistencias de Hoy</span>
                <span class="display-4 stat-value text-white">{{ $todayAttendances->count() }}</span>
            </div>
            <div class="p-3 rounded bg-success bg-opacity-10 text-success">
                <i data-lucide="calendar-check" style="width: 32px; height: 32px;"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="glass-card p-4 h-100 d-flex align-items-center justify-content-between">
            <div>
                <span class="text-secondary small d-block mb-1">Alumnos Ausentes (+7 días)</span>
                <span class="display-4 stat-value text-white">{{ $inactiveStudents->count() }}</span>
            </div>
            <div class="p-3 rounded bg-warning bg-opacity-10 text-warning">
                <i data-lucide="user-x" style="width: 32px; height: 32px;"></i>
            </div>
        </div>
    </div>

    <!-- Today's Attendance list -->
    <div class="col-lg-6">
        <div class="glass-card p-4 h-100">
            <h5 class="text-white mb-4">Ingresos de Hoy</h5>
            
            <div class="d-flex flex-column gap-3 overflow-auto" style="max-height: 400px;">
                @forelse($todayAttendances as $att)
                    <div class="p-3 rounded bg-dark bg-opacity-40 border border-secondary border-opacity-10 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $att->user->avatar_url }}" alt="Profile" class="rounded-circle" style="width: 44px; height: 44px; object-fit: cover;">
                            <div>
                                <h6 class="text-white mb-0 font-heading">
                                    <a href="{{ route('trainer.students.show', $att->user) }}" class="text-white text-decoration-none hover-primary">
                                        {{ $att->user->name }}
                                    </a>
                                </h6>
                                <small class="text-secondary">{{ $att->user->email }}</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary bg-opacity-25 text-primary">
                                <i data-lucide="clock" class="me-1" style="width: 12px; vertical-align: middle;"></i>
                                {{ $att->checked_in_at->format('H:i') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 my-auto text-secondary">
                        <i data-lucide="calendar-days" class="mb-2" style="width: 48px; height: 48px;"></i>
                        <p class="mb-0 small">Aún no hay asistencias registradas hoy.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Inactive Students Alerts -->
    <div class="col-lg-6">
        <div class="glass-card p-4 h-100">
            <h5 class="text-white mb-4">Faltan Frecuentemente (Alertas)</h5>
            
            <div class="d-flex flex-column gap-3 overflow-auto" style="max-height: 400px;">
                @forelse($inactiveStudents as $student)
                    @php
                        $lastAttendance = $student->attendances->first();
                    @endphp
                    <div class="p-3 rounded bg-warning bg-opacity-10 border border-warning border-opacity-20 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $student->avatar_url }}" alt="Profile" class="rounded-circle" style="width: 44px; height: 44px; object-fit: cover;">
                            <div>
                                <h6 class="text-white mb-0 font-heading">
                                    <a href="{{ route('trainer.students.show', $student) }}" class="text-white text-decoration-none">
                                        {{ $student->name }}
                                    </a>
                                </h6>
                                <small class="text-secondary">
                                    Última vez: 
                                    <strong>
                                        {{ $lastAttendance ? $lastAttendance->checked_in_at->format('d/m/Y') : 'Nunca ha asistido' }}
                                    </strong>
                                </small>
                            </div>
                        </div>
                        <div class="text-end">
                            @if($lastAttendance)
                                <span class="badge bg-danger">{{ now()->diffInDays($lastAttendance->checked_in_at) }} días ausente</span>
                            @else
                                <span class="badge bg-danger">Nunca asistió</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 my-auto text-secondary">
                        <i data-lucide="check-circle" class="text-success mb-2" style="width: 48px; height: 48px;"></i>
                        <p class="mb-0 small">¡Excelente! Todos tus alumnos han asistido esta semana.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Expiring Memberships -->
    <div class="col-12">
        <div class="glass-card p-4">
            <h5 class="text-white mb-4">Membresías Próximas a Vencer (Tus Alumnos)</h5>
            
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle">
                    <thead>
                        <tr class="text-secondary">
                            <th>Alumno</th>
                            <th>Plan Activo</th>
                            <th>Fecha de Término</th>
                            <th>Días Restantes</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expiringMemberships as $mb)
                            <tr class="text-white">
                                <td class="fw-bold">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $mb->user->avatar_url }}" alt="Profile" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                                        <span>{{ $mb->user->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $mb->plan->name }}</td>
                                <td>{{ $mb->end_date->format('d/m/Y') }}</td>
                                <td class="stat-value text-warning">{{ $mb->days_remaining }} días</td>
                                <td>
                                    <span class="badge bg-warning bg-opacity-25 text-warning">
                                        Por vencer
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('trainer.students.show', $mb->user) }}" class="btn btn-sm btn-premium py-1 px-3">
                                        Ver Perfil
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-secondary">No hay membresías asignadas por vencer en los próximos 10 días.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
