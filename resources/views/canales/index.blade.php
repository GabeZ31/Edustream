@extends('layouts.base')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold"><i class="bi bi-collection-play"></i> Mis Canales</h4>
            <p class="text-muted mb-0">Gestiona los canales donde subes tus recursos.</p>
        </div>
        <a href="{{ route('canales.create') }}" class="btn text-white" style="background-color:#378ADD; border-radius: 8px;">
            <i class="bi bi-plus-circle me-1"></i> Nuevo Canal
        </a>
    </div>
</div>

<div class="row">
    @forelse($canales as $canal)
        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm border-0" style="border-radius:12px; border: 1px solid #e5e7eb !important;">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="fw-bold text-dark mb-0 text-truncate" style="max-width: 80%;">{{ $canal->nombre }}</h5>
                            <div class="dropdown">
                                <button class="btn btn-link text-muted p-0 border-0 bg-transparent" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="box-shadow:none;">
                                    <i class="bi bi-three-dots-vertical" style="font-size:16px;"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-light" style="border-radius:8px; font-size:13px;">
                                    <li>
                                        <a class="dropdown-item py-2 fw-semibold" href="#" onclick="navigator.clipboard.writeText('{{ $canal->codigo_acceso }}'); alert('Código de canal copiado: {{ $canal->codigo_acceso }}'); return false;">
                                            <i class="bi bi-clipboard me-2"></i>Copiar Código ({{ $canal->codigo_acceso }})
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('canales.edit', $canal->id) }}">
                                            <i class="bi bi-pencil me-2"></i>Editar canal
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('canales.destroy', $canal->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este canal? Todos los recursos asociados podrían quedar huérfanos o dar error.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item py-2 text-danger">
                                                <i class="bi bi-trash me-2"></i>Eliminar canal
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <p class="text-muted mb-0" style="font-size:13px; min-height: 40px;">
                            {{ Str::limit($canal->descripcion, 80) ?? 'Sin descripción' }}
                        </p>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top" style="border-color:#f0f4f8 !important;">
                        <span class="badge bg-light text-dark border" style="font-size:11px; padding: 4px 8px; border-radius:6px;">
                            <i class="bi bi-file-earmark-play me-1" style="color:#378ADD;"></i>{{ $canal->recursos()->count() }} recursos
                        </span>
                        
                        <span class="badge text-white" style="background:#0C447C; font-size:11px; padding: 4px 8px; border-radius:6px;" title="Código de acceso de alumnos">
                            Código: {{ $canal->codigo_acceso }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-collection-play text-muted" style="font-size: 48px;"></i>
            <h5 class="mt-3 fw-bold text-muted">Aún no tienes canales</h5>
            <p class="text-muted">Crea un canal para empezar a subir recursos educativos.</p>
            <a href="{{ route('canales.create') }}" class="btn btn-outline-primary mt-2" style="border-radius: 8px;">Crear mi primer canal</a>
        </div>
    @endforelse
</div>
@endsection
