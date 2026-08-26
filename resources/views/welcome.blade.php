<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>GYM & DOJO LEGIONARIOS | Un Dojo, Un Espíritu</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        :root {
            --primary: #DC2626;
            --primary-dark: #991B1B;
            --gold: #D4AF37;
            --gold-light: #FBBF24;
            --dark: #07070A;
            --surface: #0E0F17;
            --surface-glass: rgba(18, 19, 28, 0.85);
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
                radial-gradient(circle at 10% 15%, rgba(220, 38, 38, 0.18) 0%, transparent 45%),
                radial-gradient(circle at 90% 85%, rgba(245, 158, 11, 0.12) 0%, transparent 45%);
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        /* Header Navigation */
        .header-nav {
            background: rgba(14, 15, 23, 0.9);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--border-glass);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        /* Glassmorphism Cards */
        .glass-card {
            background: var(--surface-glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border-glass);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6);
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }

        .glass-card:hover {
            border-color: rgba(212, 175, 55, 0.5);
            box-shadow: 0 20px 40px rgba(220, 38, 38, 0.2);
        }

        .pillar-card {
            background: linear-gradient(145deg, rgba(24, 25, 38, 0.9), rgba(14, 15, 23, 0.95));
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: 16px;
            height: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .pillar-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, var(--gold), var(--primary));
            opacity: 0.7;
        }

        .pillar-card:hover {
            transform: translateY(-5px);
            border-color: var(--gold);
            box-shadow: 0 10px 25px rgba(220, 38, 38, 0.3);
        }

        .pillar-number {
            font-family: 'Outfit', sans-serif;
            font-weight: 900;
            font-size: 1.25rem;
            color: var(--gold);
            background: rgba(212, 175, 55, 0.15);
            padding: 4px 12px;
            border-radius: 8px;
            display: inline-block;
        }

        /* Buttons */
        .btn-premium {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: 1px solid rgba(212, 175, 55, 0.4);
            border-radius: 14px;
            padding: 14px 28px;
            font-weight: 700;
            font-family: 'Outfit', sans-serif;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(220, 38, 38, 0.7);
            color: white;
            border-color: var(--gold);
        }

        .btn-gold-outline {
            border: 1px solid var(--gold);
            background: rgba(212, 175, 55, 0.08);
            color: var(--gold-light);
            border-radius: 14px;
            padding: 14px 28px;
            font-weight: 700;
            font-family: 'Outfit', sans-serif;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-gold-outline:hover {
            background: var(--gold);
            color: #000;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.4);
        }

        /* Discipline Badges */
        .discipline-badge {
            background: rgba(14, 15, 23, 0.8);
            border: 1px solid var(--gold);
            color: var(--gold-light);
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            padding: 6px 16px;
            border-radius: 30px;
            font-size: 13px;
            letter-spacing: 1px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
        }

        /* Step Card */
        .step-card {
            background: rgba(18, 19, 28, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 18px;
            transition: all 0.3s;
        }

        .step-card:hover {
            border-color: var(--primary);
            background: rgba(220, 38, 38, 0.08);
        }

        .step-number {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            font-weight: 800;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            box-shadow: 0 4px 10px rgba(220, 38, 38, 0.4);
        }
    </style>
</head>
<body>
    <!-- Top Navbar -->
    <nav class="header-nav py-3">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="/" class="d-flex align-items-center gap-3 text-decoration-none">
                <img src="{{ asset('images/logo-legionarios.jpg') }}" alt="GYM Legionarios" class="rounded-circle border border-warning shadow" style="width: 50px; height: 50px; object-fit: cover;">
                <div>
                    <span class="fs-4 fw-black font-heading text-white d-block lh-1">LEGIONARIOS</span>
                    <small class="text-warning text-uppercase font-heading fw-bold" style="font-size: 10px; letter-spacing: 2px;">Un Dojo, Un Espíritu</small>
                </div>
            </a>

            <div class="d-flex align-items-center gap-2">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-premium btn-sm px-4">
                            Mi Panel <i data-lucide="arrow-right" class="ms-1" style="width: 16px;"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-gold-outline btn-sm px-3">
                            Ingresar
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-premium btn-sm px-3 d-none d-sm-inline-block">
                                Registro
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="py-5 text-center position-relative">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="d-flex justify-content-center gap-3 mb-4">
                        <img src="{{ asset('images/logo-legionarios.jpg') }}" alt="Escudo Legionarios" class="rounded-circle border border-warning shadow-lg" style="width: 130px; height: 130px; object-fit: cover;">
                        <img src="{{ asset('images/logo-mma-gold.jpg') }}" alt="Legionarios MMA" class="rounded-circle border border-warning shadow-lg d-none d-sm-block" style="width: 130px; height: 130px; object-fit: cover;">
                    </div>

                    <h1 class="display-3 font-heading text-white fw-black mb-3">
                        DOJO & GYM <span class="text-danger">LEGIONARIOS</span>
                    </h1>
                    <p class="fs-4 text-warning font-heading fw-bold text-uppercase mb-3" style="letter-spacing: 2px;">
                        "UN DOJO, UN ESPÍRITU"
                    </p>
                    <p class="lead text-secondary mx-auto mb-4" style="max-width: 680px;">
                        Templa tu cuerpo y mente en la cuna de los verdaderos guerreros. Artes marciales de alto nivel, grappling, acondicionamiento físico y disciplina.
                    </p>

                    <!-- Disciplines Tags -->
                    <div class="d-flex flex-wrap justify-content-center gap-2 mb-5">
                        <span class="discipline-badge"><i data-lucide="swords" class="me-1 text-danger" style="width: 14px;"></i> GRAPPLING</span>
                        <span class="discipline-badge"><i data-lucide="shield" class="me-1 text-warning" style="width: 14px;"></i> NO-GI</span>
                        <span class="discipline-badge"><i data-lucide="flame" class="me-1 text-danger" style="width: 14px;"></i> BJJ (Jiu-Jitsu)</span>
                        <span class="discipline-badge"><i data-lucide="trophy" class="me-1 text-warning" style="width: 14px;"></i> MMA</span>
                        <span class="discipline-badge"><i data-lucide="dumbbell" class="me-1 text-danger" style="width: 14px;"></i> PERSONAL TRAINER</span>
                    </div>

                    <!-- Action CTA Buttons -->
                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                        <a href="{{ route('login') }}" class="btn btn-premium fs-5 px-5 py-3">
                            <i data-lucide="log-in" class="me-2" style="width: 22px;"></i> Acceder al Panel
                        </a>
                        <a href="#pilares" class="btn btn-gold-outline fs-5 px-4 py-3">
                            <i data-lucide="scroll" class="me-2" style="width: 22px;"></i> Conoce los 10 Pilares
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Section 1: Los 10 Pilares de la Familia Legionarios -->
    <section id="pilares" class="py-5 position-relative">
        <div class="container py-4">
            <div class="text-center mb-5">
                <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-50 px-3 py-2 text-uppercase font-heading mb-2">
                    Nuestra Filosofía de Vida y Equipo
                </span>
                <h2 class="display-5 text-white font-heading fw-bold mb-2">LOS 10 PILARES DE LA FAMILIA LEGIONARIOS</h2>
                <p class="text-secondary fs-5 mx-auto" style="max-width: 650px;">
                    Nuestros valores fundamentales que guían cada entrenamiento, cada combate y cada paso en el tatami.
                </p>
            </div>

            <div class="row g-4">
                <!-- Pilar I -->
                <div class="col-md-6 col-lg-4">
                    <div class="pillar-card p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="pillar-number">I</span>
                            <i data-lucide="award" class="text-warning" style="width: 24px;"></i>
                        </div>
                        <h5 class="text-white font-heading mb-2">HONOR Y LEALTAD</h5>
                        <p class="text-secondary small mb-0">Somos fieles a nuestro escudo y a nuestros compañeros de equipo.</p>
                    </div>
                </div>

                <!-- Pilar II -->
                <div class="col-md-6 col-lg-4">
                    <div class="pillar-card p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="pillar-number">II</span>
                            <i data-lucide="zap" class="text-danger" style="width: 24px;"></i>
                        </div>
                        <h5 class="text-white font-heading mb-2">DISCIPLINA SIN EXCUSAS</h5>
                        <p class="text-secondary small mb-0">La constancia vence a la inteligencia. Entrenamos con determinación diaria.</p>
                    </div>
                </div>

                <!-- Pilar III -->
                <div class="col-md-6 col-lg-4">
                    <div class="pillar-card p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="pillar-number">III</span>
                            <i data-lucide="heart" class="text-warning" style="width: 24px;"></i>
                        </div>
                        <h5 class="text-white font-heading mb-2">RESPETO UNIVERSAL</h5>
                        <p class="text-secondary small mb-0">Tratamos a los demás como queremos ser tratados dentro y fuera del tatami.</p>
                    </div>
                </div>

                <!-- Pilar IV -->
                <div class="col-md-6 col-lg-4">
                    <div class="pillar-card p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="pillar-number">IV</span>
                            <i data-lucide="book-open" class="text-danger" style="width: 24px;"></i>
                        </div>
                        <h5 class="text-white font-heading mb-2">HUMILDAD DEL GUERRERO</h5>
                        <p class="text-secondary small mb-0">Siempre somos alumnos, siempre aprendemos. De cada caída nace la maestría.</p>
                    </div>
                </div>

                <!-- Pilar V -->
                <div class="col-md-6 col-lg-4">
                    <div class="pillar-card p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="pillar-number">V</span>
                            <i data-lucide="users" class="text-warning" style="width: 24px;"></i>
                        </div>
                        <h5 class="text-white font-heading mb-2">CUIDADO DEL HERMANO</h5>
                        <p class="text-secondary small mb-0">El crecimiento de nuestros compañeros es también nuestro propio crecimiento.</p>
                    </div>
                </div>

                <!-- Pilar VI -->
                <div class="col-md-6 col-lg-4">
                    <div class="pillar-card p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="pillar-number">VI</span>
                            <i data-lucide="brain" class="text-danger" style="width: 24px;"></i>
                        </div>
                        <h5 class="text-white font-heading mb-2">FORTALEZA MENTAL</h5>
                        <p class="text-secondary small mb-0">Dominamos nuestra mente para que el cuerpo obedezca en cualquier adversidad.</p>
                    </div>
                </div>

                <!-- Pilar VII -->
                <div class="col-md-6 col-lg-4">
                    <div class="pillar-card p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="pillar-number">VII</span>
                            <i data-lucide="home" class="text-warning" style="width: 24px;"></i>
                        </div>
                        <h5 class="text-white font-heading mb-2">UNIÓN FAMILIAR</h5>
                        <p class="text-secondary small mb-0">El apoyo incondicional de nuestra familia es nuestro principal motor.</p>
                    </div>
                </div>

                <!-- Pilar VIII -->
                <div class="col-md-6 col-lg-4">
                    <div class="pillar-card p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="pillar-number">VIII</span>
                            <i data-lucide="shield-check" class="text-danger" style="width: 24px;"></i>
                        </div>
                        <h5 class="text-white font-heading mb-2">INTEGRIDAD DE CARÁCTER</h5>
                        <p class="text-secondary small mb-0">Somos personas de palabra, actuamos con absoluta honestidad y justicia.</p>
                    </div>
                </div>

                <!-- Pilar IX -->
                <div class="col-md-6 col-lg-4">
                    <div class="pillar-card p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="pillar-number">IX</span>
                            <i data-lucide="trending-up" class="text-warning" style="width: 24px;"></i>
                        </div>
                        <h5 class="text-white font-heading mb-2">ESPÍRITU DE SUPERACIÓN</h5>
                        <p class="text-secondary small mb-0">El único rival real a vencer cada día es la persona en el espejo.</p>
                    </div>
                </div>

                <!-- Pilar X -->
                <div class="col-md-12">
                    <div class="pillar-card p-4 text-center bg-danger bg-opacity-10 border-warning">
                        <div class="d-inline-block pillar-number mb-2">X</div>
                        <h4 class="text-warning font-heading mb-2">UNIDAD Y VICTORIA</h4>
                        <p class="text-white fs-5 mb-0 font-heading">
                            "Caemos juntos, nos levantamos unidos. ¡Un Dojo, Un Espíritu!"
                        </p>
                    </div>
                </div>
            </div>

            <!-- View Full Banner Graphic Button -->
            <div class="text-center mt-5">
                <button type="button" class="btn btn-gold-outline px-4" data-bs-toggle="modal" data-bs-target="#pilaresModal">
                    <i data-lucide="image" class="me-2" style="width: 18px;"></i> Ver Pergamino Oficial de los 10 Pilares
                </button>
            </div>
        </div>
    </section>

    <!-- Section 2: Checklist para tu Primera Semana -->
    <section class="py-5 bg-black bg-opacity-40">
        <div class="container py-4">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-50 px-3 py-2 text-uppercase font-heading mb-2">
                        Guía de Inicio para Alumnos
                    </span>
                    <h2 class="display-6 text-white font-heading fw-bold mb-4">
                        CHECKLIST PARA TU PRIMERA SEMANA 🥋
                    </h2>

                    <div class="d-flex flex-column gap-3">
                        <div class="step-card d-flex align-items-start gap-3">
                            <div class="step-number flex-shrink-0">1</div>
                            <div>
                                <h6 class="text-white mb-1 font-heading">Prepara tu Equipamiento</h6>
                                <small class="text-secondary">Viste ropa cómoda para moverte. Trae tus guantes, vendas y protector bucal. (¡Si ya tienes Gi, tráelo!).</small>
                            </div>
                        </div>

                        <div class="step-card d-flex align-items-start gap-3">
                            <div class="step-number flex-shrink-0">2</div>
                            <div>
                                <h6 class="text-white mb-1 font-heading">Confirma tu Horario</h6>
                                <small class="text-secondary">Verifica los horarios de clase y llega 10-15 minutos antes. ¡La puntualidad es honor!</small>
                            </div>
                        </div>

                        <div class="step-card d-flex align-items-start gap-3">
                            <div class="step-number flex-shrink-0">3</div>
                            <div>
                                <h6 class="text-white mb-1 font-heading">Hidratación y Energía</h6>
                                <small class="text-secondary">Mantente hidratado antes, durante y después del entrenamiento. ¡Tu cuerpo te lo agradecerá!</small>
                            </div>
                        </div>

                        <div class="step-card d-flex align-items-start gap-3">
                            <div class="step-number flex-shrink-0">4</div>
                            <div>
                                <h6 class="text-white mb-1 font-heading">Respeta el Tatami</h6>
                                <small class="text-secondary">Al tatami entramos descalzos. Deja tus zapatos organizados afuera. Es nuestro espacio sagrado.</small>
                            </div>
                        </div>

                        <div class="step-card d-flex align-items-start gap-3">
                            <div class="step-number flex-shrink-0">5</div>
                            <div>
                                <h6 class="text-white mb-1 font-heading">Aprende el Saludo & Respeta</h6>
                                <small class="text-secondary">Saludamos al instructor y a los compañeros. Es un símbolo de respeto universal.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 text-center">
                    <div class="glass-card p-3 p-md-4">
                        <img src="{{ asset('images/checklist-semana.jpg') }}" alt="Checklist Primera Semana" class="img-fluid rounded border border-warning shadow-lg mb-3">
                        <p class="text-warning font-heading fw-bold mb-0">¡VEN CON GANAS DE APRENDER!</p>
                        <small class="text-secondary">La actitud lo es todo. Aquí crecemos juntos y unidos. ¡Prepárate para superar tus límites!</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 3: Spartans Personal Trainer -->
    <section class="py-5">
        <div class="container py-4">
            <div class="glass-card p-4 p-md-5">
                <div class="row align-items-center g-4">
                    <div class="col-md-5 text-center">
                        <img src="{{ asset('images/personal-trainer-spartans.jpg') }}" alt="Spartans Personal Trainer" class="img-fluid rounded border border-warning shadow-lg" style="max-height: 380px; object-fit: contain;">
                    </div>
                    <div class="col-md-7">
                        <span class="badge bg-danger bg-opacity-25 text-danger border border-danger px-3 py-2 text-uppercase font-heading mb-2">
                            Asesoría Deportiva de Alto Rendimiento
                        </span>
                        <h3 class="display-6 text-white font-heading fw-bold mb-3">
                            SPARTANS FITNESS CENTER
                        </h3>
                        <h5 class="text-warning mb-3">MIGUEL MURUA — Personal Trainer & Asesoría</h5>
                        <p class="text-secondary lead mb-4">
                            Planes de entrenamiento personalizados, preparación física para artes marciales y musculación adaptada a tus objetivos específicos.
                        </p>
                        
                        <div class="d-flex gap-3">
                            <a href="{{ route('login') }}" class="btn btn-premium">
                                Comienza Tu Entrenamiento
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer & QR Notice -->
    <footer class="py-4 border-top border-secondary border-opacity-25 bg-black bg-opacity-60 text-center">
        <div class="container">
            <div class="d-flex flex-column align-items-center gap-2 mb-3">
                <img src="{{ asset('images/logo-legionarios.jpg') }}" alt="Logo" class="rounded-circle border border-warning" style="width: 40px; height: 40px; object-fit: cover;">
                <span class="fs-5 fw-bold font-heading text-white">GYM LEGIONARIOS</span>
                <small class="text-secondary">© {{ date('Y') }} Gym Legionarios — Un Dojo, Un Espíritu.</small>
            </div>

            <div class="d-inline-flex align-items-center gap-2 px-4 py-2 rounded-pill bg-dark border border-secondary border-opacity-25 text-secondary small">
                <i data-lucide="qr-code" class="text-warning" style="width: 18px;"></i>
                <span>Escanea el código QR en el acceso del gimnasio para registrar tu asistencia diaria</span>
            </div>
        </div>
    </footer>

    <!-- Modal for 10 Pilares Full Poster -->
    <div class="modal fade" id="pilaresModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content glass-card border-warning">
                <div class="modal-header border-bottom border-warning border-opacity-25">
                    <h5 class="modal-title text-warning font-heading fw-bold">Los 10 Pilares de la Familia Legionarios</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-3">
                    <img src="{{ asset('images/10-pilares.jpg') }}" alt="10 Pilares Artwork" class="img-fluid rounded border border-secondary shadow-lg">
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
