@extends('layouts.app')

@section('content')
<div class="row g-3 g-md-4">
    <!-- Header Page -->
    <div class="col-12">
        <div>
            <h4 class="text-white mb-1 font-heading">Panel de Administración</h4>
            <p class="text-secondary small mb-0">Estadísticas globales de facturación, usuarios y accesos.</p>
        </div>
    </div>

    <!-- Stats row (2x2 grid on mobile, 4 columns on desktop) -->
    <div class="col-6 col-md-3">
        <div class="glass-card p-3 p-md-4 h-100 d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-secondary small" style="font-size: 11px;">Recaudación</span>
                <div class="p-2 rounded bg-success bg-opacity-10 text-success">
                    <i data-lucide="dollar-sign" style="width: 18px; height: 18px;"></i>
                </div>
            </div>
            <div>
                <span class="stat-value text-white d-block" style="font-size: 1.25rem;">${{ number_format($earningsThisMonth, 0, ',', '.') }}</span>
                <small class="text-secondary" style="font-size: 10px;">Este mes</small>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="glass-card p-3 p-md-4 h-100 d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-secondary small" style="font-size: 11px;">Alumnos</span>
                <div class="p-2 rounded bg-primary bg-opacity-10 text-primary">
                    <i data-lucide="users" style="width: 18px; height: 18px;"></i>
                </div>
            </div>
            <div>
                <span class="stat-value text-white d-block" style="font-size: 1.25rem;">{{ $studentsCount }}</span>
                <small class="text-secondary" style="font-size: 10px;">Activos</small>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="glass-card p-3 p-md-4 h-100 d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-secondary small" style="font-size: 11px;">Entrenadores</span>
                <div class="p-2 rounded bg-info bg-opacity-10 text-info">
                    <i data-lucide="shield-check" style="width: 18px; height: 18px;"></i>
                </div>
            </div>
            <div>
                <span class="stat-value text-white d-block" style="font-size: 1.25rem;">{{ $trainersCount }}</span>
                <small class="text-secondary" style="font-size: 10px;">En dojo</small>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="glass-card p-3 p-md-4 h-100 d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-secondary small" style="font-size: 11px;">Asistencias</span>
                <div class="p-2 rounded bg-warning bg-opacity-10 text-warning">
                    <i data-lucide="calendar" style="width: 18px; height: 18px;"></i>
                </div>
            </div>
            <div>
                <span class="stat-value text-white d-block" style="font-size: 1.25rem;">{{ $attendancesTodayCount }}</span>
                <small class="text-secondary" style="font-size: 10px;">Registradas hoy</small>
            </div>
        </div>
    </div>

    <!-- Charts Section (Left) -->
    <div class="col-lg-8">
        <div class="row g-3 g-md-4">
            <!-- Plan distribution -->
            <div class="col-12 col-md-6">
                <div class="glass-card p-3 p-md-4 h-100">
                    <h6 class="text-white mb-3 font-heading">Membresías Activas por Plan</h6>
                    @if(count($planLabels) > 0)
                        <div style="height: 220px; position: relative;" class="d-flex justify-content-center">
                            <canvas id="planChart"></canvas>
                        </div>
                    @else
                        <div class="text-center py-4 text-secondary">
                            <i data-lucide="pie-chart" class="mb-2" style="width: 36px; height: 36px;"></i>
                            <p class="mb-0 small">No hay membresías activas registradas.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Memberships list -->
            <div class="col-12 col-md-6">
                <div class="glass-card p-3 p-md-4 h-100">
                    <h6 class="text-white mb-3 font-heading">Membresías Recientes</h6>
                    <div class="d-flex flex-column gap-2 overflow-auto" style="max-height: 250px;">
                        @forelse($recentMemberships as $mb)
                            <div class="p-2 p-md-3 rounded bg-dark bg-opacity-40 border border-secondary border-opacity-10 d-flex align-items-center justify-content-between">
                                <div class="overflow-hidden me-2">
                                    <h6 class="text-white mb-0 font-heading small text-truncate">{{ $mb->user->name }}</h6>
                                    <small class="text-secondary" style="font-size: 11px;">{{ $mb->plan->name }} — ${{ number_format($mb->price_paid, 0, ',', '.') }}</small>
                                </div>
                                <span class="badge bg-{{ $mb->status_color }} bg-opacity-15 text-{{ $mb->status_color }} flex-shrink-0" style="font-size: 10px;">{{ $mb->status_label }}</span>
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
        <div class="glass-card p-3 p-md-4 h-100">
            <h6 class="text-white mb-3 font-heading">Vencimientos Próximos (10 días)</h6>
            
            <div class="d-flex flex-column gap-2 overflow-auto" style="max-height: 250px;">
                @forelse($expiringMemberships as $mb)
                    <div class="p-2 p-md-3 rounded bg-warning bg-opacity-10 border border-warning border-opacity-20 d-flex align-items-center justify-content-between">
                        <div class="overflow-hidden me-2">
                            <h6 class="text-white mb-0 font-heading small text-truncate">{{ $mb->user->name }}</h6>
                            <small class="text-secondary" style="font-size: 11px;">Expira: {{ $mb->end_date->format('d/m/Y') }}</small>
                        </div>
                        <span class="badge bg-danger flex-shrink-0" style="font-size: 10px;">{{ $mb->days_remaining }} días</span>
                    </div>
                @empty
                    <div class="text-center py-4 my-auto text-secondary">
                        <i data-lucide="check-circle" class="text-success mb-2" style="width: 36px; height: 36px;"></i>
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
                    backgroundColor: ['#DC2626', '#F59E0B', '#22C55E', '#3B82F6'],
                    borderWidth: 2,
                    borderColor: '#12131C'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#F8FAFC',
                            font: { size: 11 }
                        }
                    }
                }
            }
        });
    });
</script>
@endif
@endsection
