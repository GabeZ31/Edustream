@extends('layouts.base')

@section('filtros')
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-8">

        <h5 class="fw-bold mb-3">Subir nuevo recurso</h5>

        {{-- Errores de validacion --}}
        @if($errors->any())
            <div class="alert alert-danger mb-3 py-2" style="border-radius:10px; font-size:13px">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card p-3" style="border-radius:10px; border:1px solid #e5e7eb">
            <form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">

                    {{-- COLUMNA IZQUIERDA --}}
                    <div class="col-12 col-md-6">

                        {{-- Titulo --}}
                        <div class="mb-2">
                            <label class="form-label fw-bold mb-1" style="font-size:13px">Titulo</label>
                            <input type="text" name="titulo"
                                   class="form-control form-control-sm @error('titulo') is-invalid @enderror"
                                   value="{{ old('titulo') }}" maxlength="150" required>
                            @error('titulo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="error-titulo-jquery" class="text-danger" style="display:none; font-size:12px">
                                Solo se permiten letras, numeros, : y #
                            </div>
                        </div>

                        {{-- Canal --}}
                        <div class="mb-2">
                            <label class="form-label fw-bold mb-1" style="font-size:13px">Canal</label>
                            <select name="canal_id"
                                    class="form-select form-select-sm @error('canal_id') is-invalid @enderror"
                                    required>
                                <option value="">Selecciona un canal...</option>
                                @foreach($canales as $canal)
                                    <option value="{{ $canal->id }}" {{ old('canal_id') == $canal->id ? 'selected' : '' }}>
                                        {{ $canal->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('canal_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tipo --}}
                        <div class="mb-2">
                            <label class="form-label fw-bold mb-1" style="font-size:13px">Tipo de recurso</label>
                            <select name="tipo"
                                    class="form-select form-select-sm @error('tipo') is-invalid @enderror"
                                    required>
                                <option value="">Selecciona el tipo...</option>
                                <option value="video"     {{ old('tipo') == 'video'     ? 'selected' : '' }}>Video</option>
                                <option value="pdf"       {{ old('tipo') == 'pdf'       ? 'selected' : '' }}>PDF</option>
                                <option value="documento" {{ old('tipo') == 'documento' ? 'selected' : '' }}>Documento</option>
                                <option value="otro"      {{ old('tipo') == 'otro'      ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('tipo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Descripcion --}}
                        <div class="mb-2">
                            <label class="form-label fw-bold mb-1" style="font-size:13px">Descripcion</label>
                            <textarea name="descripcion"
                                      class="form-control form-control-sm @error('descripcion') is-invalid @enderror"
                                      rows="3" maxlength="300">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Archivo --}}
                        <div class="mb-2">
                            <label class="form-label fw-bold mb-1" style="font-size:13px">Archivo</label>
                            <input type="file" name="archivo" id="archivo"
                                   class="form-control form-control-sm @error('archivo') is-invalid @enderror"
                                   accept=".mp4,.webm,.pdf,.doc,.docx,.txt,.ppt,.pptx,.xls,.xlsx"
                                   required>
                            <small id="hint-archivo" class="text-muted" style="font-size:11px">
                                Selecciona primero el tipo de recurso
                            </small>
                            @error('archivo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    {{-- COLUMNA DERECHA — PORTADA --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold mb-1" style="font-size:13px">
                            Portada <span class="text-muted fw-normal">(opcional)</span>
                        </label>

                        {{-- Grid 3x3 de portadas --}}
                        <div class="row g-2" id="grid-portadas">
                            @php
                                $nombres = [
                                    1 => 'Matemáticas',
                                    2 => 'Programación',
                                    3 => 'Ciencias',
                                    4 => 'Historia',
                                    5 => 'Arte',
                                    6 => 'Música',
                                    7 => 'Idiomas',
                                    8 => 'Tecnología',
                                    9 => 'General',
                                ];
                            @endphp

                            @foreach($nombres as $num => $nombre)
                                <div class="col-4">
                                    <label class="portada-opcion d-block" style="cursor:pointer">
                                        <input type="radio" name="portada" value="portada{{ $num }}.png"
                                               class="d-none"
                                               {{ old('portada') == "portada{$num}.png" ? 'checked' : '' }}>
                                        <img src="/img/portadas/portada{{ $num }}.png"
                                             class="w-100 portada-img {{ old('portada') == "portada{$num}.png" ? 'portada-seleccionada' : '' }}"
                                             style="border-radius:6px; border:2px solid transparent; object-fit:cover; aspect-ratio:16/9;"
                                             title="{{ $nombre }}">
                                        <div class="text-center text-muted mt-1" style="font-size:10px">{{ $nombre }}</div>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        {{-- Boton de quitar seleccion --}}
                        <div class="mt-2">
                            <button type="button" id="btn-quitar-portada"
                                    class="btn btn-sm btn-outline-secondary" style="font-size:12px; display:none;">
                                <i class="bi bi-x"></i> Quitar portada
                            </button>
                            <small class="text-muted d-block mt-1" style="font-size:11px">
                                Sin portada se muestra el ícono del tipo
                            </small>
                        </div>

                    </div>

                </div>{{-- /row --}}

                {{-- Boton enviar --}}
                <div class="mt-3">
                    <button type="submit" class="btn w-100"
                            style="background-color:#378ADD; color:#fff">
                        <i class="bi bi-upload"></i> Subir recurso
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {

        // --- VALIDACION DE TITULO ---
        var patron = /^[a-zA-Z0-9:#áéíóúÁÉÍÓÚñÑ\s]+$/;

        $('[name="titulo"]').on('input', function() {
            var valido = patron.test($(this).val());
            $(this).toggleClass('is-invalid', !valido);
            $('#error-titulo-jquery').toggle(!valido);
        });

        // --- FILTRO DE ARCHIVOS POR TIPO ---
        var tiposArchivo = {
            'video':     { accept: '.mp4,.webm',                             hint: 'Solo videos: mp4, webm · Max. 500 MB' },
            'pdf':       { accept: '.pdf',                                   hint: 'Solo PDF · Max. 500 MB' },
            'documento': { accept: '.doc,.docx,.txt,.ppt,.pptx,.xls,.xlsx', hint: 'doc, docx, txt, ppt, pptx, xls, xlsx · Max. 500 MB' },
            'otro':      { accept: '.mp4,.webm,.pdf,.doc,.docx,.txt,.ppt,.pptx,.xls,.xlsx', hint: 'Cualquier formato permitido · Max. 500 MB' },
        };

        $('[name="tipo"]').on('change', function() {
            var tipo = $(this).val();
            if (tiposArchivo[tipo]) {
                $('#archivo').attr('accept', tiposArchivo[tipo].accept);
                $('#hint-archivo').text(tiposArchivo[tipo].hint);
            } else {
                $('#archivo').attr('accept', '.mp4,.webm,.pdf,.doc,.docx,.txt,.ppt,.pptx,.xls,.xlsx');
                $('#hint-archivo').text('Selecciona primero el tipo de recurso');
            }
            $('#archivo').val('');
        });

        // --- SELECTOR DE PORTADA ---
        $('#grid-portadas input[type="radio"]').on('change', function() {
            // Quitar borde a todas
            $('.portada-img').css('border-color', 'transparent').removeClass('portada-seleccionada');
            // Poner borde azul a la seleccionada
            $(this).siblings('img').css('border-color', '#378ADD').addClass('portada-seleccionada');
            $('#btn-quitar-portada').show();
        });

        $('#btn-quitar-portada').on('click', function() {
            $('#grid-portadas input[type="radio"]').prop('checked', false);
            $('.portada-img').css('border-color', 'transparent').removeClass('portada-seleccionada');
            $(this).hide();
        });

        // --- BLOQUEAR SUBMIT SI TITULO INVALIDO ---
        $('form').on('submit', function(e) {
            var tituloValido = patron.test($('[name="titulo"]').val());
            if (!tituloValido) {
                e.preventDefault();
                $('[name="titulo"]').addClass('is-invalid');
                $('#error-titulo-jquery').show();
            }
        });

    });
</script>
@endpush