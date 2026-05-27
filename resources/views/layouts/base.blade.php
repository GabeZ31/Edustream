<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduStream</title>

    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/css/edustream.css" rel="stylesheet">
    <!-- GSAP -->
    <script src="https://cdn.jsdelivr.net/npm/gsap@3/dist/gsap.min.js"></script>
</head>

<body>

@include('partials.navbar')

{{-- SIDEBAR DE CANALES (desktop) --}}
@php
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->rol === 'admin') {
            $canalesNav = \App\Models\Canal::all();
        } elseif ($user->rol === 'maestro') {
            $propiosIds = \App\Models\Canal::where('maestro_id', $user->id)->pluck('id');
            $suscritosIds = \App\Models\Inscripcion::where('user_id', $user->id)->pluck('canal_id');
            $todosIds = $propiosIds->merge($suscritosIds)->unique();
            $canalesNav = \App\Models\Canal::whereIn('id', $todosIds)->get();
        } else {
            // Estudiante
            $suscritosIds = \App\Models\Inscripcion::where('user_id', $user->id)->pluck('canal_id');
            $canalesNav = \App\Models\Canal::whereIn('id', $suscritosIds)->get();
        }
    } else {
        $canalesNav = \App\Models\Canal::all();
    }
@endphp

<div id="canal-sidebar" class="canal-sidebar d-none d-md-flex flex-column justify-content-between pb-3">
    <div>
        <div class="sidebar-section-title">CANALES</div>

        <a href="/" class="sidebar-item {{ !request('canal') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i>
            <span>Todos</span>
        </a>

        @forelse($canalesNav as $c)
            <a href="/?canal={{ $c->id }}"
               class="sidebar-item {{ request('canal') == $c->id ? 'active' : '' }}">
                <i class="bi bi-collection-play-fill"></i>
                <span>{{ $c->nombre }}</span>
            </a>
        @empty
            <div class="px-2 py-3 text-center text-muted" style="font-size:11px; border: 1px dashed rgba(255,255,255,0.15); border-radius:8px; margin: 10px 12px;">
                <i class="bi bi-collection-play mb-1" style="font-size:16px; display:block; opacity:0.6;"></i>
                Sin canales suscritos.
            </div>
        @endforelse
    </div>

    @auth
        <div class="px-3 mt-4 border-top pt-3" style="border-color: rgba(255,255,255,0.1) !important;">
            <p class="text-white-50 mb-2" style="font-size: 10px; font-weight:600; text-transform: uppercase; letter-spacing:0.5px;">Inscribirse por código</p>
            <form action="{{ route('canales.inscribir') }}" method="POST">
                @csrf
                <div class="input-group input-group-sm">
                    <input type="text" name="codigo" class="form-control text-white border-0 bg-dark bg-opacity-50" placeholder="Código..." required style="font-size:11px; border-radius:6px 0 0 6px; box-shadow:none;" autocomplete="off">
                    <button class="btn btn-sm text-white px-2" type="submit" style="background:#378ADD; border-radius:0 6px 6px 0; border:none;" title="Unirse">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </div>
            </form>
        </div>
    @endauth
</div>

{{-- OVERLAY sidebar (siempre en DOM, GSAP controla opacidad) --}}
<div id="sidebar-overlay" class="sidebar-overlay"></div>

{{-- BUSQUEDA OVERLAY MOVIL --}}
<div id="busqueda-overlay" class="busqueda-overlay d-none">
    <input type="text" id="busqueda-input" class="form-control"
           placeholder="Buscar recurso..." autocomplete="off">
</div>

{{-- OFFCANVAS CANALES (movil) --}}
<div class="offcanvas offcanvas-bottom" id="offcanvas-canales"
     style="height:auto; max-height:60vh; border-radius:16px 16px 0 0">
    <div class="offcanvas-header pb-2">
        <h6 class="fw-bold mb-0"><i class="bi bi-collection-play me-2"></i>Canales</h6>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body pt-1 pb-4">
        <div class="d-flex flex-wrap gap-2 mb-3">
            <a href="/" class="btn btn-sm {{ !request('canal') ? 'btn-primary' : 'btn-outline-primary' }}"
               style="border-radius:20px">Todos</a>
            @forelse($canalesNav as $c)
                <a href="/?canal={{ $c->id }}"
                   class="btn btn-sm {{ request('canal') == $c->id ? 'btn-primary' : 'btn-outline-primary' }}"
                   style="border-radius:20px">{{ $c->nombre }}</a>
            @empty
                <div class="w-100 py-3 text-center text-muted" style="font-size:12px;">
                    No estás inscrito en ningún canal.
                </div>
            @endforelse
        </div>

        @auth
            <hr class="my-2" style="opacity:0.1;">
            <div class="pt-2">
                <p class="text-muted mb-2 fw-bold" style="font-size: 11px; text-transform: uppercase;">Inscribirse por código de acceso</p>
                <form action="{{ route('canales.inscribir') }}" method="POST">
                    @csrf
                    <div class="input-group input-group-sm">
                        <input type="text" name="codigo" class="form-control" placeholder="Introduce el código del canal..." required style="font-size:12px; border-radius:6px 0 0 6px;" autocomplete="off">
                        <button class="btn btn-sm text-white px-3 fw-bold" type="submit" style="background:#378ADD; border-radius:0 6px 6px 0; border:none;">
                            Unirse
                        </button>
                    </div>
                </form>
            </div>
        @endauth
    </div>
</div>

{{-- OFFCANVAS PERFIL (movil) --}}
@auth
<div class="offcanvas offcanvas-bottom" id="offcanvas-perfil"
     style="height:auto; border-radius:16px 16px 0 0">
    <div class="offcanvas-header pb-2">
        <h6 class="fw-bold mb-0">
            <i class="bi bi-person-circle me-2"></i>{{ Auth::user()->name }}
        </h6>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body pt-1 pb-4">
        <p class="text-muted mb-3" style="font-size:12px">Rol: {{ ucfirst(Auth::user()->rol) }}</p>
        <div class="d-flex flex-column gap-2">
            <a href="{{ route('perfil.index') }}" class="btn btn-sm btn-outline-secondary text-start">
                <i class="bi bi-person me-2"></i>Mi perfil
            </a>
            <a href="{{ route('canales.index') }}" class="btn btn-sm btn-outline-secondary text-start">
                <i class="bi bi-collection-play me-2"></i>Mis canales
            </a>
            @if(Auth::user()->rol == 'maestro' || Auth::user()->rol == 'admin')
                <a href="{{ route('admin.create') }}" class="btn btn-sm text-white text-start"
                   style="background:#378ADD">
                    <i class="bi bi-upload me-2"></i>Subir recurso
                </a>
            @endif
            @if(Auth::user()->rol == 'admin')
                <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary text-start">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard Admin
                </a>
            @endif
            <hr class="my-1">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger w-100 text-start">
                    <i class="bi bi-box-arrow-right me-2"></i>Cerrar sesion
                </button>
            </form>
        </div>
    </div>
</div>
@endauth

{{-- Mensaje de exito --}}
@if(session('mensaje'))
    <div id="toast-success" class="alert alert-success d-flex align-items-center gap-2"
         style="position: fixed; top: 74px; right: 20px; z-index: 1050; border-radius: 10px; box-shadow: 0 4px 14px rgba(10,41,73,0.12); margin: 0; padding: 12px 18px; border: 1px solid #c3e6cb; font-size: 14px;">
        <i class="bi bi-check-circle-fill"></i>
        <span class="fw-medium">{{ session('mensaje') }}</span>
    </div>
@endif

{{-- Contenido --}}
<div class="container-fluid contenido px-3 mt-3" id="contenido-principal">
    @yield('content')
</div>

{{-- BOTTOM NAV (solo movil) --}}
<div class="bottom-nav d-md-none">
    <a href="/" class="{{ request()->is('/') && !request('canal') ? 'active' : '' }}">
        <i class="bi bi-grid-fill"></i>
        <span>Inicio</span>
    </a>
    <a href="#" id="btn-buscar">
        <i class="bi bi-search"></i>
        <span>Buscar</span>
    </a>
    <a href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-canales">
        <i class="bi bi-collection-play{{ request('canal') ? '-fill' : '' }}"></i>
        <span>Canales</span>
    </a>
    @auth
        <a href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-perfil">
            <i class="bi bi-person-circle"></i>
            <span>Perfil</span>
        </a>
    @else
        <a href="{{ route('login') }}">
            <i class="bi bi-person-circle"></i>
            <span>Entrar</span>
        </a>
    @endauth
</div>

{{-- Script de alerta --}}
@if(session('mensaje'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var alerta = document.getElementById('toast-success');
        if (alerta && typeof gsap !== 'undefined') {
            gsap.from(alerta, { opacity: 0, y: -20, duration: 0.4, ease: 'power3.out' });
            setTimeout(function() {
                gsap.to(alerta, { opacity: 0, x: 20, duration: 0.3, ease: 'power2.in', onComplete: () => alerta.remove() });
            }, 3500);
        }
    });
</script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {

    // ── BUSQUEDA (funciona en movil y desktop) ──
    function filtrarCards(query) {
        var cols = document.querySelectorAll('.card-col');
        if (cols.length === 0) return;
        cols.forEach(function(col) {
            if (query === '') { col.style.display = ''; return; }
            var titulo = col.querySelector('h6');
            var canal  = col.querySelector('p');
            var texto  = (titulo ? titulo.textContent : '') + ' ' + (canal ? canal.textContent : '');
            col.style.display = texto.toLowerCase().includes(query) ? '' : 'none';
        });
    }

    // BUSQUEDA MOVIL (overlay)
    var btnBuscar = document.getElementById('btn-buscar');
    var overlay   = document.getElementById('busqueda-overlay');
    var inputMov  = document.getElementById('busqueda-input');

    if (btnBuscar) {
        btnBuscar.addEventListener('click', function(e) {
            e.preventDefault();
            var abierto = !overlay.classList.contains('d-none');
            overlay.classList.toggle('d-none');
            var icon = btnBuscar.querySelector('i');
            if (!abierto) { inputMov.focus(); icon.className = 'bi bi-x-lg'; }
            else { icon.className = 'bi bi-search'; inputMov.value = ''; filtrarCards(''); }
        });
        inputMov.addEventListener('input', function() { filtrarCards(this.value.toLowerCase().trim()); });
    }

    // BUSQUEDA DESKTOP
    var inputDesk = document.getElementById('busqueda-desktop');
    if (inputDesk) {
        inputDesk.addEventListener('input', function() { filtrarCards(this.value.toLowerCase().trim()); });
    }

    // ── SIDEBAR DE CANALES (GSAP) ──
    var btnSidebar  = document.getElementById('btn-sidebar-toggle');
    var sidebar     = document.getElementById('canal-sidebar');
    var sideOverlay = document.getElementById('sidebar-overlay');
    var sidebarOpen = false;

    function abrirSidebar() {
        gsap.to(sidebar,     { x: 0,   duration: 0.32, ease: 'power3.out' });
        gsap.to(sideOverlay, { opacity: 1, pointerEvents: 'auto', duration: 0.2 });
        sidebarOpen = true;
    }

    function cerrarSidebar() {
        gsap.to(sidebar,     { x: -220, duration: 0.26, ease: 'power3.in' });
        gsap.to(sideOverlay, { opacity: 0, pointerEvents: 'none', duration: 0.18 });
        sidebarOpen = false;
    }

    if (btnSidebar) {
        btnSidebar.addEventListener('click', function() {
            sidebarOpen ? cerrarSidebar() : abrirSidebar();
        });
    }

    if (sideOverlay) {
        sideOverlay.addEventListener('click', cerrarSidebar);
    }

    // ── ANIMACION ENTRADA NAVBAR ──
    gsap.from('.navbar-top', {
        y: -10, opacity: 0, duration: 0.4, ease: 'power2.out'
    });

    // ── ANIMACION ENTRADA CONTENIDO PRINCIPAL ──
    gsap.from('#contenido-principal', {
        opacity: 0, y: 8, duration: 0.45, ease: 'power2.out', delay: 0.08
    });

    // ── INTERCEPTOR GLOBAL DE MODAL DE CONFIRMACIÓN ──
    var pendingForm = null;
    var confirmModal = null;

    document.addEventListener('click', function(e) {
        var button = e.target.closest('button[type="submit"]');
        if (!button) return;

        var form = button.closest('form');
        if (!form) return;

        var onSubmitAttr = form.getAttribute('onsubmit');
        var isDelete = form.querySelector('input[name="_method"][value="DELETE"]') !== null;

        if ((onSubmitAttr && onSubmitAttr.includes('confirm')) || isDelete) {
            e.preventDefault(); // Detener envío inmediato

            var msg = "Esta acción es irreversible. ¿Deseas continuar?";
            if (onSubmitAttr && onSubmitAttr.includes('confirm')) {
                var match = onSubmitAttr.match(/confirm\(['"](.*)['"]\)/);
                if (match && match[1]) {
                    msg = match[1];
                }
            }

            var modalTitle = document.getElementById('confirmModalTitle');
            var modalMsg = document.getElementById('confirmModalMessage');
            if (modalMsg) modalMsg.textContent = msg;

            if (modalTitle) {
                modalTitle.textContent = (msg.toLowerCase().includes('eliminar') || msg.toLowerCase().includes('borrar')) 
                    ? "Confirmar eliminación" 
                    : "Confirmar acción";
            }

            pendingForm = form;

            var modalEl = document.getElementById('confirmModal');
            if (modalEl) {
                if (!confirmModal) {
                    confirmModal = new bootstrap.Modal(modalEl);
                }
                confirmModal.show();
            }
        }
    });

    var confirmBtn = document.getElementById('confirmModalBtnAction');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            if (pendingForm) {
                var oldOnSubmit = pendingForm.getAttribute('onsubmit');
                pendingForm.removeAttribute('onsubmit');
                pendingForm.submit();
                if (oldOnSubmit) {
                    pendingForm.setAttribute('onsubmit', oldOnSubmit);
                }
            }
        });
    }
});
</script>

{{-- MODAL DE CONFIRMACIÓN DE SEGURIDAD GLOBAL --}}
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content shadow-lg border-0" style="border-radius: 16px; background:#fff;">
            <div class="modal-body p-4 text-center">
                <i class="bi bi-exclamation-triangle-fill text-danger mb-3" style="font-size: 40px; display:inline-block;"></i>
                <h5 class="fw-bold mb-2 text-dark" id="confirmModalTitle">¿Confirmar acción?</h5>
                <p class="text-muted mb-4" id="confirmModalMessage" style="font-size: 13px;">Esta acción podría ser permanente. ¿Deseas continuar?</p>
                <div class="d-flex gap-2">
                    {{-- Botón Aceptar/Eliminar en la izquierda (rojo) --}}
                    <button type="button" class="btn btn-danger btn-sm flex-grow-1 py-2 fw-bold" id="confirmModalBtnAction" style="border-radius: 8px; font-size: 13px;">
                        Sí, proceder
                    </button>
                    {{-- Botón Cancelar en la derecha (azul, predeterminado/seguro) --}}
                    <button type="button" class="btn btn-primary btn-sm flex-grow-1 py-2 fw-bold" data-bs-dismiss="modal" style="border-radius: 8px; font-size: 13px;">
                        Cancelar (No)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')

</body>

</html>