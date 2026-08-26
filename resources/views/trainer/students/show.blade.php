@extends('layouts.app')

@section('content')
<div class="row g-4">
    <!-- Page Header & Quick Profile Info -->
    <div class="col-12">
        <div class="glass-card p-4 d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
            <div class="d-flex flex-column flex-md-row align-items-center gap-3 text-center text-md-start">
                <img src="{{ $student->avatar_url }}" alt="Profile Picture" class="rounded-circle border border-primary border-3" style="width: 80px; height: 80px; object-fit: cover;">
                <div>
                    <h3 class="text-white mb-1 font-heading">{{ $student->name }}</h3>
                    <p class="text-secondary mb-0">
                        <i data-lucide="mail" class="me-1 text-primary" style="width: 16px; vertical-align: middle;"></i>{{ $student->email }}
                        <span class="mx-2 text-secondary">|</span>
                        <i data-lucide="target" class="me-1 text-primary" style="width: 16px; vertical-align: middle;"></i>Objetivo: {{ $student->objective ?? 'Sin registrar' }}
                    </p>
                </div>
            </div>

            <!-- Header Quick Stats -->
            <div class="d-flex gap-2">
                <div class="p-2 px-3 rounded bg-dark bg-opacity-40 border border-secondary border-opacity-10 text-center">
                    <span class="text-secondary small d-block" style="font-size: 10px;">Racha Asist.</span>
                    <span class="text-white font-heading fw-bold stat-value">🔥 {{ $streak }} días</span>
                </div>
                <div class="p-2 px-3 rounded bg-dark bg-opacity-40 border border-secondary border-opacity-10 text-center">
                    <span class="text-secondary small d-block" style="font-size: 10px;">Asist. Mes</span>
                    <span class="text-white font-heading fw-bold stat-value">📅 {{ $attendancesThisMonth }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Pestañas de control -->
    <div class="col-12">
        <ul class="nav nav-pills gap-2" id="studentDetailTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active glass-card text-white py-2 px-4 border-0" id="stats-tab" data-bs-toggle="tab" data-bs-target="#stats-pane" type="button" role="tab">
                    <i data-lucide="line-chart" class="me-2" style="width: 18px; vertical-align: text-bottom;"></i>Medidas y Gráficos
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link glass-card text-white py-2 px-4 border-0" id="photos-tab" data-bs-toggle="tab" data-bs-target="#photos-pane" type="button" role="tab">
                    <i data-lucide="images" class="me-2" style="width: 18px; vertical-align: text-bottom;"></i>Fotos de Progreso
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link glass-card text-white py-2 px-4 border-0" id="obs-tab" data-bs-toggle="tab" data-bs-target="#obs-pane" type="button" role="tab">
                    <i data-lucide="message-square" class="me-2" style="width: 18px; vertical-align: text-bottom;"></i>Observaciones y Feedback
                </button>
            </li>
        </ul>
    </div>

    <!-- Contenido de las pestañas -->
    <div class="col-12">
        <div class="tab-content" id="studentDetailTabContent">
            <!-- 1. GRÁFICOS Y HISTORIAL PANE -->
            <div class="tab-pane fade show active" id="stats-pane" role="tabpanel" aria-labelledby="stats-tab" tabindex="0">
                <div class="row g-4">
                    <!-- Formulario de Medidas (Izquierda) -->
                    <div class="col-lg-4">
                        <div class="glass-card p-4">
                            <h5 class="text-white mb-3">Registrar Nueva Evaluación</h5>
                            
                            <form action="{{ route('trainer.students.measurements.store', $student) }}" method="POST">
                                @csrf
                                <div class="row g-2">
                                    <div class="col-6 mb-2">
                                        <label class="form-label text-secondary small">Peso (kg)*</label>
                                        <input type="number" step="0.01" class="form-control bg-dark border-secondary border-opacity-25 text-white" name="weight" required>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="form-label text-secondary small">Altura (cm)*</label>
                                        <input type="number" step="0.1" class="form-control bg-dark border-secondary border-opacity-25 text-white" name="height" value="{{ $student->latestBodyStat ? $student->latestBodyStat->height : '' }}" required>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="form-label text-secondary small">Grasa (%)</label>
                                        <input type="number" step="0.01" class="form-control bg-dark border-secondary border-opacity-25 text-white" name="body_fat">
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="form-label text-secondary small">Músculo (kg)</label>
                                        <input type="number" step="0.01" class="form-control bg-dark border-secondary border-opacity-25 text-white" name="muscle_mass">
                                    </div>
                                    
                                    <hr class="border-secondary border-opacity-25 my-2">
                                    <h6 class="text-secondary small col-12 mb-1">Perímetros (cm)</h6>

                                    <div class="col-4 mb-2">
                                        <label class="form-label text-secondary small">Cintura</label>
                                        <input type="number" step="0.1" class="form-control bg-dark border-secondary border-opacity-25 text-white" name="waist">
                                    </div>
                                    <div class="col-4 mb-2">
                                        <label class="form-label text-secondary small">Cadera</label>
                                        <input type="number" step="0.1" class="form-control bg-dark border-secondary border-opacity-25 text-white" name="hip">
                                    </div>
                                    <div class="col-4 mb-2">
                                        <label class="form-label text-secondary small">Brazo</label>
                                        <input type="number" step="0.1" class="form-control bg-dark border-secondary border-opacity-25 text-white" name="arm">
                                    </div>
                                    <div class="col-4 mb-2">
                                        <label class="form-label text-secondary small">Pierna</label>
                                        <input type="number" step="0.1" class="form-control bg-dark border-secondary border-opacity-25 text-white" name="leg">
                                    </div>
                                    <div class="col-4 mb-2">
                                        <label class="form-label text-secondary small">Pecho</label>
                                        <input type="number" step="0.1" class="form-control bg-dark border-secondary border-opacity-25 text-white" name="chest">
                                    </div>
                                    <div class="col-4 mb-2">
                                        <label class="form-label text-secondary small">Fecha*</label>
                                        <input type="date" class="form-control bg-dark border-secondary border-opacity-25 text-white" name="measured_at" value="{{ now()->toDateString() }}" required>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label class="form-label text-secondary small">Notas u Observaciones</label>
                                        <input type="text" class="form-control bg-dark border-secondary border-opacity-25 text-white" name="notes" placeholder="Ej. Medición post vacaciones">
                                    </div>

                                    <button type="submit" class="btn btn-premium w-100 py-2">
                                        Guardar Evaluación
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Gráficos e Historial (Derecha) -->
                    <div class="col-lg-8">
                        <!-- Chart Area -->
                        @if(count($chartData['labels']) > 0)
                            <div class="glass-card p-4 mb-4">
                                <h5 class="text-white mb-4">Evolución de Peso e IMC</h5>
                                <div style="height: 250px;">
                                    <canvas id="studentWeightChart"></canvas>
                                </div>
                            </div>
                        @endif

                        <!-- Historial Table -->
                        <div class="glass-card p-4">
                            <h5 class="text-white mb-4">Historial de Evaluaciones</h5>
                            <div class="table-responsive" style="max-height: 280px; overflow-y: auto;">
                                <table class="table table-dark table-hover mb-0 align-middle">
                                    <thead>
                                        <tr class="text-secondary small">
                                            <th>Fecha</th>
                                            <th>Peso (kg)</th>
                                            <th>IMC</th>
                                            <th>Grasa (%)</th>
                                            <th>Músculo (kg)</th>
                                            <th>Cintura (cm)</th>
                                            <th>Brazo (cm)</th>
                                            <th>Pierna (cm)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($bodyStats as $stat)
                                            <tr class="text-white small">
                                                <td class="fw-bold">{{ $stat->measured_at->format('d/m/Y') }}</td>
                                                <td class="stat-value">{{ $stat->weight }}</td>
                                                <td><span class="badge bg-{{ $stat->bmi_color }}">{{ $stat->bmi }}</span></td>
                                                <td class="stat-value">{{ $stat->body_fat ?? '-' }}</td>
                                                <td class="stat-value">{{ $stat->muscle_mass ?? '-' }}</td>
                                                <td class="stat-value">{{ $stat->waist ?? '-' }}</td>
                                                <td class="stat-value">{{ $stat->arm ?? '-' }}</td>
                                                <td class="stat-value">{{ $stat->leg ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-secondary">Aún no registra evaluaciones corporales.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. FOTOS PANE -->
            <div class="tab-pane fade" id="photos-pane" role="tabpanel" aria-labelledby="photos-tab" tabindex="0">
                <div class="glass-card p-4">
                    <h5 class="text-white mb-4">Fotos de Progreso Físico del Alumno</h5>
                    
                    @if($photos->isEmpty())
                        <div class="text-center py-5">
                            <i data-lucide="image-off" class="text-secondary mb-3" style="width: 56px; height: 56px;"></i>
                            <p class="text-secondary mb-0">El alumno no ha subido fotos de progreso todavía.</p>
                        </div>
                    @else
                        <div class="row g-4">
                            @foreach($photos as $photo)
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <div class="card bg-dark border-0 glass-card overflow-hidden h-100">
                                        <div style="height: 230px; overflow: hidden; position: relative;">
                                            <img src="{{ $photo->photo_url }}" class="card-img-top w-100 h-100" style="object-fit: cover;" alt="Progreso">
                                            <span class="badge bg-primary position-absolute top-2 end-2 px-2 py-1">
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

            <!-- 3. OBSERVACIONES Y FEEDBACK PANE -->
            <div class="tab-pane fade" id="obs-pane" role="tabpanel" aria-labelledby="obs-tab" tabindex="0">
                <div class="row g-4">
                    <!-- Crear Observación (Izquierda) -->
                    <div class="col-lg-4">
                        <div class="glass-card p-4">
                            <h5 class="text-white mb-4">Agregar Observación o Consejo</h5>
                            
                            <form action="{{ route('trainer.observations.store', $student) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label text-secondary small">Categoría del Feedback</label>
                                    <select class="form-select bg-dark border-secondary border-opacity-25 text-white" name="category" required>
                                        <option value="technique">🎯 Corrección Técnica</option>
                                        <option value="nutrition">🥗 Nutrición y Hábito</option>
                                        <option value="motivation">⚡ Motivación</option>
                                        <option value="injury">🩹 Cuidado de Lesión</option>
                                        <option value="progress">📈 Comentario de Progreso</option>
                                        <option value="general">📝 Nota General</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-secondary small">Visibilidad</label>
                                    <select class="form-select bg-dark border-secondary border-opacity-25 text-white" name="is_private" required>
                                        <option value="0">Público (El alumno lo puede ver en su Dashboard)</option>
                                        <option value="1">Privado (Sólo tú y los entrenadores lo ven)</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-secondary small">Mensaje / Comentario</label>
                                    <textarea class="form-control bg-dark border-secondary border-opacity-25 text-white" name="content" rows="4" placeholder="Escribe el consejo o la corrección aquí..." required></textarea>
                                </div>

                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" name="is_pinned" id="is_pinned" value="1">
                                    <label class="form-check-label text-secondary small" for="is_pinned">
                                        Destacar esta observación (Pin)
                                    </label>
                                </div>

                                <button type="submit" class="btn btn-premium w-100 py-2">
                                    Registrar Observación
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Lista de Observaciones (Derecha) -->
                    <div class="col-lg-8">
                        <div class="glass-card p-4">
                            <h5 class="text-white mb-4">Historial de Observaciones</h5>
                            
                            <div class="d-flex flex-column gap-3 overflow-auto" style="max-height: 480px;">
                                @forelse($observations as $obs)
                                    <div class="p-3 rounded bg-dark bg-opacity-40 border-start border-{{ $obs->category_color }} border-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-{{ $obs->category_color }} bg-opacity-25 text-{{ $obs->category_color }} px-2 py-1">
                                                    {{ $obs->category_icon }} {{ $obs->category_label }}
                                                </span>
                                                @if($obs->is_private)
                                                    <span class="badge bg-secondary text-secondary-light">🔒 Solo Entrenador</span>
                                                @else
                                                    <span class="badge bg-info bg-opacity-15 text-info">👁️ Visible para Alumno</span>
                                                @endif
                                                @if($obs->is_pinned)
                                                    <span class="badge bg-warning text-dark"><i data-lucide="pin" style="width: 10px; vertical-align: middle;" class="me-1"></i>Destacado</span>
                                                @endif
                                            </div>
                                            
                                            <form action="{{ route('trainer.observations.destroy', $obs) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-link text-danger border-0 p-0" title="Eliminar observación">
                                                    <i data-lucide="trash-2" style="width: 16px;"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <p class="text-white small mb-1">{{ $obs->content }}</p>
                                        <small class="text-secondary">Publicado por {{ $obs->trainer->name }} — {{ $obs->created_at->diffForHumans() }}</small>
                                    </div>
                                @empty
                                    <div class="text-center py-5 text-secondary">
                                        <i data-lucide="message-square-off" class="mb-2" style="width: 44px; height: 44px;"></i>
                                        <p class="mb-0 small">No hay observaciones registradas para este alumno.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

        const ctxWeight = document.getElementById('studentWeightChart').getContext('2d');
        const weightGradient = ctxWeight.createLinearGradient(0, 0, 0, 200);
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
                    pointRadius: 5
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
    });
</script>
@endif
@endsection
