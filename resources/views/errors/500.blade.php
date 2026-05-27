@extends('layouts.base')

@section('filtros')
@endsection

@section('content')
{{-- Error 500 --}}
<div class="d-flex flex-column align-items-center justify-content-center text-center" style="padding-top: 80px;">
    <i class="bi bi-exclamation-triangle-fill" style="font-size: 64px; color: #dc3545"></i>
    <h2 class="fw-bold mt-3 text-dark">Error interno del servidor</h2>
    <p class="text-muted mb-4">Algo salió mal en el servidor. Estamos trabajando para solucionarlo.</p>
    <a href="/" class="btn text-white fw-bold shadow-sm" style="background-color: #378ADD; color: #fff; border-radius: 8px;">
        <i class="bi bi-arrow-left me-1"></i> Volver al inicio
    </a>
</div>
@endsection