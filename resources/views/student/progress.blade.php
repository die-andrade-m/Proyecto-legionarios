@extends('layouts.app')

@section('content')
<div class="row g-4">
    <!-- Header Page -->
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="text-white mb-1">Mi Progreso Físico</h3>
                <p class="text-secondary mb-0">Controla tu evolución corporal, estadísticas e imágenes de progreso.</p>
            </div>
            
            <button type="button" class="btn btn-premium d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#uploadPhotoModal">
                <i data-lucide="camera" style="width: 18px;"></i>
                <span>Subir Foto</span>
            </button>
        </div>
    </div>

    <!-- Pestañas de control -->
    <div class="col-12">
        <ul class="nav nav-pills gap-2" id="progressTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active glass-card text-white py-2 px-4 border-0" id="charts-tab" data-bs-toggle="tab" data-bs-target="#charts-pane" type="button" role="tab">
                    <i data-lucide="line-chart" class="me-2" style="width: 18px; vertical-align: text-bottom;"></i>Gráficos
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link glass-card text-white py-2 px-4 border-0" id="history-tab" data-bs-toggle="tab" data-bs-target="#history-pane" type="button" role="tab">
                    <i data-lucide="table" class="me-2" style="width: 18px; vertical-align: text-bottom;"></i>Historial
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link glass-card text-white py-2 px-4 border-0" id="photos-tab" data-bs-toggle="tab" data-bs-target="#photos-pane" type="button" role="tab">
                    <i data-lucide="images" class="me-2" style="width: 18px; vertical-align: text-bottom;"></i>Fotos de Progreso
                </button>
            </li>
        </ul>
    </div>

    <!-- Contenido de las pestañas -->
    <div class="col-12">
        <div class="tab-content" id="progressTabContent">
            <!-- 1. GRÁFICOS PANE -->
            <div class="tab-pane fade show active" id="charts-pane" role="tabpanel" tabindex="0">
                @if(count($chartData['labels']) > 0)
                    <div class="row g-4">
                        <!-- Weight Chart -->
                        <div class="col-lg-8">
                            <div class="glass-card p-4">
                                <h5 class="text-white mb-4">Evolución de Peso (kg)</h5>
                                <div style="height: 350px;">
                                    <canvas id="weightChart"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Body Fat & Muscle mass -->
                        <div class="col-lg-4">
                            <div class="glass-card p-4 h-100">
                                <h5 class="text-white mb-4">Composición Corporal (%)</h5>
                                <div style="height: 300px;">
                                    <canvas id="compositionChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="glass-card p-5 text-center">
                        <i data-lucide="scale-3d" class="text-secondary mb-3" style="width: 64px; height: 64px;"></i>
                        <h4 class="text-white">Sin datos suficientes</h4>
                        <p class="text-secondary mb-0">Necesitas al menos una medición corporal registrada por tu entrenador para visualizar los gráficos.</p>
                    </div>
                @endif
            </div>

            <!-- 2. HISTORIAL PANE -->
            <div class="tab-pane fade" id="history-pane" role="tabpanel" tabindex="0">
                <div class="glass-card p-4 overflow-hidden">
                    <h5 class="text-white mb-4">Historial de Medidas Corporales</h5>
                    
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0 align-middle">
                            <thead>
                                <tr class="text-secondary">
                                    <th>Fecha</th>
                                    <th>Peso (kg)</th>
                                    <th>Altura (cm)</th>
                                    <th>IMC</th>
                                    <th>Grasa (%)</th>
                                    <th>Músculo (kg)</th>
                                    <th>Cintura (cm)</th>
                                    <th>Cadera (cm)</th>
                                    <th>Brazo (cm)</th>
                                    <th>Pierna (cm)</th>
                                    <th>Pecho (cm)</th>
                                    <th>Notas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bodyStats as $stat)
                                    <tr class="text-white">
                                        <td class="fw-bold">{{ $stat->measured_at->format('d/m/Y') }}</td>
                                        <td class="stat-value">{{ $stat->weight }}</td>
                                        <td class="stat-value">{{ $stat->height }}</td>
                                        <td>
                                            <span class="badge bg-{{ $stat->bmi_color }}">{{ $stat->bmi }}</span>
                                        </td>
                                        <td class="stat-value">{{ $stat->body_fat ?? '-' }}</td>
                                        <td class="stat-value">{{ $stat->muscle_mass ?? '-' }}</td>
                                        <td class="stat-value">{{ $stat->waist ?? '-' }}</td>
                                        <td class="stat-value">{{ $stat->hip ?? '-' }}</td>
                                        <td class="stat-value">{{ $stat->arm ?? '-' }}</td>
                                        <td class="stat-value">{{ $stat->leg ?? '-' }}</td>
                                        <td class="stat-value">{{ $stat->chest ?? '-' }}</td>
                                        <td class="small text-secondary text-truncate" style="max-width: 150px;" title="{{ $stat->notes }}">
                                            {{ $stat->notes ?? '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center py-4 text-secondary">Aún no se han registrado evaluaciones físicas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 3. FOTOS PANE -->
            <div class="tab-pane fade" id="photos-pane" role="tabpanel" tabindex="0">
                <div class="glass-card p-4">
                    <h5 class="text-white mb-4">Galería de Fotos de Progreso</h5>
                    
                    @if($photos->isEmpty())
                        <div class="text-center py-5">
                            <i data-lucide="image-plus" class="text-secondary mb-3" style="width: 56px; height: 56px;"></i>
                            <p class="text-secondary mb-0">No has subido fotos de progreso todavía. ¡Sube tu primera foto para empezar a comparar!</p>
                        </div>
                    @else
                        <div class="row g-4">
                            @foreach($photos as $photo)
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <div class="card bg-dark border-0 glass-card overflow-hidden h-100">
                                        <div style="height: 250px; overflow: hidden; position: relative;">
                                            <img src="{{ $photo->photo_url }}" class="card-img-top w-100 h-100" style="object-fit: cover;" alt="Progreso">
                                            <span class="badge bg-primary position-absolute top-2 end-2 px-2 py-1" style="font-size: 11px;">
                                                {{ $photo->angle_label }}
                                            </span>
                                        </div>
                                        <div class="card-body p-3">
                                            <h6 class="text-white mb-1 font-heading">{{ $photo->taken_at->format('d/m/Y') }}</h6>
                                            <p class="text-secondary small mb-0">{{ $photo->caption ?? 'Sin descripción' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para subir fotos de progreso -->
<div class="modal fade" id="uploadPhotoModal" tabindex="-1" aria-labelledby="uploadPhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0 text-white" style="background-color: var(--surface-light);">
            <div class="modal-header border-bottom border-secondary border-opacity-25 p-4">
                <h5 class="modal-title font-heading" id="uploadPhotoModalLabel">Subir Foto de Progreso</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('student.progress.photo.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="photo" class="form-label text-secondary small">Seleccionar Imagen</label>
                        <input type="file" class="form-control bg-dark border-secondary border-opacity-25 text-white" id="photo" name="photo" accept="image/*" required>
                    </div>

                    <div class="mb-3">
                        <label for="angle" class="form-label text-secondary small">Ángulo de la foto</label>
                        <select class="form-select bg-dark border-secondary border-opacity-25 text-white" id="angle" name="angle" required>
                            <option value="front">Frontal</option>
                            <option value="side">Lateral</option>
                            <option value="back">Posterior</option>
                            <option value="other">Otro</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="taken_at" class="form-label text-secondary small">Fecha de la foto</label>
                        <input type="date" class="form-control bg-dark border-secondary border-opacity-25 text-white" id="taken_at" name="taken_at" value="{{ now()->toDateString() }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="caption" class="form-label text-secondary small">Comentario o Nota (Opcional)</label>
                        <input type="text" class="form-control bg-dark border-secondary border-opacity-25 text-white" id="caption" name="caption" placeholder="Ej. Después del entrenamiento de pierna">
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-25 p-4">
                    <button type="button" class="btn btn-outline-secondary border-0 text-white" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-premium px-4">Subir Foto</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@if(count($chartData['labels']) > 0)
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const labels = @json($chartData['labels']);
        const weightData = @json($chartData['weight']);
        const fatData = @json($chartData['fat']);
        const muscleData = @json($chartData['muscle']);

        // Weight Chart
        const ctxWeight = document.getElementById('weightChart').getContext('2d');
        
        // Gradient fill for weight line
        const weightGradient = ctxWeight.createLinearGradient(0, 0, 0, 300);
        weightGradient.addColorStop(0, 'rgba(108, 60, 247, 0.4)');
        weightGradient.addColorStop(1, 'rgba(108, 60, 247, 0.0)');

        new Chart(ctxWeight, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Peso Corporal (kg)',
                    data: weightData,
                    borderColor: '#6C3CF7',
                    borderWidth: 3,
                    backgroundColor: weightGradient,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#6C3CF7',
                    pointBorderColor: '#fff',
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)'
                        },
                        ticks: {
                            color: '#94A3B8'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#94A3B8'
                        }
                    }
                }
            }
        });

        // Composition Chart
        const ctxComp = document.getElementById('compositionChart').getContext('2d');
        new Chart(ctxComp, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Grasa (%)',
                        data: fatData,
                        borderColor: '#FF6B35',
                        borderWidth: 2,
                        tension: 0.3,
                        pointBackgroundColor: '#FF6B35',
                        fill: false
                    },
                    {
                        label: 'Músculo (kg)',
                        data: muscleData,
                        borderColor: '#22C55E',
                        borderWidth: 2,
                        tension: 0.3,
                        pointBackgroundColor: '#22C55E',
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: '#F8FAFC'
                        }
                    }
                },
                scales: {
                    y: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)'
                        },
                        ticks: {
                            color: '#94A3B8'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#94A3B8'
                        }
                    }
                }
            }
        });
    });
</script>
@endif
@endsection
