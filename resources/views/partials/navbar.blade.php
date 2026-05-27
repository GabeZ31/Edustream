{{-- Navbar superior --}}
<nav class="navbar-top navbar px-3 py-2">

    {{-- TOGGLE SIDEBAR (solo desktop) --}}
    <button class="btn btn-sm text-white d-none d-md-flex align-items-center me-2"
            id="btn-sidebar-toggle"
            style="background:transparent; border:none; opacity:0.8"
            title="Canales">
        <i class="bi bi-layout-sidebar" style="font-size:20px"></i>
    </button>

    <a class="navbar-brand text-white fw-bold" href="/">EduStream</a>

    {{-- BUSQUEDA DESKTOP (solo escritorio) --}}
    <div class="d-none d-md-flex ms-4 flex-grow-1" style="max-width:480px">
        <div class="input-group input-group-sm">
            <input type="text" id="busqueda-desktop"
                   class="form-control busqueda-desktop-input"
                   placeholder="Buscar recursos..."
                   autocomplete="off">
            <span class="busqueda-desktop-btn">
                <i class="bi bi-search"></i>
            </span>
        </div>
    </div>

    {{-- BOTON SUBIR RECURSO (Maestros y Admins) --}}
    <div class="ms-auto d-flex align-items-center">
        @auth
            @if(Auth::user()->rol == 'maestro' || Auth::user()->rol == 'admin')
                <a href="{{ route('admin.create') }}" class="btn btn-sm text-white fw-bold shadow-sm"
                   style="background:#378ADD; border-radius:6px; margin-right: 15px;">
                    <i class="bi bi-upload me-1"></i> Subir Recurso
                </a>
            @endif
        @endauth
    </div>

    {{-- USUARIO LOGGEADO --}}
    @auth
        <div class="dropdown ms-3">
            <button class="btn btn-sm dropdown-toggle d-flex align-items-center gap-2"
                    style="background:rgba(255,255,255,0.15); color:#fff; border:none"
                    data-bs-toggle="dropdown">
                <i class="bi bi-person-circle"></i>
                {{ Auth::user()->name }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end" style="min-width:180px">

                {{-- OPCIONES COMUNES --}}
                <li><h6 class="dropdown-header" style="font-size:11px">CUENTA</h6></li>
                <li><a class="dropdown-item" href="{{ route('perfil.index') }}"><i class="bi bi-person me-2"></i>Mi perfil</a></li>
                <li><a class="dropdown-item" href="{{ route('canales.index') }}"><i class="bi bi-collection-play me-2"></i>Mis canales</a></li>

                {{-- SOLO ADMIN --}}
                @if(Auth::user()->rol == 'admin')
                    <li><hr class="dropdown-divider"></li>
                    <li><h6 class="dropdown-header" style="font-size:11px">ADMINISTRACION</h6></li>
                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard Admin</a></li>
                @endif

                {{-- CERRAR SESION --}}
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i>Cerrar sesion
                        </button>
                    </form>
                </li>
            </ul>
        </div>

    {{-- NO LOGGEADO --}}
    @else
        <div class="d-flex gap-2 ms-3">
            <a href="{{ route('login') }}" class="btn btn-sm"
               style="background:rgba(255,255,255,0.15); color:#fff; border:none">
                Iniciar sesion
            </a>
            <a href="{{ route('registro') }}" class="btn btn-sm"
               style="background:#378ADD; color:#fff; border:none; border-radius:6px">
                Registrarse
            </a>
        </div>
    @endauth

</nav>