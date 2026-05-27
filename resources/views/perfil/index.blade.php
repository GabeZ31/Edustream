@extends('layouts.base')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        
        <div class="d-flex align-items-center mb-4">
            <h4 class="fw-bold mb-0"><i class="bi bi-person-circle"></i> Mi Perfil</h4>
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
            <div class="mb-4 text-center">
                <div class="d-inline-flex justify-content-center align-items-center bg-light text-primary rounded-circle mb-2" style="width: 80px; height: 80px; font-size: 36px;">
                    <i class="bi bi-person-fill"></i>
                </div>
                <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                <span class="badge bg-{{ $user->rol == 'admin' ? 'danger' : ($user->rol == 'maestro' ? 'primary' : 'secondary') }}">{{ ucfirst($user->rol) }}</span>
            </div>

            <form action="{{ route('perfil.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label fw-bold mb-1" style="font-size:13px">Nombre completo</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required maxlength="255">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold mb-1" style="font-size:13px">Correo electrónico</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required maxlength="255">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">
                
                <h6 class="fw-bold mb-3">Cambiar contraseña <span class="text-muted fw-normal" style="font-size: 13px;">(Opcional)</span></h6>

                <div class="mb-3">
                    <label class="form-label fw-bold mb-1" style="font-size:13px">Nueva contraseña</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" minlength="8">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold mb-1" style="font-size:13px">Confirmar nueva contraseña</label>
                    <input type="password" name="password_confirmation" class="form-control" minlength="8">
                </div>

                <button type="submit" class="btn w-100 text-white" style="background-color:#378ADD; border-radius: 8px;">
                    <i class="bi bi-save me-1"></i> Guardar Cambios
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
