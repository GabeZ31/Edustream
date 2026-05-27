@extends('layouts.base')

@section('filtros')
@endsection

@section('content')
{{-- Error 403 --}}
<div class="d-flex flex-column align-items-center justify-content-center text-center" style="padding-top: 80px;">
    <i class="bi bi-shield-slash-fill" style="font-size: 64px; color: #dc3545"></i>
    <h2 class="fw-bold mt-3 text-dark">Acceso restringido</h2>
    <p class="text-muted mb-4">No tienes los permisos necesarios para ver este recurso o canal.</p>
    <a href="/" class="btn text-white fw-bold shadow-sm" style="background-color: #378ADD; color: #fff; border-radius: 8px;">
        <i class="bi bi-arrow-left me-1"></i> Volver al catálogo
    </a>
</div>
@endsection
