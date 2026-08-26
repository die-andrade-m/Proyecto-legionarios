<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'GYM Legionarios') }}</title>

    <!-- DNS Preconnect & Preload for High Speed -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="https://unpkg.com">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 (Dark Theme Base) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom CSS (Premium Dark Glassmorphism + Mobile-First) -->
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
            --surface-glass: rgba(18, 19, 28, 0.88);
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
            background-image: 
                radial-gradient(circle at 15% 15%, rgba(220, 38, 38, 0.12) 0%, transparent 45%),
                radial-gradient(circle at 85% 85%, rgba(245, 158, 11, 0.08) 0%, transparent 45%);
            -webkit-tap-highlight-color: transparent;
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        /* Glassmorphism Card Style */
        .glass-card {
            background: var(--surface-glass);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-glass);
            border-radius: 16px;
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
            padding: 1.5rem 2rem;
            min-height: 100vh;
        }

        /* Bottom Nav Bar for Mobile */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: calc(62px + env(safe-area-inset-bottom, 0px));
            padding-bottom: env(safe-area-inset-bottom, 0px);
            background: rgba(14, 15, 23, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-top: 1px solid var(--border-glass);
            z-index: 1000;
            display: none;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.5);
        }

        .bottom-nav-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 10px;
            font-weight: 600;
            transition: all 0.2s ease;
            padding: 6px 2px;
            touch-action: manipulation;
        }

        .bottom-nav-item.active {
            color: var(--gold-light);
        }

        .bottom-nav-item.active i {
            color: var(--primary);
            transform: scale(1.1);
        }

        .bottom-nav-item i {
            margin-bottom: 2px;
            transition: transform 0.2s ease;
        }

        /* Custom buttons */
        .btn-premium {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 700;
            font-family: 'Outfit', sans-serif;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.4);
            letter-spacing: 0.3px;
        }

        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.6);
            color: white;
            border-color: var(--gold);
        }

        .btn-pulse {
            animation: pulse-animation 2s infinite;
        }

        @keyframes pulse-animation {
            0% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7); }
            70% { box-shadow: 0 0 0 12px rgba(220, 38, 38, 0); }
            100% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); }
        }

        /* Form Inputs Mobile Friendly (Prevent zoom on iOS) */
        .form-control, .form-select {
            background-color: rgba(10, 10, 14, 0.85) !important;
            border: 1px solid rgba(212, 175, 55, 0.25) !important;
            color: #F8FAFC !important;
            padding: 10px 14px !important;
            border-radius: 10px !important;
            font-size: 16px !important; /* Critical for iOS: >=16px prevents unwanted zoom */
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.25) !important;
        }

        /* Stats visual number fonts */
        .stat-value {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
        }

        /* Mobile Breakpoints & Responsive Tweaks */
        @media (max-width: 991.98px) {
            .sidebar {
                display: none !important;
            }
            .main-wrapper {
                margin-left: 0 !important;
                padding: 0.75rem 0.75rem !important;
            }
            .bottom-nav {
                display: flex !important;
            }
            body {
                padding-bottom: calc(72px + env(safe-area-inset-bottom, 0px));
            }
            .glass-card {
                padding: 1rem !important;
                border-radius: 14px !important;
            }
            .display-5 {
                font-size: 1.75rem !important;
            }
            .display-4 {
                font-size: 2rem !important;
            }
            .stat-value {
                font-size: 1.35rem !important;
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
    <!-- Sidebar Partial (Handles both Desktop fixed & Mobile offcanvas) -->
    @include('layouts.partials.sidebar')

    <!-- Main Content Area -->
    <div class="main-wrapper">
        <!-- Top Navbar (Desktop/Mobile Header) -->
        @include('layouts.partials.navbar')

        <!-- Alerts wrapper -->
        <div class="container-fluid px-0 mt-2">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 glass-card bg-success bg-opacity-25 text-white py-2 px-3 mb-3" role="alert">
                    <div class="d-flex align-items-center">
                        <i data-lucide="check-circle" class="me-2 text-success" style="width: 18px; height: 18px;"></i>
                        <div class="small fw-semibold">{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close" style="padding: 0.75rem;"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 glass-card bg-danger bg-opacity-25 text-white py-2 px-3 mb-3" role="alert">
                    <div class="d-flex align-items-center">
                        <i data-lucide="alert-triangle" class="me-2 text-danger" style="width: 18px; height: 18px;"></i>
                        <div class="small fw-semibold">{{ session('error') }}</div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close" style="padding: 0.75rem;"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 glass-card bg-danger bg-opacity-25 text-white py-2 px-3 mb-3" role="alert">
                    <div class="d-flex align-items-center">
                        <i data-lucide="alert-circle" class="me-2 text-danger" style="width: 18px; height: 18px;"></i>
                        <div class="small">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close" style="padding: 0.75rem;"></button>
                </div>
            @endif
        </div>

        <main class="container-fluid px-0 py-2 py-md-3">
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
