@extends('layouts.base')

@section('content')

    {{-- Barra de estadisticas --}}
    <div class="stats-bar mb-3">
        <span class="stat-item">
            <i class="bi bi-collection-play-fill"></i>
            {{ $stats['recursos'] }} recursos
        </span>
        <span class="stat-sep">·</span>
        <span class="stat-item">
            <i class="bi bi-broadcast"></i>
            {{ $stats['canales'] }} canales
        </span>
        <span class="stat-sep">·</span>
        <span class="stat-item">
            <i class="bi bi-people-fill"></i>
            {{ $stats['usuarios'] }} estudiantes
        </span>
    </div>

    @if($recursos->isEmpty())
        <div class="d-flex flex-column align-items-center justify-content-center text-center" style="padding-top:60px">
            <i class="bi bi-collection-play" style="font-size:56px; color:#cdd8e6"></i>
            <p class="text-muted mt-3 mb-0">No hay recursos todavia.</p>
        </div>
    @else
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
            @foreach($recursos as $recurso)
                <div class="col card-col">
                    {{-- Componente de tarjeta --}}
                    <x-card-recurso :recurso="$recurso" />
                </div>
            @endforeach
        </div>
    @endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // STATS BAR
    gsap.from('.stats-bar', {
        opacity: 0, x: -12, duration: 0.5, ease: 'power2.out', delay: 0.15
    });

    // CARDS con stagger suave
    gsap.from('.card-col', {
        opacity: 0, y: 18, duration: 0.4,
        stagger: 0.055,
        ease: 'power2.out',
        delay: 0.2
    });
});
</script>
@endpush