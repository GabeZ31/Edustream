@extends('layouts.base')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm border-0" style="border-radius:10px;">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-2">
                <h4 class="fw-bold mb-0"><i class="bi bi-pencil-square text-primary me-2"></i> Editar Recurso</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('recursos.update', $recurso->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted" style="font-size:12px">TITULO DEL RECURSO</label>
                        <input type="text" name="titulo" class="form-control" value="{{ old('titulo', $recurso->titulo) }}" required>
                        @error('titulo') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted" style="font-size:12px">DESCRIPCION</label>
                        <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $recurso->descripcion) }}</textarea>
                        @error('descripcion') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted" style="font-size:12px">TIPO DE RECURSO</label>
                            <select name="tipo" class="form-select" required>
                                <option value="video" {{ $recurso->tipo == 'video' ? 'selected' : '' }}>Video</option>
                                <option value="pdf" {{ $recurso->tipo == 'pdf' ? 'selected' : '' }}>PDF</option>
                                <option value="documento" {{ $recurso->tipo == 'documento' ? 'selected' : '' }}>Documento / Presentacion</option>
                                <option value="otro" {{ $recurso->tipo == 'otro' ? 'selected' : '' }}>Otro archivo</option>
                            </select>
                            @error('tipo') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted" style="font-size:12px">CANAL</label>
                            <select name="canal_id" class="form-select" required>
                                @foreach($canales as $canal)
                                    <option value="{{ $canal->id }}" {{ $recurso->canal_id == $canal->id ? 'selected' : '' }}>{{ $canal->nombre }}</option>
                                @endforeach
                            </select>
                            @error('canal_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted" style="font-size:12px">REEMPLAZAR ARCHIVO (OPCIONAL)</label>
                        <input type="file" name="archivo" class="form-control">
                        <small class="text-muted">Si no subes un nuevo archivo, se mantendra el actual.</small>
                        @error('archivo') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('recursos.show', $recurso->id) }}" class="btn btn-light">Cancelar</a>
                        <button type="submit" class="btn text-white" style="background-color: #378ADD;">
                            Guardar Cambios
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
