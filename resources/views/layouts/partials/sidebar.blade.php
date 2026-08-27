<!-- Desktop Sidebar (Visible on screens >= 992px) -->
<aside class="sidebar d-none d-lg-flex flex-column justify-content-between">
    <div>
        <!-- Logo & Brand -->
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center mb-4 text-decoration-none">
            <img src="{{ asset('images/logo-legionarios.jpg') }}" alt="GYM Legionarios" class="rounded-circle border border-warning me-3 shadow" style="width: 44px; height: 44px; object-fit: cover;">
            <div>
                <span class="fs-5 fw-bold font-heading text-white d-block lh-1">LEGIONARIOS</span>
                <small class="text-warning text-uppercase fw-semibold" style="font-size: 9px; letter-spacing: 1px;">Un Dojo, Un Espíritu</small>
            </div>
        </a>

        <hr class="border-warning border-opacity-25 mb-4">

        <!-- Navigation Links -->
        <nav class="nav flex-column gap-2">
            @auth
                @if(auth()->user()->isAdmin())
                    <!-- Admin Links -->
                    <a href="{{ route('admin.dashboard') }}" class="nav-link text-white py-2 px-3 rounded d-flex align-items-center {{ request()->routeIs('admin.dashboard') ? 'bg-primary bg-opacity-25 text-primary border-start border-primary border-3 fw-bold' : '' }}">
                        <i data-lucide="layout-dashboard" class="me-3" style="width: 20px;"></i>
                        Panel General
                    </a>
                    <a href="{{ route('trainer.attendances.index') }}" class="nav-link text-white py-2 px-3 rounded d-flex align-items-center {{ request()->routeIs('trainer.attendances.*') ? 'bg-primary bg-opacity-25 text-primary border-start border-primary border-3 fw-bold' : '' }}">
                        <i data-lucide="calendar-check" class="me-3" style="width: 20px;"></i>
                        Control Asistencias
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="nav-link text-white py-2 px-3 rounded d-flex align-items-center {{ request()->routeIs('admin.users.*') ? 'bg-primary bg-opacity-25 text-primary border-start border-primary border-3 fw-bold' : '' }}">
                        <i data-lucide="users" class="me-3" style="width: 20px;"></i>
                        Gestión Usuarios
                    </a>
                @elseif(auth()->user()->isTrainer())
                    <!-- Trainer Links -->
                    <a href="{{ route('trainer.dashboard') }}" class="nav-link text-white py-2 px-3 rounded d-flex align-items-center {{ request()->routeIs('trainer.dashboard') ? 'bg-primary bg-opacity-25 text-primary border-start border-primary border-3 fw-bold' : '' }}">
                        <i data-lucide="layout-dashboard" class="me-3" style="width: 20px;"></i>
                        Mi Panel
                    </a>
                    <a href="{{ route('trainer.attendances.index') }}" class="nav-link text-white py-2 px-3 rounded d-flex align-items-center {{ request()->routeIs('trainer.attendances.*') ? 'bg-primary bg-opacity-25 text-primary border-start border-primary border-3 fw-bold' : '' }}">
                        <i data-lucide="calendar-check" class="me-3" style="width: 20px;"></i>
                        Control Asistencias
                    </a>
                    <a href="{{ route('trainer.students.index') }}" class="nav-link text-white py-2 px-3 rounded d-flex align-items-center {{ request()->routeIs('trainer.students.*') ? 'bg-primary bg-opacity-25 text-primary border-start border-primary border-3 fw-bold' : '' }}">
                        <i data-lucide="users" class="me-3" style="width: 20px;"></i>
                        Mis Alumnos
                    </a>
                    <a href="{{ route('trainer.routines.index') }}" class="nav-link text-white py-2 px-3 rounded d-flex align-items-center {{ request()->routeIs('trainer.routines.*') ? 'bg-primary bg-opacity-25 text-primary border-start border-primary border-3 fw-bold' : '' }}">
                        <i data-lucide="dumbbell" class="me-3" style="width: 20px;"></i>
                        Gestión Rutinas
                    </a>
                @elseif(auth()->user()->isStudent())
                    <!-- Student Links -->
                    <a href="{{ route('student.dashboard') }}" class="nav-link text-white py-2 px-3 rounded d-flex align-items-center {{ request()->routeIs('student.dashboard') ? 'bg-primary bg-opacity-25 text-primary border-start border-primary border-3 fw-bold' : '' }}">
                        <i data-lucide="home" class="me-3" style="width: 20px;"></i>
                        Inicio
                    </a>
                    <a href="{{ route('student.attendances.index') }}" class="nav-link text-white py-2 px-3 rounded d-flex align-items-center {{ request()->routeIs('student.attendances.*') ? 'bg-primary bg-opacity-25 text-primary border-start border-primary border-3 fw-bold' : '' }}">
                        <i data-lucide="calendar-check" class="me-3" style="width: 20px;"></i>
                        Mi Asistencia
                    </a>
                    <a href="{{ route('student.routine') }}" class="nav-link text-white py-2 px-3 rounded d-flex align-items-center {{ request()->routeIs('student.routine') ? 'bg-primary bg-opacity-25 text-primary border-start border-primary border-3 fw-bold' : '' }}">
                        <i data-lucide="dumbbell" class="me-3" style="width: 20px;"></i>
                        Mi Rutina
                    </a>
                    <a href="{{ route('student.progress') }}" class="nav-link text-white py-2 px-3 rounded d-flex align-items-center {{ request()->routeIs('student.progress') ? 'bg-primary bg-opacity-25 text-primary border-start border-primary border-3 fw-bold' : '' }}">
                        <i data-lucide="trending-up" class="me-3" style="width: 20px;"></i>
                        Progreso Físico
                    </a>
                    <a href="{{ route('student.weights.index') }}" class="nav-link text-white py-2 px-3 rounded d-flex align-items-center {{ request()->routeIs('student.weights.*') ? 'bg-primary bg-opacity-25 text-primary border-start border-primary border-3 fw-bold' : '' }}">
                        <i data-lucide="trophy" class="me-3" style="width: 20px;"></i>
                        Pesos & Ranking
                    </a>
                @endif
            @endauth
        </nav>
    </div>

    <!-- User Profile Dropdown / Logout (Desktop) -->
    <div>
        @auth
            <hr class="border-secondary border-opacity-25 mb-3">
            <div class="d-flex align-items-center mb-3">
                <img src="{{ auth()->user()->avatar_url }}" alt="Profile" class="rounded-circle me-3 border border-warning" style="width: 40px; height: 40px; object-fit: cover;">
                <div class="overflow-hidden">
                    <h6 class="mb-0 text-white text-truncate">{{ auth()->user()->name }}</h6>
                    <small class="text-secondary text-capitalize">{{ auth()->user()->getPrimaryRole() }}</small>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100 border-0 text-start d-flex align-items-center px-3 py-2 rounded">
                    <i data-lucide="log-out" class="me-3" style="width: 18px;"></i>
                    Cerrar Sesión
                </button>
            </form>
        @endauth
    </div>
</aside>

<!-- Mobile Offcanvas Sidebar Drawer (Only visible on screens < 992px) -->
<div class="offcanvas offcanvas-start text-white d-lg-none" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel" style="background-color: var(--surface); border-right: 1px solid var(--border-glass); width: 290px;">
    <div class="offcanvas-header border-bottom border-warning border-opacity-25 py-3">
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
            <img src="{{ asset('images/logo-legionarios.jpg') }}" alt="GYM Legionarios" class="rounded-circle border border-warning me-2 shadow-sm" style="width: 38px; height: 38px; object-fit: cover;">
            <div>
                <span class="fs-6 fw-bold font-heading text-white d-block lh-1">LEGIONARIOS</span>
                <small class="text-warning text-uppercase" style="font-size: 8px; letter-spacing: 1px;">Un Dojo, Un Espíritu</small>
            </div>
        </a>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column justify-content-between p-3">
        <!-- Navigation Links -->
        <nav class="nav flex-column gap-2">
            @auth
                <div class="px-2 py-1 mb-2 bg-dark bg-opacity-50 rounded border border-secondary border-opacity-10 d-flex align-items-center gap-2">
                    <img src="{{ auth()->user()->avatar_url }}" alt="Profile" class="rounded-circle border border-warning" style="width: 32px; height: 32px; object-fit: cover;">
                    <div class="overflow-hidden">
                        <div class="text-white small fw-bold text-truncate">{{ auth()->user()->name }}</div>
                        <span class="badge bg-primary bg-opacity-25 text-primary text-capitalize" style="font-size: 9px;">{{ auth()->user()->getPrimaryRole() }}</span>
                    </div>
                </div>

                @if(auth()->user()->isAdmin())
                    <!-- Admin Mobile Links -->
                    <a href="{{ route('admin.dashboard') }}" class="nav-link text-white py-3 px-3 rounded d-flex align-items-center {{ request()->routeIs('admin.dashboard') ? 'bg-primary bg-opacity-25 text-primary border-start border-primary border-3 fw-bold' : '' }}">
                        <i data-lucide="layout-dashboard" class="me-3 text-warning" style="width: 20px;"></i>
                        Panel General
                    </a>
                    <a href="{{ route('trainer.attendances.index') }}" class="nav-link text-white py-3 px-3 rounded d-flex align-items-center {{ request()->routeIs('trainer.attendances.*') ? 'bg-primary bg-opacity-25 text-primary border-start border-primary border-3 fw-bold' : '' }}">
                        <i data-lucide="calendar-check" class="me-3 text-warning" style="width: 20px;"></i>
                        Control Asistencias
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="nav-link text-white py-3 px-3 rounded d-flex align-items-center {{ request()->routeIs('admin.users.*') ? 'bg-primary bg-opacity-25 text-primary border-start border-primary border-3 fw-bold' : '' }}">
                        <i data-lucide="users" class="me-3 text-warning" style="width: 20px;"></i>
                        Gestión Usuarios
                    </a>
                @elseif(auth()->user()->isTrainer())
                    <!-- Trainer Mobile Links -->
                    <a href="{{ route('trainer.dashboard') }}" class="nav-link text-white py-3 px-3 rounded d-flex align-items-center {{ request()->routeIs('trainer.dashboard') ? 'bg-primary bg-opacity-25 text-primary border-start border-primary border-3 fw-bold' : '' }}">
                        <i data-lucide="layout-dashboard" class="me-3 text-warning" style="width: 20px;"></i>
                        Mi Panel
                    </a>
                    <a href="{{ route('trainer.attendances.index') }}" class="nav-link text-white py-3 px-3 rounded d-flex align-items-center {{ request()->routeIs('trainer.attendances.*') ? 'bg-primary bg-opacity-25 text-primary border-start border-primary border-3 fw-bold' : '' }}">
                        <i data-lucide="calendar-check" class="me-3 text-warning" style="width: 20px;"></i>
                        Control Asistencias
                    </a>
                    <a href="{{ route('trainer.students.index') }}" class="nav-link text-white py-3 px-3 rounded d-flex align-items-center {{ request()->routeIs('trainer.students.*') ? 'bg-primary bg-opacity-25 text-primary border-start border-primary border-3 fw-bold' : '' }}">
                        <i data-lucide="users" class="me-3 text-warning" style="width: 20px;"></i>
                        Mis Alumnos
                    </a>
                    <a href="{{ route('trainer.routines.index') }}" class="nav-link text-white py-3 px-3 rounded d-flex align-items-center {{ request()->routeIs('trainer.routines.*') ? 'bg-primary bg-opacity-25 text-primary border-start border-primary border-3 fw-bold' : '' }}">
                        <i data-lucide="dumbbell" class="me-3 text-warning" style="width: 20px;"></i>
                        Gestión Rutinas
                    </a>
                @elseif(auth()->user()->isStudent())
                    <!-- Student Mobile Links -->
                    <a href="{{ route('student.dashboard') }}" class="nav-link text-white py-3 px-3 rounded d-flex align-items-center {{ request()->routeIs('student.dashboard') ? 'bg-primary bg-opacity-25 text-primary border-start border-primary border-3 fw-bold' : '' }}">
                        <i data-lucide="home" class="me-3 text-warning" style="width: 20px;"></i>
                        Inicio
                    </a>
                    <a href="{{ route('student.attendances.index') }}" class="nav-link text-white py-3 px-3 rounded d-flex align-items-center {{ request()->routeIs('student.attendances.*') ? 'bg-primary bg-opacity-25 text-primary border-start border-primary border-3 fw-bold' : '' }}">
                        <i data-lucide="calendar-check" class="me-3 text-warning" style="width: 20px;"></i>
                        Mi Asistencia
                    </a>
                    <a href="{{ route('student.routine') }}" class="nav-link text-white py-3 px-3 rounded d-flex align-items-center {{ request()->routeIs('student.routine') ? 'bg-primary bg-opacity-25 text-primary border-start border-primary border-3 fw-bold' : '' }}">
                        <i data-lucide="dumbbell" class="me-3 text-warning" style="width: 20px;"></i>
                        Mi Rutina
                    </a>
                    <a href="{{ route('student.progress') }}" class="nav-link text-white py-3 px-3 rounded d-flex align-items-center {{ request()->routeIs('student.progress') ? 'bg-primary bg-opacity-25 text-primary border-start border-primary border-3 fw-bold' : '' }}">
                        <i data-lucide="trending-up" class="me-3 text-warning" style="width: 20px;"></i>
                        Progreso Físico
                    </a>
                    <a href="{{ route('student.weights.index') }}" class="nav-link text-white py-3 px-3 rounded d-flex align-items-center {{ request()->routeIs('student.weights.*') ? 'bg-primary bg-opacity-25 text-primary border-start border-primary border-3 fw-bold' : '' }}">
                        <i data-lucide="trophy" class="me-3 text-warning" style="width: 20px;"></i>
                        Pesos & Ranking
                    </a>
                @endif
            @endauth
        </nav>

        <!-- Logout Bottom (Mobile Offcanvas) -->
        <div class="pt-3 border-top border-secondary border-opacity-25">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100 border-0 text-start d-flex align-items-center px-3 py-3 rounded">
                    <i data-lucide="log-out" class="me-3" style="width: 20px;"></i>
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
</div>
