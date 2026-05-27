<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduStream - Crear cuenta</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/css/edustream.css" rel="stylesheet">
    <!-- GSAP -->
    <script src="https://cdn.jsdelivr.net/npm/gsap@3/dist/gsap.min.js"></script>
    <style>
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
        width: 460px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 40px 40px;
        background: #F0F4F8;
        overflow-y: auto;
    }

    .form-card {
        width: 100%;
        max-width: 380px;
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        padding: 28px 32px;
        box-shadow: 0 2px 12px rgba(10,41,73,0.06);
    }

    /* Rol selector como botones tipo toggle */
    .rol-selector {
        display: flex;
        gap: 8px;
        margin-top: 4px;
    }

    .rol-btn {
        flex: 1;
        position: relative;
    }

    .rol-btn input[type="radio"] { display: none; }

    .rol-btn label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 8px 12px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 500;
        color: #666;
        transition: border-color 0.15s ease, background 0.15s ease, color 0.15s ease;
        width: 100%;
    }

    .rol-btn input:checked + label {
        border-color: #378ADD;
        background: #E6F1FB;
        color: #0A2949;
    }

    /* ── Responsive movil ── */
    @media (max-width: 767px) {
        .login-page        { flex-direction: column; }
        .login-hero        { padding: 28px 24px 24px; flex: none; min-height: auto; }
        .login-hero::before, .login-hero::after { display: none; }
        .hero-title        { font-size: 22px; }
        .hero-desc         { display: none; }
        .hero-features     { display: none; }
        .hero-tag          { margin-bottom: 10px; }
        .login-form-panel  { width: 100%; padding: 24px 16px 32px; }
        .form-card         { padding: 24px; }
    }
    </style>
</head>

<body>
<div class="login-page">

    {{-- ═══ PANEL IZQUIERDO — HERO ═══ --}}
    <div class="login-hero">
        <a href="/" class="hero-logo">EduStream</a>

        <div class="hero-tag">
            <i class="bi bi-person-plus-fill"></i>
            Sistema de distribución de contenido educativo
        </div>

        <h1 class="hero-title">
            Crea tu cuenta.<br>
            <span>Empieza a aprender.</span>
        </h1>

        <p class="hero-desc">
            Regístrate y accede a todos los recursos de tu institución,
            compartidos y distribuidos entre la comunidad estudiantil.
        </p>

        <ul class="hero-features">
            <li>
                <i class="bi bi-collection-play-fill"></i>
                Accede a recursos de todas las materias
            </li>
            <li>
                <i class="bi bi-broadcast"></i>
                Ayuda a otros al compartir contenido P2P
            </li>
            <li>
                <i class="bi bi-cloud-upload-fill"></i>
                Sube y comparte tus propios materiales
            </li>
            <li>
                <i class="bi bi-phone-fill"></i>
                Disponible desde cualquier dispositivo
            </li>
        </ul>
    </div>

    {{-- ═══ PANEL DERECHO — FORM ═══ --}}
    <div class="login-form-panel">
        <div class="form-card">
            <h5 class="fw-bold mb-1">Crear cuenta</h5>
            <p class="text-muted mb-4" style="font-size:13px">Únete a EduStream en segundos</p>

            {{-- Errores --}}
            @if($errors->any())
                <div class="alert alert-danger" style="border-radius:8px; font-size:13px">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('registro.post') }}" method="POST">
                @csrf

                {{-- Nombre --}}
                <div class="mb-3">
                    <label class="form-label fw-bold" style="font-size:13px">Nombre</label>
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" placeholder="Tu nombre completo" autocomplete="name" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label class="form-label fw-bold" style="font-size:13px">Email</label>
                    <input type="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="tucorreo@ejemplo.com" autocomplete="email" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Contraseña --}}
                <div class="mb-3">
                    <label class="form-label fw-bold" style="font-size:13px">Contraseña</label>
                    <input type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Mínimo 8 caracteres" autocomplete="new-password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Confirmar --}}
                <div class="mb-3">
                    <label class="form-label fw-bold" style="font-size:13px">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation"
                           class="form-control"
                           placeholder="Repite tu contraseña" autocomplete="new-password" required>
                </div>

                {{-- Rol — botones tipo toggle ── --}}
                <div class="mb-4">
                    <label class="form-label fw-bold" style="font-size:13px">Soy...</label>
                    <div class="rol-selector">
                        <div class="rol-btn">
                            <input type="radio" name="rol" id="estudiante" value="estudiante"
                                   {{ old('rol', 'estudiante') == 'estudiante' ? 'checked' : '' }} required>
                            <label for="estudiante">
                                <i class="bi bi-mortarboard-fill"></i> Estudiante
                            </label>
                        </div>
                        <div class="rol-btn">
                            <input type="radio" name="rol" id="maestro" value="maestro"
                                   {{ old('rol') == 'maestro' ? 'checked' : '' }}>
                            <label for="maestro">
                                <i class="bi bi-person-workspace"></i> Maestro
                            </label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn w-100 mb-3"
                        style="background-color:#378ADD; color:#fff; border-radius:8px; font-weight:600">
                    Crear cuenta
                </button>

                <div class="text-center" style="font-size:13px">
                    <span class="text-muted">¿Ya tienes cuenta?</span>
                    <a href="{{ route('login') }}" class="text-decoration-none fw-bold"
                       style="color:#378ADD"> Inicia sesión</a>
                </div>
            </form>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

    tl.from('.login-hero', { opacity: 0, x: -30, duration: 0.5 }, 0)
      .from('.hero-logo',  { opacity: 0, y: -12, duration: 0.4 }, 0.15)
      .from('.hero-tag',   { opacity: 0, x: -10, duration: 0.35 }, 0.25)
      .from('.hero-title', { opacity: 0, y: 14,  duration: 0.4 }, 0.3)
      .from('.hero-desc',  { opacity: 0, y: 10,  duration: 0.35 }, 0.4)
      .from('.hero-features li', {
          opacity: 0, x: -10, duration: 0.3, stagger: 0.07
      }, 0.48);

    gsap.from('.login-form-panel', {
        opacity: 0, x: 30, duration: 0.5, ease: 'power3.out', delay: 0.1
    });
});
</script>
</body>
</html>