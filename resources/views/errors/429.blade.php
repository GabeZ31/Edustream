@extends('layouts.base')

@section('filtros')
@endsection

@section('content')
{{-- Error 429 - Demasiadas peticiones --}}
<div class="d-flex flex-column align-items-center justify-content-center text-center" style="padding-top: 80px;">
    <i class="bi bi-shield-exclamation" style="font-size: 64px; color: #fd7e14"></i>
    <h2 class="fw-bold mt-3 text-dark">Demasiadas peticiones</h2>
    <p class="text-muted mb-4">Has realizado demasiadas solicitudes en poco tiempo. Por favor, espera un momento antes de volver a intentarlo.</p>
    <a href="/" class="btn text-white fw-bold shadow-sm" style="background-color: #378ADD; color: #fff; border-radius: 8px;">
        <i class="bi bi-arrow-left me-1"></i> Volver al inicio
    </a>
</div>
@endsection
