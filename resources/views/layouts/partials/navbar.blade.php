<nav class="navbar navbar-expand-lg navbar-dark bg-transparent px-0 py-3 mb-2">
    <div class="container-fluid px-0">
        <!-- Mobile Logo Header (Only visible on mobile) -->
        <div class="d-flex align-items-center d-lg-none w-100 justify-content-between">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                <img src="{{ asset('images/logo-legionarios.jpg') }}" alt="GYM Legionarios" class="rounded-circle border border-warning me-2" style="width: 32px; height: 32px; object-fit: cover;">
                <span class="fs-5 fw-bold font-heading text-white">LEGIONARIOS</span>
            </a>

            @auth
                <div class="dropdown">
                    <button class="btn bg-transparent border-0 p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ auth()->user()->avatar_url }}" alt="Profile" class="rounded-circle" style="width: 38px; height: 38px; object-fit: cover; border: 2px solid var(--primary);">
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end glass-card border-0 p-2 mt-2" style="background-color: var(--surface-light)">
                        <li>
                            <div class="px-3 py-2 border-bottom border-secondary border-opacity-25 mb-1">
                                <p class="mb-0 fw-bold text-white small text-truncate" style="max-width: 150px;">{{ auth()->user()->name }}</p>
                                <span class="badge bg-primary bg-opacity-25 text-primary text-capitalize" style="font-size: 10px;">{{ auth()->user()->getPrimaryRole() }}</span>
                            </div>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger d-flex align-items-center py-2 px-3">
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
                <h4 class="mb-0 text-white">
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
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill font-heading text-capitalize" style="font-size: 13px;">
                        Rol: {{ auth()->user()->getPrimaryRole() }}
                    </span>
                    <span class="text-secondary small">{{ now()->translatedFormat('l, d \d\e F Y') }}</span>
                </div>
            @endauth
        </div>
    </div>
</nav>
