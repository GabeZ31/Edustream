<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users',
            'password' => [
                'required',
                'confirmed',
                \Illuminate\Validation\Rules\Password::min(8)->letters()->numbers()
            ],
            'rol'      => 'required|in:estudiante,maestro',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'El nombre es obligatorio.',
            'name.max'           => 'El nombre no puede superar 100 caracteres.',
            'email.required'     => 'El email es obligatorio.',
            'email.email'        => 'Ingresa un email valido.',
            'email.unique'       => 'Este email ya esta registrado.',
            'password.required'  => 'La contrasena es obligatoria.',
            'password.confirmed' => 'Las contrasenas no coinciden.',
            'rol.required'       => 'Debes seleccionar tu rol.',
            'rol.in'             => 'El rol seleccionado no es valido.',
        ];
    }
}
