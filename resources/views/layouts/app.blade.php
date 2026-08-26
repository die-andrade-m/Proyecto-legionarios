<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'GYM Legionarios') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 (Dark Theme Base) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom CSS (Premium Dark Glassmorphism) -->
    <style>
        :root {
            --primary: #DC2626;
            --primary-rgb: 220, 38, 38;
            --primary-dark: #991B1B;
            --secondary: #F59E0B;
            --secondary-rgb: 245, 158, 11;
            --gold: #D4AF37;
            --gold-light: #FBBF24;
            --success: #22C55E;
            --warning: #F59E0B;
            --danger: #EF4444;
            --dark: #0A0A0E;
            --surface: #12131C;
            --surface-glass: rgba(18, 19, 28, 0.85);
            --surface-light: #1C1D2A;
            --border-glass: rgba(212, 175, 55, 0.25);
            --border-red: rgba(220, 38, 38, 0.35);
            --text-primary: #F8FAFC;
            --text-secondary: #94A3B8;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--dark);
            color: var(--text-primary);
            overflow-x: hidden;
            padding-bottom: 75px; /* Spacing for bottom navigation on mobile */
            background-image: 
                radial-gradient(circle at 15% 15%, rgba(220, 38, 38, 0.12) 0%, transparent 45%),
                radial-gradient(circle at 85% 85%, rgba(245, 158, 11, 0.08) 0%, transparent 45%);
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        /* Glassmorphism Card Style with Gold accent border */
        .glass-card {
            background: var(--surface-glass);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-glass);
            border-radius: 18px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.45);
        }

        /* Sidebar Style for Desktop */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            background-color: var(--surface);
            border-right: 1px solid var(--border-glass);
            padding: 1.5rem;
            transition: all 0.3s;
        }

        /* Main Content wrapper */
        .main-wrapper {
            margin-left: 260px;
            padding: 2rem;
            min-height: 100vh;
        }

        /* Bottom Nav Bar for Mobile */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 65px;
            background: rgba(18, 18, 31, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-top: 1px solid var(--border-glass);
            z-index: 1000;
            display: none;
        }

        .bottom-nav-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 11px;
            font-weight: 500;
            transition: color 0.2s;
        }

        .bottom-nav-item.active {
            color: var(--primary);
        }

        .bottom-nav-item i {
            margin-bottom: 3px;
        }

        /* custom buttons */
        .btn-premium {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(108, 60, 247, 0.4);
        }

        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(108, 60, 247, 0.6);
            color: white;
        }

        .btn-pulse {
            animation: pulse-animation 2s infinite;
        }

        @keyframes pulse-animation {
            0% {
                box-shadow: 0 0 0 0 rgba(108, 60, 247, 0.7);
            }
            70% {
                box-shadow: 0 0 0 15px rgba(108, 60, 247, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(108, 60, 247, 0);
            }
        }

        /* Achievements / Gamification Badges */
        .badge-achievement {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin: 0 auto 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            transition: transform 0.3s;
        }

        .badge-achievement:hover {
            transform: scale(1.15) rotate(5deg);
        }

        /* Stats visual number fonts */
        .stat-value {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
        }

        /* Responsive Breakpoints */
        @media (max-width: 991.98px) {
            .sidebar {
                display: none;
            }
            .main-wrapper {
                margin-left: 0;
                padding: 1rem;
            }
            .bottom-nav {
                display: flex;
            }
            body {
                padding-bottom: 80px;
            }
        }

        @media (min-width: 992px) {
            body {
                padding-bottom: 0;
            }
        }
    </style>
</head>
<body class="h-full">
    <!-- Desktop Sidebar -->
    @include('layouts.partials.sidebar')

    <!-- Main Content Area -->
    <div class="main-wrapper">
        <!-- Top Navbar (Desktop/Mobile Header) -->
        @include('layouts.partials.navbar')

        <!-- Alerts wrapper -->
        <div class="container-fluid mt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 glass-card bg-success bg-opacity-25 text-white" role="alert">
                    <div class="d-flex align-items-center">
                        <i data-lucide="check-circle" class="me-2 text-success"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 glass-card bg-danger bg-opacity-25 text-white" role="alert">
                    <div class="d-flex align-items-center">
                        <i data-lucide="alert-triangle" class="me-2 text-danger"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 glass-card bg-danger bg-opacity-25 text-white" role="alert">
                    <div class="d-flex align-items-center">
                        <i data-lucide="alert-circle" class="me-2 text-danger"></i>
                        <div>
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <main class="container-fluid py-4">
            @yield('content')
        </main>
    </div>

    <!-- Mobile Bottom Navigation -->
    @include('layouts.partials.bottom-nav')

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize all Lucide icons
        lucide.createIcons();
    </script>
    @yield('scripts')
</body>
</html>
