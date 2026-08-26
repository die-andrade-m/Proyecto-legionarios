@extends('layouts.app')

@section('content')
<div class="row g-4">
    <!-- Header Page -->
    <div class="col-12">
        <div>
            <h3 class="text-white mb-1">Panel de Administración</h3>
            <p class="text-secondary mb-0">Estadísticas globales de facturación, usuarios y accesos.</p>
        </div>
    </div>

    <!-- Stats row -->
    <div class="col-md-3">
        <div class="glass-card p-4 h-100 d-flex align-items-center justify-content-between">
            <div>
                <span class="text-secondary small d-block mb-1">Recaudación (Mes)</span>
                <span class="fs-3 stat-value text-white">${{ number_format($earningsThisMonth, 0, ',', '.') }}</span>
            </div>
            <div class="p-3 rounded bg-success bg-opacity-10 text-success">
                <i data-lucide="dollar-sign" style="width: 28px; height: 28px;"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="glass-card p-4 h-100 d-flex align-items-center justify-content-between">
            <div>
                <span class="text-secondary small d-block mb-1">Alumnos Activos</span>
                <span class="fs-3 stat-value text-white">{{ $studentsCount }}</span>
            </div>
            <div class="p-3 rounded bg-primary bg-opacity-10 text-primary">
                <i data-lucide="users" style="width: 28px; height: 28px;"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="glass-card p-4 h-100 d-flex align-items-center justify-content-between">
            <div>
                <span class="text-secondary small d-block mb-1">Entrenadores</span>
                <span class="fs-3 stat-value text-white">{{ $trainersCount }}</span>
            </div>
            <div class="p-3 rounded bg-info bg-opacity-10 text-info">
                <i data-lucide="shield-check" style="width: 28px; height: 28px;"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="glass-card p-4 h-100 d-flex align-items-center justify-content-between">
            <div>
                <span class="text-secondary small d-block mb-1">Asistencias Hoy</span>
                <span class="fs-3 stat-value text-white">{{ $attendancesTodayCount }}</span>
            </div>
            <div class="p-3 rounded bg-warning bg-opacity-10 text-warning">
                <i data-lucide="calendar" style="width: 28px; height: 28px;"></i>
            </div>
        </div>
    </div>

    <!-- Charts Section (Left) -->
    <div class="col-lg-8">
        <div class="row g-4">
            <!-- Plan distribution -->
            <div class="col-md-6">
                <div class="glass-card p-4 h-100">
                    <h5 class="text-white mb-4">Membresías Activas por Plan</h5>
                    @if(count($planLabels) > 0)
                        <div style="height: 250px; position: relative;" class="d-flex justify-content-center">
                            <canvas id="planChart"></canvas>
                        </div>
                    @else
                        <div class="text-center py-5 text-secondary">
                            <i data-lucide="pie-chart" class="mb-2" style="width: 44px; height: 44px;"></i>
                            <p class="mb-0 small">No hay membresías activas registradas.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Memberships list -->
            <div class="col-md-6">
                <div class="glass-card p-4 h-100">
                    <h5 class="text-white mb-4">Membresías Recientes</h5>
                    <div class="d-flex flex-column gap-3">
                        @forelse($recentMemberships as $mb)
                            <div class="p-3 rounded bg-dark bg-opacity-40 border border-secondary border-opacity-10 d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-white mb-0 font-heading">{{ $mb->user->name }}</h6>
                                    <small class="text-secondary">{{ $mb->plan->name }} — ${{ number_format($mb->price_paid, 0, ',', '.') }}</small>
                                </div>
                                <span class="badge bg-{{ $mb->status_color }} bg-opacity-10 text-{{ $mb->status_color }}">{{ $mb->status_label }}</span>
                            </div>
                        @empty
                            <p class="text-secondary text-center py-4 small mb-0">Sin transacciones recientes.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Expirations alerts (Right) -->
    <div class="col-lg-4">
        <div class="glass-card p-4 h-100">
            <h5 class="text-white mb-4">Vencimientos Próximos (10 días)</h5>
            
            <div class="d-flex flex-column gap-3 overflow-auto" style="max-height: 280px;">
                @forelse($expiringMemberships as $mb)
                    <div class="p-3 rounded bg-warning bg-opacity-10 border border-warning border-opacity-20 d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white mb-0 font-heading">{{ $mb->user->name }}</h6>
                            <small class="text-secondary">Expira el: {{ $mb->end_date->format('d/m/Y') }}</small>
                        </div>
                        <span class="badge bg-danger">{{ $mb->days_remaining }} días</span>
                    </div>
                @empty
                    <div class="text-center py-5 my-auto text-secondary">
                        <i data-lucide="check-circle" class="text-success mb-2" style="width: 44px; height: 44px;"></i>
                        <p class="mb-0 small">Sin membresías por vencer pronto.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@if(count($planLabels) > 0)
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const labels = @json($planLabels);
        const counts = @json($planCounts);

        const ctxPlan = document.getElementById('planChart').getContext('2d');
        new Chart(ctxPlan, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: counts,
                    backgroundColor: ['#6C3CF7', '#FF6B35', '#22C55E', '#3B82F6'],
                    borderWidth: 2,
                    borderColor: '#12121F'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#F8FAFC'
                        }
                    }
                }
            }
        });
    });
</script>
@endif
@endsection
