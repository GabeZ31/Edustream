<a href="/recursos/{{ $recurso->id }}" class="text-decoration-none">
    <div class="card-recurso card h-100">

        {{-- Portada: imagen si tiene, ícono si no --}}
        <div class="thumb-{{ $recurso->tipo }} thumb-card d-flex align-items-center justify-content-center" style="{{ $recurso->portada ? 'padding:0;' : '' }}">
            @if($recurso->portada)
                <img src="/img/portadas/{{ $recurso->portada }}"
                     class="w-100 h-100"
                     style="object-fit:cover; border-radius: var(--thumb-radius, 8px) var(--thumb-radius, 8px) 0 0;">
            @elseif($recurso->tipo == 'video')
                <i class="bi bi-play-circle-fill thumb-icon" style="color:#0C447C"></i>
            @elseif($recurso->tipo == 'pdf')
                <i class="bi bi-file-pdf-fill thumb-icon" style="color:#A32D2D"></i>
            @elseif($recurso->tipo == 'documento')
                <i class="bi bi-file-text-fill thumb-icon" style="color:#27500A"></i>
            @else
                <i class="bi bi-folder-fill thumb-icon" style="color:#633806"></i>
            @endif
        </div>

        {{-- Info --}}
        <div class="card-body p-2">
            <span class="badge badge-{{ $recurso->tipo }} mb-1">{{ $recurso->tipo }}</span>
            <h6 class="fw-bold mb-1 text-dark" style="font-size:13px">{{ $recurso->titulo }}</h6>
            <p class="text-muted mb-0" style="font-size:11px">{{ $recurso->canal->nombre }}</p>
        </div>

    </div>
</a>