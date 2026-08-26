@extends('layouts.app')

@section('content')
<div class="row g-4">
    <!-- Page Header -->
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="text-white mb-1">Gestión de Usuarios</h3>
                <p class="text-secondary mb-0">Administra cuentas, asigna roles, entrenadores y membresías.</p>
            </div>
            
            <button type="button" class="btn btn-premium d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createUserModal">
                <i data-lucide="user-plus" style="width: 18px;"></i>
                <span>Nuevo Usuario</span>
            </button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="col-12">
        <div class="glass-card p-3 d-flex flex-wrap gap-2 justify-content-between align-items-center">
            <div class="d-flex gap-2">
                <a href="{{ route('admin.users.index', ['role' => 'student']) }}" class="btn btn-sm {{ $roleFilter === 'student' ? 'btn-primary' : 'btn-dark bg-opacity-40 text-secondary' }} px-3 py-2 rounded-3">
                    Alumnos
                </a>
                <a href="{{ route('admin.users.index', ['role' => 'trainer']) }}" class="btn btn-sm {{ $roleFilter === 'trainer' ? 'btn-primary' : 'btn-dark bg-opacity-40 text-secondary' }} px-3 py-2 rounded-3">
                    Entrenadores
                </a>
                <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="btn btn-sm {{ $roleFilter === 'admin' ? 'btn-primary' : 'btn-dark bg-opacity-40 text-secondary' }} px-3 py-2 rounded-3">
                    Administradores
                </a>
            </div>
            <span class="text-secondary small">Mostrando {{ $users->count() }} usuarios</span>
        </div>
    </div>

    <!-- Users table -->
    <div class="col-12">
        <div class="glass-card p-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle">
                    <thead>
                        <tr class="text-secondary">
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            @if($roleFilter === 'student')
                                <th>Entrenador</th>
                                <th>Membresía</th>
                            @endif
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr class="text-white">
                                <td class="fw-bold">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $user->avatar_url }}" alt="Profile" class="rounded-circle" style="width: 34px; height: 34px; object-fit: cover;">
                                        <span>{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?? '-' }}</td>
                                @if($roleFilter === 'student')
                                    <td>{{ $user->trainer ? $user->trainer->name : 'Sin asignar' }}</td>
                                    <td>
                                        @if($user->activeMembership)
                                            <span class="badge bg-success bg-opacity-15 text-success">
                                                {{ $user->activeMembership->plan->name }} (Activo)
                                            </span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-15 text-danger">
                                                Sin Plan Activo
                                            </span>
                                        @endif
                                    </td>
                                @endif
                                <td>
                                    <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                        {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-secondary border-secondary border-opacity-25 text-white" data-bs-toggle="modal" data-bs-target="#editUserModal-{{ $user->id }}">
                                        Editar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-secondary">No hay usuarios registrados con este rol.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ==================== MODALS CONTAINER ==================== -->
<!-- We place modals here outside tables and glass-cards to avoid Bootstrap backdrop clipping / dark screen bug -->

<!-- Modals de Edición -->
@foreach($users as $user)
    <div class="modal fade" id="editUserModal-{{ $user->id }}" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content glass-card border-0 text-white" style="background-color: var(--surface-light); box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);">
                <div class="modal-header border-bottom border-secondary border-opacity-25 p-4">
                    <h5 class="modal-title font-heading">Editar Usuario: {{ $user->name }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label text-secondary small">Nombre completo*</label>
                            <input type="text" class="form-control bg-dark border-secondary border-opacity-25 text-white" name="name" value="{{ $user->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary small">Email*</label>
                            <input type="email" class="form-control bg-dark border-secondary border-opacity-25 text-white" name="email" value="{{ $user->email }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary small">Teléfono</label>
                            <input type="text" class="form-control bg-dark border-secondary border-opacity-25 text-white" name="phone" value="{{ $user->phone }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary small">Nueva Contraseña (Dejar vacío si no cambia)</label>
                            <input type="password" class="form-control bg-dark border-secondary border-opacity-25 text-white" name="password" placeholder="Contraseña">
                        </div>

                        @if($roleFilter === 'student')
                            <div class="mb-3">
                                <label class="form-label text-secondary small">Entrenador Asignado</label>
                                <select class="form-select bg-dark border-secondary border-opacity-25 text-white" name="trainer_id">
                                    <option value="">-- Sin Entrenador --</option>
                                    @foreach($trainers as $trainer)
                                        <option value="{{ $trainer->id }}" {{ $user->trainer_id == $trainer->id ? 'selected' : '' }}>{{ $trainer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label text-secondary small">Estado de la cuenta*</label>
                            <select class="form-select bg-dark border-secondary border-opacity-25 text-white" name="is_active" required>
                                <option value="1" {{ $user->is_active ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-secondary border-opacity-25 p-4">
                        <button type="button" class="btn btn-outline-secondary border-0 text-white" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-premium px-4">Actualizar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<!-- Modal para crear usuario -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true" x-data="{ selectedRole: '3' }" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0 text-white" style="background-color: var(--surface-light); box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);">
            <div class="modal-header border-bottom border-secondary border-opacity-25 p-4">
                <h5 class="modal-title font-heading">Registrar Nuevo Usuario</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Rol de Usuario*</label>
                        <select class="form-select bg-dark border-secondary border-opacity-25 text-white" name="role_id" x-model="selectedRole" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ $role->name === 'student' ? 'selected' : '' }}>{{ $role->display_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small">Nombre completo*</label>
                        <input type="text" class="form-control bg-dark border-secondary border-opacity-25 text-white" name="name" placeholder="Ej. Juan Pérez" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small">Email*</label>
                        <input type="email" class="form-control bg-dark border-secondary border-opacity-25 text-white" name="email" placeholder="email@ejemplo.com" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small">Teléfono</label>
                        <input type="text" class="form-control bg-dark border-secondary border-opacity-25 text-white" name="phone" placeholder="+56912345678">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small">Contraseña de acceso*</label>
                        <input type="password" class="form-control bg-dark border-secondary border-opacity-25 text-white" name="password" placeholder="Mínimo 8 caracteres" required>
                    </div>

                    <!-- Campos Condicionales de Alumno (selectedRole 3 / student) -->
                    @php
                        $studentRoleObj = $roles->where('name', 'student')->first();
                    @endphp
                    @if($studentRoleObj)
                        <div x-show="selectedRole == '{{ $studentRoleObj->id }}'">
                            <hr class="border-secondary border-opacity-25 my-3">
                            <h6 class="text-secondary small mb-3">Campos de Alumno</h6>

                            <div class="mb-3">
                                <label class="form-label text-secondary small">Entrenador Asignado</label>
                                <select class="form-select bg-dark border-secondary border-opacity-25 text-white" name="trainer_id">
                                    <option value="">-- Elige Entrenador --</option>
                                    @foreach($trainers as $trainer)
                                        <option value="{{ $trainer->id }}">{{ $trainer->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-secondary small">Asignar Membresía Inicial (Opcional)</label>
                                <select class="form-select bg-dark border-secondary border-opacity-25 text-white" name="plan_id">
                                    <option value="">-- Sin Membresía inicial --</option>
                                    @foreach($plans as $plan)
                                        <option value="{{ $plan->id }}">{{ $plan->name }} (${{ number_format($plan->price, 0) }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-25 p-4">
                    <button type="button" class="btn btn-outline-secondary border-0 text-white" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-premium px-4">Crear Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
