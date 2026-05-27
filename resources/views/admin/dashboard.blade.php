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

<div class="row">
    {{-- Tabla de Usuarios --}}
    <div class="col-12 col-lg-5 mb-4">
        <div class="card shadow-sm border-0" style="border-radius:10px;">
            <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                <h6 class="fw-bold">Usuarios Recientes</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usuarios->take(10) as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <form action="{{ route('admin.usuarios.rol', $user->id) }}" method="POST" class="d-flex align-items-center gap-1">
                                            @csrf
                                            @method('PUT')
                                            <select name="rol" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                                                <option value="estudiante" {{ $user->rol == 'estudiante' ? 'selected' : '' }}>Estudiante</option>
                                                <option value="maestro" {{ $user->rol == 'maestro' ? 'selected' : '' }}>Maestro</option>
                                                <option value="admin" {{ $user->rol == 'admin' ? 'selected' : '' }}>Admin</option>
                                            </select>
                                        </form>
                                        @if(auth()->user()->id !== $user->id)
                                        <form action="{{ route('admin.usuarios.destroy', $user->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar a este usuario? Esta acción es irreversible.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
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

    {{-- Tabla de Recursos --}}
    <div class="col-12 col-lg-7 mb-4">
        <div class="card shadow-sm border-0" style="border-radius:10px;">
            <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                <h6 class="fw-bold">Recursos Subidos</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Titulo</th>
                                <th>Tipo</th>
                                <th>Canal (Maestro)</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recursos as $recurso)
                            <tr>
                                <td><a href="{{ route('recursos.show', $recurso->id) }}" class="text-decoration-none fw-bold">{{ Str::limit($recurso->titulo, 25) }}</a></td>
                                <td><span class="badge badge-{{ $recurso->tipo }}">{{ ucfirst($recurso->tipo) }}</span></td>
                                <td>{{ $recurso->canal ? $recurso->canal->nombre : 'Sin Canal' }}</td>
                                <td>
                                    <a href="{{ route('recursos.edit', $recurso->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
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
@endsection
