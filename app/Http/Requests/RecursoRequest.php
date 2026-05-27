<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecursoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'titulo'      => 'required|string|max:150',
            'canal_id'    => 'required|exists:canals,id',
            'descripcion' => 'nullable|string|max:300',
            'tipo'        => 'required|in:video,pdf,documento,otro',
            'portada'     => 'nullable|string|in:portada1.png,portada2.png,portada3.png,portada4.png,portada5.png,portada6.png,portada7.png,portada8.png,portada9.png',
        ];

        // Validacion condicional basada en el tipo de recurso seleccionado
        $tipo = $this->input('tipo');
        if ($tipo === 'video') {
            $rules['archivo'] = 'required|file|max:512000|mimes:mp4,webm';
        } elseif ($tipo === 'pdf') {
            $rules['archivo'] = 'required|file|max:512000|mimes:pdf';
        } elseif ($tipo === 'documento') {
            $rules['archivo'] = 'required|file|max:512000|mimes:doc,docx,txt,ppt,pptx,xls,xlsx';
        } else {
            // 'otro' permite formatos seguros adicionales
            $rules['archivo'] = 'required|file|max:512000|mimes:mp4,webm,pdf,doc,docx,txt,ppt,pptx,xls,xlsx,zip,rar';
        }

        // Si se esta editando el recurso, el archivo es opcional
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['archivo'] = str_replace('required|', 'nullable|', $rules['archivo']);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'titulo.required'      => 'El titulo es obligatorio.',
            'titulo.max'           => 'El titulo no puede superar 150 caracteres.',
            'canal_id.required'    => 'Debes seleccionar un canal.',
            'canal_id.exists'      => 'El canal seleccionado no existe.',
            'descripcion.max'      => 'La descripcion no puede superar 300 caracteres.',
            'tipo.required'        => 'Debes seleccionar el tipo de recurso.',
            'tipo.in'              => 'El tipo seleccionado no es valido.',
            'archivo.required'     => 'Debes subir un archivo.',
            'archivo.file'         => 'El archivo no es valido.',
            'archivo.max'          => 'El archivo no puede superar 500 MB.',
            'archivo.mimes'        => 'El formato del archivo no coincide con el tipo de recurso seleccionado.',
        ];
    }
}
