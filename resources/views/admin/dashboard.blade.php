@extends('layouts.base')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold"><i class="bi bi-speedometer2"></i> Dashboard de Administrador</h4>
        <p class="text-muted">Gestiona los usuarios y recursos de EduStream.</p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card p-3 shadow-sm border-0" style="border-radius:10px;">
            <div class="d-flex align-items-center">
                <div class="bg-primary text-white rounded p-3 me-3">
                    <i class="bi bi-people" style="font-size: 24px;"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-muted">Total Usuarios</h6>
                    <h3 class="fw-bold mb-0">{{ $usuarios->count() }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card p-3 shadow-sm border-0" style="border-radius:10px;">
            <div class="d-flex align-items-center">
                <div class="bg-success text-white rounded p-3 me-3">
                    <i class="bi bi-collection-play" style="font-size: 24px;"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-muted">Total Recursos</h6>
                    <h3 class="fw-bold mb-0">{{ $recursos->count() }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

@php
    $adminsCount = $usuarios->where('rol', 'admin')->count();
    $maestrosCount = $usuarios->where('rol', 'maestro')->count();
    $estudiantesCount = $usuarios->where('rol', 'estudiante')->count();

    $videosCount = $recursos->where('tipo', 'video')->count();
    $pdfsCount = $recursos->where('tipo', 'pdf')->count();
    $docsCount = $recursos->where('tipo', 'documento')->count();
    $otrosCount = $recursos->where('tipo', 'otro')->count();
@endphp

{{-- Fila de Gráficas Visuales --}}
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card p-3 shadow-sm border-0 h-100" style="border-radius:10px; background:#fff;">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-pie-chart-fill text-primary me-2"></i>Distribución de Usuarios por Rol</h6>
            <div style="position: relative; height: 220px; width: 100%;">
                <canvas id="chartRoles"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card p-3 shadow-sm border-0 h-100" style="border-radius:10px; background:#fff;">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-bar-chart-line-fill text-success me-2"></i>Recursos por Formato</h6>
            <div style="position: relative; height: 220px; width: 100%;">
                <canvas id="chartRecursos"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Tabla de Usuarios --}}
    <div class="col-12 col-lg-5 mb-4">
        <div class="card shadow-sm border-0" style="border-radius:10px;">
            <div class="card-header bg-white border-bottom-0 pt-3 pb-2 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-people-fill me-2 text-muted"></i>Usuarios del Sistema
                </h6>
                <button class="btn btn-sm btn-link text-decoration-none p-0 text-muted" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUsuarios" aria-expanded="true" aria-controls="collapseUsuarios">
                    <i class="bi bi-chevron-up collapse-icon" style="font-size: 14px;"></i>
                </button>
            </div>
            <div class="collapse show" id="collapseUsuarios">
                <div class="card-body pt-0">
                    <div class="table-responsive" style="max-height: 380px; overflow-y: auto; border-radius: 8px;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light" style="position: sticky; top: 0; z-index: 2;">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usuarios as $user)
                                <tr>
                                    <td class="fw-medium">{{ $user->name }}</td>
                                    <td class="text-muted" style="font-size: 13px;">{{ $user->email }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <form action="{{ route('admin.usuarios.rol', $user->id) }}" method="POST" class="d-flex align-items-center gap-1">
                                                @csrf
                                                @method('PUT')
                                                <select name="rol" class="form-select form-select-sm" style="width: auto; font-size: 12px; border-radius: 6px;" onchange="this.form.submit()">
                                                    <option value="estudiante" {{ $user->rol == 'estudiante' ? 'selected' : '' }}>Estudiante</option>
                                                    <option value="maestro" {{ $user->rol == 'maestro' ? 'selected' : '' }}>Maestro</option>
                                                    <option value="admin" {{ $user->rol == 'admin' ? 'selected' : '' }}>Admin</option>
                                                </select>
                                            </form>
                                            @if(auth()->user()->id !== $user->id)
                                            <form action="{{ route('admin.usuarios.destroy', $user->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar a este usuario? Esta acción es irreversible.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 6px;" title="Eliminar usuario">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de Recursos --}}
    <div class="col-12 col-lg-7 mb-4">
        <div class="card shadow-sm border-0" style="border-radius:10px;">
            <div class="card-header bg-white border-bottom-0 pt-3 pb-2 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-folder-fill me-2 text-muted"></i>Recursos Subidos
                </h6>
                <button class="btn btn-sm btn-link text-decoration-none p-0 text-muted" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRecursos" aria-expanded="true" aria-controls="collapseRecursos">
                    <i class="bi bi-chevron-up collapse-icon" style="font-size: 14px;"></i>
                </button>
            </div>
            <div class="collapse show" id="collapseRecursos">
                <div class="card-body pt-0">
                    <div class="table-responsive" style="max-height: 380px; overflow-y: auto; border-radius: 8px;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light" style="position: sticky; top: 0; z-index: 2;">
                                <tr>
                                    <th>Título</th>
                                    <th>Tipo</th>
                                    <th>Canal (Maestro)</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recursos as $recurso)
                                <tr>
                                    <td><a href="{{ route('recursos.show', $recurso->id) }}" class="text-decoration-none fw-semibold text-dark">{{ Str::limit($recurso->titulo, 30) }}</a></td>
                                    <td>
                                        @if($recurso->tipo === 'video')
                                            <span class="badge bg-danger bg-opacity-10 text-danger" style="font-size:11px; border: 1px solid rgba(220,53,69,0.2);">Video</span>
                                        @elseif($recurso->tipo === 'pdf')
                                            <span class="badge bg-warning bg-opacity-10 text-warning-emphasis" style="font-size:11px; border: 1px solid rgba(255,193,7,0.2);">PDF</span>
                                        @elseif($recurso->tipo === 'documento')
                                            <span class="badge bg-success bg-opacity-10 text-success" style="font-size:11px; border: 1px solid rgba(25,135,84,0.2);">Doc</span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary" style="font-size:11px; border: 1px solid rgba(108,117,125,0.2);">Otro</span>
                                        @endif
                                    </td>
                                    <td class="text-muted" style="font-size: 13px;">
                                        {{ $recurso->canal ? $recurso->canal->nombre : 'Sin Canal' }}
                                    </td>
                                    <td>
                                        <a href="{{ route('recursos.edit', $recurso->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 6px;" title="Editar recurso">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Gráfica de Roles
    const ctxRoles = document.getElementById('chartRoles').getContext('2d');
    new Chart(ctxRoles, {
        type: 'doughnut',
        data: {
            labels: ['Estudiantes', 'Maestros', 'Administradores'],
            datasets: [{
                data: [{{ $estudiantesCount }}, {{ $maestrosCount }}, {{ $adminsCount }}],
                backgroundColor: ['#fd7e14', '#378add', '#0c447c'],
                borderColor: '#ffffff',
                borderWidth: 2,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 15,
                        font: {
                            family: "'Inter', sans-serif",
                            size: 12
                        }
                    }
                }
            }
        }
    });

    // 2. Gráfica de Recursos
    const ctxRecursos = document.getElementById('chartRecursos').getContext('2d');
    new Chart(ctxRecursos, {
        type: 'bar',
        data: {
            labels: ['Videos', 'PDFs', 'Documentos', 'Otros'],
            datasets: [{
                data: [{{ $videosCount }}, {{ $pdfsCount }}, {{ $docsCount }}, {{ $otrosCount }}],
                backgroundColor: ['#dc3545', '#ffc107', '#198754', '#6c757d'],
                borderRadius: 5,
                barThickness: 24
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            family: "'Inter', sans-serif",
                            size: 11
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            family: "'Inter', sans-serif",
                            size: 11
                        }
                    }
                }
            }
        }
    });

    // 3. Manejo de Chevrons en Collapse
    const collapses = document.querySelectorAll('.collapse');
    collapses.forEach(function(collapseEl) {
        collapseEl.addEventListener('show.bs.collapse', function () {
            const icon = this.parentElement.querySelector('.collapse-icon');
            if (icon) {
                icon.classList.remove('bi-chevron-down');
                icon.classList.add('bi-chevron-up');
            }
        });
        collapseEl.addEventListener('hide.bs.collapse', function () {
            const icon = this.parentElement.querySelector('.collapse-icon');
            if (icon) {
                icon.classList.remove('bi-chevron-up');
                icon.classList.add('bi-chevron-down');
            }
        });
    });
});
</script>
@endpush

@endsection
