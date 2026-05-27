@extends('layouts.base')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('canales.index') }}" class="btn btn-sm btn-outline-secondary me-3" style="border-radius: 8px;">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <h4 class="fw-bold mb-0">Editar Canal</h4>
        </div>

        @if($errors->any())
            <div class="alert alert-danger mb-3 py-2" style="border-radius:10px; font-size:13px">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card p-4 shadow-sm border-0" style="border-radius:12px;">
            <form action="{{ route('canales.update', $canal->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label fw-bold mb-1" style="font-size:13px">Nombre del Canal</label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $canal->nombre) }}" required maxlength="150">
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold mb-1" style="font-size:13px">Descripción <span class="text-muted fw-normal">(opcional)</span></label>
                    <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="4" maxlength="300">{{ old('descripcion', $canal->descripcion) }}</textarea>
                    @error('descripcion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn w-100 text-white" style="background-color:#378ADD; border-radius: 8px;">
                    <i class="bi bi-save me-1"></i> Actualizar Canal
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
