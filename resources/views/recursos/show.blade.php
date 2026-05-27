@extends('layouts.base')

@section('content')
<div class="row" id="show-content">

    {{-- Columna principal --}}
    <div class="col-12 col-md-8">

        {{-- VIDEO --}}
        @if($recurso->tipo == 'video')
            {{-- Contenedor 16:9 con overflow limpio --}}
            <div class="player-wrapper mb-3">
                {{-- Spinner de carga --}}
                <div id="player-loader" class="player-loader">
                    <div class="player-spinner"></div>
                </div>
                <video id="player" controls
                       class="player-video"
                       src="/storage/{{ $recurso->archivo }}"
                       preload="metadata">
                </video>
            </div>

            {{-- Panel de metricas P2P --}}
            <div class="p2p-panel mb-3">
                <div class="p2p-stat">
                    <i class="bi bi-people-fill p2p-icon"></i>
                    <div>
                        <div class="p2p-label">PEERS</div>
                        <div class="p2p-value" id="peers">0</div>
                    </div>
                </div>
                <div class="p2p-stat">
                    <i class="bi bi-arrow-down-circle-fill p2p-icon"></i>
                    <div>
                        <div class="p2p-label">DESCARGADO P2P</div>
                        <div class="p2p-value" id="progreso">0%</div>
                    </div>
                </div>
                <div class="p2p-stat p2p-estado-wrap">
                    <span class="p2p-dot" id="estado-dot"></span>
                    <div>
                        <div class="p2p-label">ESTADO</div>
                        <div class="p2p-value" id="estado" style="color:#0C447C">Conectando...</div>
                    </div>
                </div>
            </div>

        {{-- PDF --}}
        @elseif($recurso->tipo == 'pdf')
            <iframe src="/storage/{{ $recurso->archivo }}"
                    class="w-100 mb-3"
                    style="height:500px; border-radius:10px; border:1px solid #e5e7eb">
            </iframe>

        {{-- DOCUMENTO U OTRO --}}
        @else
            <div class="card p-4 mb-3 text-center" style="border-radius:10px; border:1px solid #e5e7eb">
                <i class="bi bi-file-earmark-arrow-down" style="font-size:48px; color:#0C447C"></i>
                <p class="mt-2 mb-3 text-muted">Este recurso esta disponible para descarga</p>
                <a href="/storage/{{ $recurso->archivo }}"
                   download
                   class="btn btn-sm" style="background-color:#378ADD; color:#fff">
                    <i class="bi bi-download"></i> Descargar archivo
                </a>
            </div>
        @endif

        {{-- Info del recurso --}}
        <div class="card p-3" style="border-radius:10px; border:1px solid #e5e7eb">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="badge badge-{{ $recurso->tipo }} mb-2">{{ $recurso->tipo }}</span>
                    <h5 class="fw-bold text-dark">{{ $recurso->titulo }}</h5>
                    <p class="text-muted mb-1" style="font-size:13px">
                        <i class="bi bi-collection-play"></i> {{ $recurso->canal->nombre }}
                    </p>
                </div>
                
                {{-- Boton de Editar y Borrar (Solo Admin o Maestro Dueño) --}}
                @auth
                    @if(Auth::user()->rol == 'admin' || Auth::user()->id == $recurso->canal->maestro_id)
                        <div class="d-flex gap-2">
                            <a href="{{ route('recursos.edit', $recurso->id) }}" class="btn btn-sm btn-outline-primary shadow-sm" style="border-radius: 6px;">
                                <i class="bi bi-pencil me-1"></i> Editar
                            </a>
                            <form action="{{ route('recursos.destroy', $recurso->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este recurso?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm" style="border-radius: 6px;">
                                    <i class="bi bi-trash me-1"></i> Borrar
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
            
            @if($recurso->descripcion)
                <hr class="my-2" style="opacity: 0.1">
                <p class="text-muted mb-0 mt-2" style="font-size:13px">{{ $recurso->descripcion }}</p>
            @endif
        </div>
    </div>

    {{-- Columna lateral - recursos relacionados --}}
    <div class="col-12 col-md-4 mt-3 mt-md-0">
        <p class="fw-bold text-muted mb-2" style="font-size:12px; text-transform:uppercase">
            Mas del canal
        </p>

        @foreach($recurso->canal->recursos as $relacionado)
            @if($relacionado->id != $recurso->id)
                <a href="/recursos/{{ $relacionado->id }}" class="text-decoration-none">
                    <div class="card mb-2 d-flex flex-row align-items-center p-2" style="border-radius:10px; border:1px solid #e5e7eb; background:#fff; height: 80px;">
                        <div class="thumb-{{ $relacionado->tipo }} rounded d-flex align-items-center justify-content-center flex-shrink-0" 
                             style="width: 100px; height: 100%; overflow: hidden;">
                            @if($relacionado->portada)
                                <img src="/img/portadas/{{ $relacionado->portada }}" class="w-100 h-100" style="object-fit:cover;">
                            @elseif($relacionado->tipo == 'video')
                                <i class="bi bi-play-circle-fill" style="color:#0C447C; font-size:28px;"></i>
                            @elseif($relacionado->tipo == 'pdf')
                                <i class="bi bi-file-pdf-fill" style="color:#A32D2D; font-size:28px;"></i>
                            @elseif($relacionado->tipo == 'documento')
                                <i class="bi bi-file-text-fill" style="color:#27500A; font-size:28px;"></i>
                            @else
                                <i class="bi bi-folder-fill" style="color:#633806; font-size:28px;"></i>
                            @endif
                        </div>
                        <div class="ms-3 flex-grow-1 overflow-hidden">
                            <h6 class="fw-bold mb-1 text-dark text-truncate" style="font-size:13px; margin:0;">{{ $relacionado->titulo }}</h6>
                            <p class="text-muted mb-0 text-truncate" style="font-size:11px;">{{ $relacionado->canal->nombre }}</p>
                        </div>
                    </div>
                </a>
            @endif
        @endforeach

        {{-- SECCION DE COMENTARIOS --}}
        <div class="comments-container mt-4 mb-4">
            <h5 class="fw-bold text-dark mb-3" style="font-size:16px;">
                <i class="bi bg-transparent bi-chat-left-text me-2" style="color:#0C447C"></i>Comentarios 
                <span class="badge rounded-pill bg-secondary ms-1" style="font-size:11px; font-weight:600;">{{ $recurso->comentarios->count() }}</span>
            </h5>

            {{-- Formulario para nuevo comentario --}}
            @auth
                <div class="card p-3 mb-3 border-light shadow-sm" style="border-radius:12px; background:#fff; border: 1px solid #e5e7eb;">
                    <form action="{{ route('comentarios.store', $recurso->id) }}" method="POST">
                        @csrf
                        <div class="d-flex gap-2">
                            <div class="avatar-circle flex-shrink-0" style="background:#0C447C; color:#fff; width:34px; height:34px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:12px;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="flex-grow-1">
                                <textarea name="contenido" rows="2" class="form-control" placeholder="Añade un comentario..." style="border-radius:8px; font-size:12px; resize:none; border: 1px solid #ced4da;" required></textarea>
                                <div class="d-flex justify-content-end mt-2">
                                    <button type="submit" class="btn btn-sm text-white px-3 fw-bold shadow-sm" style="background:#378ADD; border-radius:6px; font-size:11px;">
                                        Comentar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                <div class="card p-3 text-center mb-3 shadow-sm" style="border-radius:12px; border: 1px dashed #ced4da; background: #f8f9fa;">
                    <i class="bi bi-chat-left-text-fill mb-2" style="font-size:20px; color:#378ADD;"></i>
                    <p class="mb-1 fw-bold text-dark" style="font-size:13px;">¿Participar en la discusión?</p>
                    <p class="text-muted mb-2" style="font-size:11px;">Inicia sesión para poder escribir.</p>
                    <div>
                        <a href="{{ route('login') }}" class="btn btn-sm text-white px-2 fw-bold shadow-sm" style="background:#378ADD; border-radius:6px; font-size:11px;">Entrar</a>
                        <a href="{{ route('registro') }}" class="btn btn-sm btn-outline-secondary px-2 ms-1 shadow-sm" style="border-radius:6px; font-size:11px;">Registrarse</a>
                    </div>
                </div>
            @endauth

            {{-- Listado de comentarios --}}
            <div class="comments-list d-flex flex-column gap-2">
                @forelse($recurso->comentarios as $comentario)
                    <div class="comentario-card card p-3 border-light shadow-sm" style="border-radius:12px; background:#fff; position:relative; border: 1px solid #e5e7eb;">
                        <div class="d-flex gap-2 align-items-start">
                            {{-- Avatar circular con inicial --}}
                            @php
                                $coloresAvatar = ['#0C447C', '#378ADD', '#198754', '#6f42c1', '#fd7e14', '#20c997', '#d63384'];
                                $indiceColor = $comentario->user_id % count($coloresAvatar);
                                $colorBg = $coloresAvatar[$indiceColor];
                            @endphp
                            <div class="avatar-circle flex-shrink-0" style="background:{{ $colorBg }}; color:#fff; width:34px; height:34px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:12px;">
                                {{ strtoupper(substr($comentario->user->name, 0, 1)) }}
                            </div>

                            <div class="flex-grow-1 overflow-hidden">
                                <div class="d-flex align-items-center flex-wrap gap-1 mb-1">
                                    <span class="fw-bold text-dark text-truncate" style="font-size:12px; max-width: 100px;">{{ $comentario->user->name }}</span>
                                    
                                    {{-- Badges de Rol --}}
                                    @if($comentario->user->rol === 'admin')
                                        <span class="badge bg-danger" style="font-size:8px; font-weight:600; padding:1px 4px; border-radius:10px;">Admin</span>
                                    @elseif($comentario->user->rol === 'maestro')
                                        @if($comentario->user->id === $recurso->canal->maestro_id)
                                            <span class="badge text-white" style="font-size:8px; font-weight:600; padding:1px 4px; border-radius:10px; background:#0C447C;">Autor/Mtro</span>
                                        @else
                                            <span class="badge bg-info text-white" style="font-size:8px; font-weight:600; padding:1px 4px; border-radius:10px;">Maestro</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary text-white" style="font-size:8px; font-weight:600; padding:1px 4px; border-radius:10px;">Estudiante</span>
                                    @endif

                                    <span class="text-muted" style="font-size:10px;">&middot; {{ $comentario->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="mb-0 text-dark text-break" style="font-size:12px; line-height:1.4;">{!! nl2br(e($comentario->contenido)) !!}</p>
                            </div>

                            {{-- Botón para eliminar comentario --}}
                            @auth
                                @if(Auth::id() === $comentario->user_id || Auth::user()->rol === 'admin' || Auth::id() === $recurso->canal->maestro_id)
                                    <form action="{{ route('comentarios.destroy', $comentario->id) }}" method="POST" class="ms-auto" onsubmit="return confirm('¿Estás seguro de eliminar este comentario?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn p-0 text-danger border-0 bg-transparent" title="Eliminar comentario" style="opacity:0.65; transition: opacity 0.2s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.65">
                                            <i class="bi bi-trash-fill" style="font-size:12px;"></i>
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                @empty
                    <div class="text-center py-3 text-muted bg-light rounded-3" style="border: 1px dashed #ced4da; border-radius:12px;">
                        <i class="bi bi-chat-dots-fill mb-1" style="font-size:20px; opacity:0.5;"></i>
                        <p class="mb-0" style="font-size:11px;">Aún no hay comentarios.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>

{{-- WebTorrent implementado para que funcione al 100% como P2P + Respaldo de Servidor --}}
@if($recurso->tipo == 'video')
<style>
/* ── Player ── */
.player-wrapper {
    position: relative;
    width: 100%;
    aspect-ratio: 16 / 9;
    background: #0d0d0d;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
}

.player-video {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
}

.player-loader {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #0d0d0d;
    z-index: 10;
    transition: opacity 0.4s ease;
}

.player-loader.hidden {
    opacity: 0;
    pointer-events: none;
}

.player-spinner {
    width: 40px;
    height: 40px;
    border: 3px solid rgba(55,138,221,0.2);
    border-top-color: #378ADD;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* ── Panel P2P ── */
.p2p-panel {
    display: flex;
    gap: 0;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.p2p-stat {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    border-right: 1px solid #f0f0f0;
}

.p2p-stat:last-child { border-right: none; }

.p2p-icon {
    font-size: 20px;
    color: #378ADD;
    flex-shrink: 0;
}

.p2p-label {
    font-size: 9px;
    font-weight: 700;
    color: #aab0bc;
    letter-spacing: 0.8px;
    margin-bottom: 2px;
}

.p2p-value {
    font-size: 16px;
    font-weight: 700;
    color: #1A1A2E;
    line-height: 1;
}

/* Dot de estado con pulso */
.p2p-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #aab0bc;
    flex-shrink: 0;
    transition: background 0.3s ease;
}

.p2p-dot.activo {
    background: #378ADD;
    animation: pulse-dot 1.5s ease infinite;
}

.p2p-dot.completo { background: #198754; animation: none; }
.p2p-dot.directo  { background: #6c757d; animation: none; }
.p2p-dot.error    { background: #dc3545; animation: none; }

@keyframes pulse-dot {
    0%, 100% { box-shadow: 0 0 0 0 rgba(55,138,221,0.4); }
    50%       { box-shadow: 0 0 0 5px rgba(55,138,221,0); }
}

@media (max-width: 576px) {
    .p2p-panel { flex-direction: column; }
    .p2p-stat  { border-right: none; border-bottom: 1px solid #f0f0f0; }
    .p2p-stat:last-child { border-bottom: none; }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/webtorrent/webtorrent.min.js"></script>
<script>
    const client   = new WebTorrent();
    const videoUrl = window.location.origin + '/storage/{{ $recurso->archivo }}';
    let torrentId  = '{{ $recurso->torrent }}';
    const loader   = document.getElementById('player-loader');
    const dot      = document.getElementById('estado-dot');

    // Ocultar loader cuando el video puede reproducirse
    document.getElementById('player').addEventListener('canplay', function() {
        loader.classList.add('hidden');
    });

    // Limpiamos el src del player en caso de que WebTorrent lo maneje
    document.getElementById('player').removeAttribute('src');

    function setEstado(texto, color, dotClass) {
        document.getElementById('estado').textContent = texto;
        document.getElementById('estado').style.color = color;
        dot.className = 'p2p-dot ' + dotClass;
    }

    if(!torrentId || torrentId.trim() === '') {
        // Fallback inmediato por si en DB no existiera torrent
        document.getElementById('player').src = videoUrl;
        setEstado('Directo (Sin Torrent)', '#6c757d', 'directo');
    } else {
        if (!torrentId.startsWith('magnet:')) {
            torrentId = '/storage/' + torrentId;
        }

        setEstado('Iniciando P2P...', '#0C447C', '');

        client.add(torrentId, function(torrent) {

            // LA CLAVE: Agregar el servidor como 'WebSeed'.
            // Si el P2P es lento, el video baja seguro del servidor y permite adelantar/atrasar.
            torrent.addWebSeed(videoUrl);

            const file = torrent.files.find(f => f.name.match(/\.(mp4|webm|mkv|avi)$/i));

            if(file) {
                // Renderizarlo via P2P
                file.renderTo('#player');

                setInterval(function() {
                    document.getElementById('peers').textContent    = torrent.numPeers;
                    document.getElementById('progreso').textContent = Math.round(torrent.progress * 100) + '%';

                    if(torrent.done) {
                        setEstado('Completo', '#198754', 'completo');
                    } else if (torrent.numPeers > 0) {
                        setEstado('P2P Acelerado', '#378ADD', 'activo');
                    } else {
                        setEstado('Descargando de Servidor...', '#6c757d', 'directo');
                    }
                }, 1000);
            } else {
                document.getElementById('player').src = videoUrl;
                setEstado('Archivo no soportado en torrent', '#6c757d', 'directo');
            }
        });

        client.on('error', function(err) {
            console.error('WebTorrent Error:', err);
            document.getElementById('player').src = videoUrl;
            setEstado('Error P2P, Directo', '#dc3545', 'error');
        });
    }
</script>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var tl = gsap.timeline({ defaults: { ease: 'power2.out' } });

    // Columna principal: player → p2p → info
    tl.from('.player-wrapper', { opacity: 0, y: 16, duration: 0.45 })
      .from('.p2p-panel',      { opacity: 0, y: 10, duration: 0.35 }, '-=0.1')
      .from('.card.p-3',       { opacity: 0, y: 10, duration: 0.35 }, '-=0.1');

    // Columna lateral: entra ligeramente despues (incluye recomendaciones y comentarios)
    gsap.from('.col-md-4 > *', {
        opacity: 0, x: 12, duration: 0.35,
        stagger: 0.06, delay: 0.3, ease: 'power2.out'
    });
});
</script>
@endpush

@endsection