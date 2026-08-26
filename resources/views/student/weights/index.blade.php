@extends('layouts.app')

@section('content')
<div class="row g-4" x-data="{ activeTab: '{{ request()->has('leaderboard_exercise_id') ? 'leaderboard' : 'personal' }}' }">
    <!-- Header Title -->
    <div class="col-12">
        <div class="glass-card p-4 border-warning d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <img src="{{ asset('images/logo-mma-gold.jpg') }}" alt="Legionarios" class="rounded-circle border border-warning shadow" style="width: 60px; height: 60px; object-fit: cover;">
                <div>
                    <h3 class="text-white font-heading mb-1">Registro de Pesos & Hall de la Fama 🏋️‍♂️🏆</h3>
                    <p class="text-secondary small mb-0">Registra tus marcas personales, analiza tu progresión semanal y compite con la comunidad de Legionarios.</p>
                </div>
            </div>

            <!-- Tab Buttons -->
            <div class="btn-group p-1 rounded-pill bg-dark border border-secondary border-opacity-25" role="group">
                <button type="button" 
                        class="btn font-heading fw-bold btn-sm px-4 py-2 rounded-pill" 
                        :class="activeTab === 'personal' ? 'btn-danger text-white shadow' : 'btn-dark text-secondary border-0'"
                        @click="activeTab = 'personal'">
                    <i data-lucide="user" class="me-1" style="width: 16px;"></i> Mi Progreso Personal
                </button>
                <button type="button" 
                        class="btn font-heading fw-bold btn-sm px-4 py-2 rounded-pill" 
                        :class="activeTab === 'leaderboard' ? 'btn-warning text-dark shadow' : 'btn-dark text-secondary border-0'"
                        @click="activeTab = 'leaderboard'">
                    <i data-lucide="trophy" class="me-1" style="width: 16px;"></i> Ranking General (Competencia)
                </button>
            </div>
        </div>
    </div>

    <!-- TAB 1: PROGRESO PERSONAL -->
    <div class="col-12" x-show="activeTab === 'personal'" x-transition>
        <div class="row g-4">
            <!-- Form Card: Register New Weight -->
            <div class="col-lg-5">
                <div class="glass-card p-4 h-100 border-warning">
                    <h5 class="text-warning font-heading mb-3 d-flex align-items-center gap-2">
                        <i data-lucide="plus-circle" style="width: 22px;"></i> Registrar Nuevo Peso
                    </h5>

                    <form method="POST" action="{{ route('student.weights.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="exercise_id" class="form-label text-secondary small fw-bold">Ejercicio</label>
                            <select class="form-select bg-dark text-white border-secondary border-opacity-25 py-2" id="exercise_id" name="exercise_id" required>
                                @foreach($exercises as $ex)
                                    <option value="{{ $ex->id }}" {{ $selectedExerciseId == $ex->id ? 'selected' : '' }}>
                                        {{ $ex->name }} ({{ $ex->category_label ?? 'Fuerza' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label for="weight_kg" class="form-label text-secondary small fw-bold">Peso (kg)</label>
                                <input type="number" step="0.5" min="0.5" max="1000" class="form-control bg-dark text-white border-secondary border-opacity-25" id="weight_kg" name="weight_kg" placeholder="ej: 75.0" required>
                            </div>
                            <div class="col-6">
                                <label for="reps" class="form-label text-secondary small fw-bold">Repeticiones</label>
                                <input type="number" min="1" max="100" class="form-control bg-dark text-white border-secondary border-opacity-25" id="reps" name="reps" value="5" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="logged_at" class="form-label text-secondary small fw-bold">Fecha del Registro</label>
                            <input type="date" class="form-control bg-dark text-white border-secondary border-opacity-25" id="logged_at" name="logged_at" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label text-secondary small fw-bold">Notas u Observaciones (Opcional)</label>
                            <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-25" id="notes" name="notes" placeholder="ej: Sensación liviana, buena técnica">
                        </div>

                        <button type="submit" class="btn btn-premium w-100 py-3">
                            <i data-lucide="check" class="me-2" style="width: 18px;"></i> Guardar en Mi Historial
                        </button>
                    </form>
                </div>
            </div>

            <!-- PR Summary Cards -->
            <div class="col-lg-7">
                <div class="glass-card p-4 h-100">
                    <h5 class="text-white font-heading mb-3 d-flex align-items-center gap-2">
                        <i data-lucide="award" class="text-warning" style="width: 22px;"></i> Mis Récords Personales (PRs)
                    </h5>

                    <div class="row g-3">
                        @forelse($exercises->take(6) as $ex)
                            <div class="col-sm-6 col-md-4">
                                <div class="p-3 rounded bg-dark bg-opacity-50 border border-secondary border-opacity-10 text-center h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <small class="text-secondary d-block text-truncate mb-1 fw-bold">{{ $ex->name }}</small>
                                        <span class="fs-3 font-heading fw-bold text-warning">
                                            {{ isset($personalPrs[$ex->id]) ? $personalPrs[$ex->id] . ' kg' : 'S/R' }}
                                        </span>
                                    </div>
                                    <small class="text-secondary d-block mt-2" style="font-size: 10px;">
                                        {{ isset($personalPrs[$ex->id]) ? '👑 Récord Máximo' : 'Sin registro aún' }}
                                    </small>
                                </div>
                            </div>
                        @empty
                            <p class="text-secondary small">No hay ejercicios registrados en el sistema.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Chart Section: Weekly Weight Progression -->
            <div class="col-12">
                <div class="glass-card p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                        <div>
                            <h5 class="text-white font-heading mb-1 d-flex align-items-center gap-2">
                                <i data-lucide="trending-up" class="text-danger" style="width: 22px;"></i> Gráfico de Evolución de Cargas
                            </h5>
                            <small class="text-secondary">Evolución del peso levantado y estimación de 1RM por fecha.</small>
                        </div>

                        <!-- Filter Dropdown for Chart -->
                        <form method="GET" action="{{ route('student.weights.index') }}" class="d-flex align-items-center gap-2">
                            <label class="text-secondary small fw-bold text-nowrap">Ver Ejercicio:</label>
                            <select name="exercise_id" class="form-select form-select-sm bg-dark text-white border-warning" onchange="this.form.submit()">
                                @foreach($exercises as $ex)
                                    <option value="{{ $ex->id }}" {{ $selectedExerciseId == $ex->id ? 'selected' : '' }}>
                                        {{ $ex->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    @if(count($chartWeights) > 0)
                        <div style="height: 320px; width: 100%;">
                            <canvas id="weightProgressChart"></canvas>
                        </div>
                    @else
                        <div class="text-center py-5 text-secondary">
                            <i data-lucide="line-chart" class="mb-2" style="width: 48px; height: 48px;"></i>
                            <p class="mb-0">Aún no registras datos para <strong>{{ $selectedExercise ? $selectedExercise->name : 'este ejercicio' }}</strong>. Utiliza el formulario arriba para guardar tu primer peso.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- History Table -->
            <div class="col-12">
                <div class="glass-card p-4">
                    <h5 class="text-white font-heading mb-3">Historial Completo de Registros</h5>

                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle mb-0" style="--bs-table-bg: transparent;">
                            <thead>
                                <tr class="text-secondary small border-bottom border-secondary border-opacity-25">
                                    <th>Fecha</th>
                                    <th>Ejercicio</th>
                                    <th>Peso (kg)</th>
                                    <th>Reps</th>
                                    <th>1RM Est.</th>
                                    <th>Notas</th>
                                    <th class="text-end">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($personalLogs as $log)
                                    <tr>
                                        <td>{{ $log->logged_at->format('d/m/Y') }}</td>
                                        <td class="fw-bold text-white">{{ $log->exercise->name }}</td>
                                        <td><span class="badge bg-danger bg-opacity-25 text-danger px-2 py-1 fs-6 font-heading">{{ $log->weight_kg }} kg</span></td>
                                        <td>{{ $log->reps }}</td>
                                        <td><span class="text-warning fw-bold">{{ $log->one_rep_max }} kg</span></td>
                                        <td class="text-secondary small">{{ $log->notes ?? '-' }}</td>
                                        <td class="text-end">
                                            <form method="POST" action="{{ route('student.weights.destroy', $log->id) }}" onsubmit="return confirm('¿Seguro que deseas eliminar este registro?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                                    <i data-lucide="trash-2" style="width: 16px;"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-secondary">
                                            No tienes registros de pesos aún. ¡Comienza a ingresar tus levantamientos hoy!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 2: RANKING GENERAL / LEADERBOARD DE COMPETENCIA -->
    <div class="col-12" x-show="activeTab === 'leaderboard'" x-transition>
        <div class="row g-4">
            <!-- Podium Top 3 Overall -->
            <div class="col-12">
                <div class="glass-card p-4 border-warning bg-black bg-opacity-40">
                    <div class="text-center mb-4">
                        <span class="badge bg-warning text-dark font-heading fw-bold px-3 py-1 text-uppercase">
                            Hall de la Fama Legionario
                        </span>
                        <h4 class="text-white font-heading fw-bold mt-2 mb-0">GUERREROS CON MAYORES LEVANTAMIENTOS 🏆</h4>
                    </div>

                    <div class="row justify-content-center align-items-end g-3 text-center">
                        @if(isset($topStudentsOverall[1]))
                            <!-- 2do Lugar (Plata) -->
                            <div class="col-4 col-md-3">
                                <div class="p-3 glass-card border-secondary rounded-top">
                                    <div class="fs-1">🥈</div>
                                    <h6 class="text-white font-heading mb-1 text-truncate">{{ $topStudentsOverall[1]->user->name }}</h6>
                                    <span class="badge bg-secondary text-white">{{ $topStudentsOverall[1]->top_weight }} kg</span>
                                    <small class="text-secondary d-block mt-1" style="font-size: 10px;">2º Puesto</small>
                                </div>
                            </div>
                        @endif

                        @if(isset($topStudentsOverall[0]))
                            <!-- 1er Lugar (Oro) -->
                            <div class="col-4 col-md-4">
                                <div class="p-4 glass-card border-warning bg-warning bg-opacity-10 rounded-top shadow-lg" style="transform: scale(1.05);">
                                    <div class="fs-1">🥇</div>
                                    <h5 class="text-warning font-heading mb-1 text-truncate fw-bold">{{ $topStudentsOverall[0]->user->name }}</h5>
                                    <span class="badge bg-warning text-dark font-heading fs-6 px-3 py-1">{{ $topStudentsOverall[0]->top_weight }} kg</span>
                                    <small class="text-warning d-block mt-2 font-heading fw-bold" style="font-size: 11px;">👑 LÍDER ABSOLUTO</small>
                                </div>
                            </div>
                        @endif

                        @if(isset($topStudentsOverall[2]))
                            <!-- 3er Lugar (Bronce) -->
                            <div class="col-4 col-md-3">
                                <div class="p-3 glass-card border-danger rounded-top">
                                    <div class="fs-1">🥉</div>
                                    <h6 class="text-white font-heading mb-1 text-truncate">{{ $topStudentsOverall[2]->user->name }}</h6>
                                    <span class="badge bg-danger text-white">{{ $topStudentsOverall[2]->top_weight }} kg</span>
                                    <small class="text-secondary d-block mt-1" style="font-size: 10px;">3º Puesto</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- General Leaderboard Table -->
            <div class="col-12">
                <div class="glass-card p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                        <div>
                            <h5 class="text-white font-heading mb-1 d-flex align-items-center gap-2">
                                <i data-lucide="award" class="text-warning" style="width: 22px;"></i> Tabla General de Clasificación de Legionarios
                            </h5>
                            <small class="text-secondary">Ránking de máximos levantamientos por alumno y ejercicio.</small>
                        </div>

                        <!-- Filter Dropdown for Leaderboard -->
                        <form method="GET" action="{{ route('student.weights.index') }}" class="d-flex align-items-center gap-2">
                            <label class="text-secondary small fw-bold text-nowrap">Filtrar Ejercicio:</label>
                            <select name="leaderboard_exercise_id" class="form-select form-select-sm bg-dark text-white border-warning" onchange="this.form.submit()">
                                <option value="">Todos los Ejercicios</option>
                                @foreach($exercises as $ex)
                                    <option value="{{ $ex->id }}" {{ $leaderboardFilterExerciseId == $ex->id ? 'selected' : '' }}>
                                        {{ $ex->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle mb-0" style="--bs-table-bg: transparent;">
                            <thead>
                                <tr class="text-secondary small border-bottom border-secondary border-opacity-25">
                                    <th style="width: 60px;">Posición</th>
                                    <th>Alumno / Legionario</th>
                                    <th>Ejercicio</th>
                                    <th>Máximo Peso (PR)</th>
                                    <th>1RM Máximo Estimado</th>
                                    <th>Fecha Registro</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leaderboardRecords as $index => $rec)
                                    <tr class="{{ $rec->user_id === auth()->id() ? 'bg-danger bg-opacity-10 border-start border-danger border-3' : '' }}">
                                        <td class="fw-bold fs-5">
                                            @if($index === 0) 🥇
                                            @elseif($index === 1) 🥈
                                            @elseif($index === 2) 🥉
                                            @else #{{ $index + 1 }}
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ $rec->user->avatar_url }}" alt="Avatar" class="rounded-circle" style="width: 34px; height: 34px; object-fit: cover;">
                                                <div>
                                                    <span class="text-white fw-bold d-block lh-1">{{ $rec->user->name }}</span>
                                                    @if($rec->user_id === auth()->id())
                                                        <small class="badge bg-warning text-dark" style="font-size: 9px;">TÚ</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-secondary small">{{ $rec->exercise->name }}</td>
                                        <td>
                                            <span class="badge bg-danger text-white fs-6 font-heading px-3 py-1">
                                                {{ $rec->max_weight }} kg
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-warning fw-bold">{{ $rec->max_1rm }} kg</span>
                                        </td>
                                        <td class="text-secondary small">{{ Carbon\Carbon::parse($rec->last_logged)->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-secondary">
                                            No hay registros de peso en la clasificación general. ¡Sé el primero en ingresar una marca!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@if(count($chartWeights) > 0)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('weightProgressChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [
                    {
                        label: 'Peso Levantado (kg)',
                        data: {!! json_encode($chartWeights) !!},
                        borderColor: '#DC2626',
                        backgroundColor: 'rgba(220, 38, 38, 0.15)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: '#DC2626',
                        pointRadius: 5
                    },
                    {
                        label: '1RM Estimado (kg)',
                        data: {!! json_encode($chartOneRepMax) !!},
                        borderColor: '#F59E0B',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        fill: false,
                        tension: 0.3,
                        pointBackgroundColor: '#F59E0B',
                        pointRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: '#F8FAFC',
                            font: { family: 'Inter', size: 12 }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: { color: '#94A3B8' }
                    },
                    y: {
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: { color: '#94A3B8' }
                    }
                }
            }
        });
    });
</script>
@endif
@endsection
