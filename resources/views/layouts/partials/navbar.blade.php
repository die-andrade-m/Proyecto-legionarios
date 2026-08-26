<nav class="navbar navbar-expand-lg navbar-dark bg-transparent px-0 py-2 py-md-3 mb-2">
    <div class="container-fluid px-0">
        <!-- Mobile Header (Visible only on screens < 992px) -->
        <div class="d-flex align-items-center d-lg-none w-100 justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <!-- Hamburger Button for Offcanvas Drawer -->
                <button class="btn btn-outline-warning btn-sm p-2 border-opacity-50 text-warning d-flex align-items-center justify-content-center" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar" aria-label="Abrir Menú">
                    <i data-lucide="menu" style="width: 22px; height: 22px;"></i>
                </button>

                <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                    <img src="{{ asset('images/logo-legionarios.jpg') }}" alt="GYM Legionarios" class="rounded-circle border border-warning me-2 shadow-sm" style="width: 34px; height: 34px; object-fit: cover;">
                    <div>
                        <span class="fs-6 fw-bold font-heading text-white d-block lh-1">LEGIONARIOS</span>
                        <small class="text-warning text-uppercase" style="font-size: 8px; letter-spacing: 1px;">Un Dojo, Un Espíritu</small>
                    </div>
                </a>
            </div>

            @auth
                <div class="dropdown">
                    <button class="btn bg-transparent border-0 p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ auth()->user()->avatar_url }}" alt="Profile" class="rounded-circle shadow-sm" style="width: 38px; height: 38px; object-fit: cover; border: 2px solid var(--primary);">
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end glass-card border-0 p-2 mt-2 shadow-lg" style="background-color: var(--surface-light)">
                        <li>
                            <div class="px-3 py-2 border-bottom border-secondary border-opacity-25 mb-1">
                                <p class="mb-0 fw-bold text-white small text-truncate" style="max-width: 160px;">{{ auth()->user()->name }}</p>
                                <span class="badge bg-primary bg-opacity-25 text-primary text-capitalize" style="font-size: 10px;">{{ auth()->user()->getPrimaryRole() }}</span>
                            </div>
                        </li>
                        <li>
                            <a class="dropdown-item text-white d-flex align-items-center py-2 px-3 rounded" href="{{ route('dashboard') }}">
                                <i data-lucide="layout-dashboard" class="me-2 text-warning" style="width: 16px;"></i>
                                Mi Panel
                            </a>
                        </li>
                        <li><hr class="dropdown-divider border-secondary border-opacity-25"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger d-flex align-items-center py-2 px-3 rounded">
                                    <i data-lucide="log-out" class="me-2" style="width: 16px;"></i>
                                    Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth
        </div>

        <!-- Desktop Title/Welcome (Hidden on mobile) -->
        <div class="d-none d-lg-flex w-100 justify-content-between align-items-center">
            <div>
                <h4 class="mb-0 text-white font-heading">
                    @auth
                        @if(auth()->user()->isAdmin())
                            Panel de Administración
                        @elseif(auth()->user()->isTrainer())
                            Panel de Control
                        @else
                            ¡Hola, {{ explode(' ', auth()->user()->name)[0] }}! ⚡
                        @endif
                    @endauth
                </h4>
                <small class="text-secondary">Plataforma GYM Legionarios</small>
            </div>
            
            @auth
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-primary bg-opacity-15 text-primary border border-primary border-opacity-25 px-3 py-2 rounded-pill font-heading text-capitalize" style="font-size: 13px;">
                        Rol: {{ auth()->user()->getPrimaryRole() }}
                    </span>
                    <span class="text-secondary small">{{ now()->translatedFormat('l, d \d\e F Y') }}</span>
                </div>
            @endauth
        </div>
    </div>
</nav>
