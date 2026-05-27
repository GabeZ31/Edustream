@extends('layouts.base')

@section('filtros')
@endsection

@section('content')
{{-- Error 404 --}}
<div class="d-flex flex-column align-items-center justify-content-center text-center" style="padding-top: 80px;">
    <i class="bi bi-exclamation-circle" style="font-size: 64px; color: #0C447C"></i>
    <h2 class="fw-bold mt-3 text-dark">Pagina no encontrada</h2>
    <p class="text-muted mb-4">El recurso que buscas no existe o fue eliminado.</p>
    <a href="/" class="btn" style="background-color: #378ADD; color: #fff; border-radius: 8px;">
        <i class="bi bi-arrow-left"></i> Volver al catalogo
    </a>
</div>
@endsection