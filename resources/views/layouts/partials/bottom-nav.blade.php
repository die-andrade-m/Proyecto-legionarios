@auth
    <nav class="bottom-nav d-lg-none d-flex">
        @if(auth()->user()->isStudent())
            <!-- Student Mobile Nav -->
            <a href="{{ route('student.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                <i data-lucide="home" style="width: 20px; height: 20px;"></i>
                <span>Inicio</span>
            </a>
            <a href="{{ route('student.routine') }}" class="bottom-nav-item {{ request()->routeIs('student.routine') ? 'active' : '' }}">
                <i data-lucide="dumbbell" style="width: 20px; height: 20px;"></i>
                <span>Rutina</span>
            </a>
            <a href="{{ route('student.progress') }}" class="bottom-nav-item {{ request()->routeIs('student.progress') ? 'active' : '' }}">
                <i data-lucide="trending-up" style="width: 20px; height: 20px;"></i>
                <span>Progreso</span>
            </a>
            <a href="{{ route('student.weights.index') }}" class="bottom-nav-item {{ request()->routeIs('student.weights.*') ? 'active' : '' }}">
                <i data-lucide="trophy" style="width: 20px; height: 20px;"></i>
                <span>Ranking</span>
            </a>
            <button type="button" class="bottom-nav-item bg-transparent border-0" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                <i data-lucide="menu" style="width: 20px; height: 20px;"></i>
                <span>Menú</span>
            </button>
        @elseif(auth()->user()->isTrainer())
            <!-- Trainer Mobile Nav -->
            <a href="{{ route('trainer.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('trainer.dashboard') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard" style="width: 20px; height: 20px;"></i>
                <span>Panel</span>
            </a>
            <a href="{{ route('trainer.students.index') }}" class="bottom-nav-item {{ request()->routeIs('trainer.students.*') ? 'active' : '' }}">
                <i data-lucide="users" style="width: 20px; height: 20px;"></i>
                <span>Alumnos</span>
            </a>
            <a href="{{ route('trainer.routines.index') }}" class="bottom-nav-item {{ request()->routeIs('trainer.routines.*') ? 'active' : '' }}">
                <i data-lucide="dumbbell" style="width: 20px; height: 20px;"></i>
                <span>Rutinas</span>
            </a>
            <button type="button" class="bottom-nav-item bg-transparent border-0" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                <i data-lucide="menu" style="width: 20px; height: 20px;"></i>
                <span>Menú</span>
            </button>
        @elseif(auth()->user()->isAdmin())
            <!-- Admin Mobile Nav -->
            <a href="{{ route('admin.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard" style="width: 20px; height: 20px;"></i>
                <span>Panel</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i data-lucide="users" style="width: 20px; height: 20px;"></i>
                <span>Usuarios</span>
            </a>
            <button type="button" class="bottom-nav-item bg-transparent border-0" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                <i data-lucide="menu" style="width: 20px; height: 20px;"></i>
                <span>Menú</span>
            </button>
        @endif
    </nav>
@endauth
