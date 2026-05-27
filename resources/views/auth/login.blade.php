<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduStream - Iniciar sesion</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/css/edustream.css" rel="stylesheet">
    <style>
    /* ── Layout hero ── */
    html, body { height: 100%; }

    .login-page {
        display: flex;
        min-height: 100vh;
        font-family: 'Inter', sans-serif;
    }

    /* ── Panel izquierdo (hero) ── */
    .login-hero {
        flex: 1;
        background: var(--azul-navy);
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 56px 52px;
        position: relative;
        overflow: hidden;
    }

    /* Decoracion de fondo: circulos difusos */
    .login-hero::before {
        content: '';
        position: absolute;
        top: -80px; right: -80px;
        width: 340px; height: 340px;
        border-radius: 50%;
        background: rgba(55,138,221,0.12);
        pointer-events: none;
    }
    .login-hero::after {
        content: '';
        position: absolute;
        bottom: -60px; left: -60px;
        width: 260px; height: 260px;
        border-radius: 50%;
        background: rgba(55,138,221,0.08);
        pointer-events: none;
    }

    .hero-logo {
        font-size: 28px;
        font-weight: 800;
        color: #fff;
        text-decoration: none;
        margin-bottom: 36px;
        display: inline-block;
    }

    .hero-tag {
        display: flex;
        align-items: center;
        gap: 6px;
        color: rgba(255,255,255,0.45);
        font-size: 12px;
        font-weight: 500;
        letter-spacing: 0.5px;
        margin-bottom: 18px;
    }

    .hero-title {
        font-size: 34px;
        font-weight: 800;
        color: #fff;
        line-height: 1.2;
        margin-bottom: 16px;
    }

    .hero-title span { color: #378ADD; }

    .hero-desc {
        color: rgba(255,255,255,0.6);
        font-size: 15px;
        line-height: 1.6;
        margin-bottom: 36px;
        max-width: 380px;
    }

    .hero-features {
        list-style: none;
        padding: 0; margin: 0;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .hero-features li {
        display: flex;
        align-items: center;
        gap: 10px;
        color: rgba(255,255,255,0.75);
        font-size: 14px;
        font-weight: 500;
    }

    .hero-features li i {
        font-size: 18px;
        color: #378ADD;
        flex-shrink: 0;
    }

    /* ── Panel derecho (formulario) ── */
    .login-form-panel {
        width: 440px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 48px 40px;
        background: #F0F4F8;
    }

    .form-card {
        width: 100%;
        max-width: 360px;
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        padding: 32px;
        box-shadow: 0 2px 12px rgba(10,41,73,0.06);
    }

    .form-card h5 {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    /* ── Responsive movil ── */
    @media (max-width: 767px) {
        .login-page    { flex-direction: column; }
        .login-hero    { padding: 28px 24px 24px; flex: none; min-height: auto; }
        .login-hero::before, .login-hero::after { display: none; }
        .hero-title    { font-size: 22px; }
        .hero-desc     { display: none; }
        .hero-features { display: none; }
        .hero-tag      { margin-bottom: 10px; }
        .login-form-panel { width: 100%; padding: 24px 16px 32px; }
        .form-card     { padding: 24px; }
    }
    </style>
</head>

<body>
<div class="login-page">

    {{-- ═══ PANEL IZQUIERDO — HERO ═══ --}}
    <div class="login-hero">
        <a href="/" class="hero-logo">EduStream</a>

        <div class="hero-tag">
            <i class="bi bi-broadcast"></i>
            Sistema de distribución de contenido educativo
        </div>

        <h1 class="hero-title">
            Aprende y comparte<br>
            <span>sin límites</span>
        </h1>

        <p class="hero-desc">
            EduStream distribuye el contenido educativo entre los propios estudiantes,
            reduciendo la carga del servidor y acelerando la entrega de cada recurso.
        </p>

        <ul class="hero-features">
            <li>
                <i class="bi bi-play-circle-fill"></i>
                Video con aceleración P2P entre peers
            </li>
            <li>
                <i class="bi bi-collection-play-fill"></i>
                Organizado en canales por materia
            </li>
            <li>
                <i class="bi bi-people-fill"></i>
                Roles de estudiante, maestro y admin
            </li>
            <li>
                <i class="bi bi-phone-fill"></i>
                Accesible desde cualquier dispositivo
            </li>
        </ul>
    </div>

    {{-- ═══ PANEL DERECHO — FORM ═══ --}}
    <div class="login-form-panel">
        <div class="form-card">
            <h5 class="fw-bold mb-1">Bienvenido</h5>
            <p class="text-muted mb-4" style="font-size:13px">Ingresa a tu cuenta de EduStream</p>

            {{-- Errores --}}
            @if ($errors->any())
                <div class="alert alert-danger" style="border-radius:8px; font-size:13px">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold" style="font-size:13px">Email</label>
                    <input type="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" autocomplete="email" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold" style="font-size:13px">Contraseña</label>
                    <input type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           autocomplete="current-password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn w-100 mb-3"
                        style="background-color:#378ADD; color:#fff; border-radius:8px; font-weight:600">
                    Iniciar sesion
                </button>
                <div class="text-center" style="font-size:13px">
                    <span class="text-muted">¿No tienes cuenta?</span>
                    <a href="{{ route('registro') }}" class="text-decoration-none fw-bold"
                       style="color:#378ADD"> Registrate</a>
                </div>
            </form>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

    // HERO: panel izquierdo
    tl.from('.login-hero', { opacity: 0, x: -30, duration: 0.5 }, 0)
      .from('.hero-logo',  { opacity: 0, y: -12, duration: 0.4 }, 0.15)
      .from('.hero-tag',   { opacity: 0, x: -10, duration: 0.35 }, 0.25)
      .from('.hero-title', { opacity: 0, y: 14, duration: 0.4 }, 0.3)
      .from('.hero-desc',  { opacity: 0, y: 10, duration: 0.35 }, 0.4)
      .from('.hero-features li', {
          opacity: 0, x: -10, duration: 0.3,
          stagger: 0.07
      }, 0.48);

    // FORM: panel derecho entra desde la derecha simultaneamente
    gsap.from('.login-form-panel', { opacity: 0, x: 30, duration: 0.5, ease: 'power3.out', delay: 0.1 });
});
</script>
</body>
</html>
