<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'GYM Legionarios') }}</title>

    <!-- DNS Preconnect for Performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800;900&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #DC2626;
            --primary-dark: #991B1B;
            --gold: #D4AF37;
            --gold-light: #FBBF24;
            --dark: #0A0A0E;
            --surface-glass: rgba(18, 19, 28, 0.9);
            --border-glass: rgba(212, 175, 55, 0.3);
            --text-primary: #F8FAFC;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--dark);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(220, 38, 38, 0.15) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(245, 158, 11, 0.1) 0%, transparent 40%);
            -webkit-tap-highlight-color: transparent;
        }

        .auth-card {
            background: var(--surface-glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border-glass);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6);
            max-width: 440px;
            width: 100%;
        }

        h1, h2, h3, h4, h5 {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
        }

        /* Input fields with iOS zoom prevention (16px) */
        input[type="email"],
        input[type="password"],
        input[type="text"] {
            background-color: rgba(10, 10, 14, 0.85) !important;
            border: 1px solid rgba(212, 175, 55, 0.25) !important;
            color: #F8FAFC !important;
            padding: 12px 14px !important;
            border-radius: 10px !important;
            font-size: 16px !important;
            transition: all 0.3s;
        }

        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="text"]:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.3) !important;
        }

        .btn-premium {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: 1px solid rgba(212, 175, 55, 0.4);
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 700;
            font-family: 'Outfit', sans-serif;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.4);
            letter-spacing: 0.5px;
            touch-action: manipulation;
        }

        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.6);
            color: white;
            border-color: var(--gold);
        }

        .text-link {
            color: var(--gold);
            text-decoration: none;
            font-size: 13px;
        }

        .text-link:hover {
            text-decoration: underline;
            color: #FBBF24;
        }

        @media (max-width: 575.98px) {
            .auth-card {
                padding: 1.5rem 1.25rem !important;
                border-radius: 16px !important;
            }
        }
    </style>
</head>
<body>
    <div class="container p-3 d-flex justify-content-center">
        <div class="auth-card p-4 p-md-5">
            <!-- App Logo Link -->
            <div class="text-center mb-4">
                <a href="/" class="text-decoration-none text-white d-inline-flex flex-column align-items-center gap-1">
                    <img src="{{ asset('images/logo-legionarios.jpg') }}" alt="GYM Legionarios" class="rounded-circle border border-warning shadow" style="width: 64px; height: 64px; object-fit: cover;">
                    <span class="fs-4 fw-bold font-heading text-white tracking-tight">GYM LEGIONARIOS</span>
                    <small class="text-warning text-uppercase fw-semibold" style="font-size: 10px; letter-spacing: 2px;">Un Dojo, Un Espíritu</small>
                </a>
            </div>

            <!-- Content Slot -->
            {{ $slot }}
        </div>
    </div>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
