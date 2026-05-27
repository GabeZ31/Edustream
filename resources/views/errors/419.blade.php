@extends('layouts.base')

@section('filtros')
@endsection

@section('content')
{{-- Error 419 - Sesion expirada --}}
<div class="d-flex flex-column align-items-center justify-content-center text-center" style="padding-top: 80px;">
    <i class="bi bi-clock-history" style="font-size: 64px; color: #0C447C"></i>
    <h2 class="fw-bold mt-3 text-dark">Sesion expirada</h2>
    <p class="text-muted mb-4">Tu sesion expiro. Vuelve a intentarlo.</p>
    <a href="/" class="btn" style="background-color: #378ADD; color: #fff; border-radius: 8px;">
        <i class="bi bi-arrow-left"></i> Volver al inicio
    </a>
</div>
@endsection