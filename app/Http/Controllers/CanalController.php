<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Canal;
use Illuminate\Support\Facades\Gate;

class CanalController extends Controller
{
    public function index()
    {
        if (Gate::denies('create-content')) {
            return redirect('/')->with('mensaje', 'Acceso denegado. Solo maestros pueden gestionar canales.');
        }
        $user = auth()->user();

        if ($user->rol == 'admin') {
            $canales = Canal::all();
        } else {
            $canales = Canal::where('maestro_id', $user->id)->get();
        }

        return view('canales.index', compact('canales'));
    }

    public function create()
    {
        if (Gate::denies('create-content')) {
            return redirect('/')->with('mensaje', 'Acceso denegado.');
        }

        return view('canales.create');
    }

    public function store(Request $request)
    {
        if (Gate::denies('create-content')) {
            return redirect('/')->with('mensaje', 'Acceso denegado.');
        }
        $user = auth()->user();

        $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string|max:300',
        ]);

        $codigo = strtoupper(\Illuminate\Support\Str::random(6));
        while (Canal::where('codigo_acceso', $codigo)->exists()) {
            $codigo = strtoupper(\Illuminate\Support\Str::random(6));
        }

        Canal::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'maestro_id' => $user->id,
            'codigo_acceso' => $codigo,
        ]);

        return redirect()->route('canales.index')->with('mensaje', 'Canal creado exitosamente.');
    }

    public function edit(Canal $canale) // Usamos $canale por convención de Route::resource (canales -> canale) pero podemos ajustarlo
    {
        if (Gate::denies('manage-canal', $canale)) {
            return redirect('/')->with('mensaje', 'No tienes permiso para editar este canal.');
        }

        return view('canales.edit', ['canal' => $canale]);
    }

    public function update(Request $request, Canal $canale)
    {
        if (Gate::denies('manage-canal', $canale)) {
            return redirect('/')->with('mensaje', 'No tienes permiso para editar este canal.');
        }

        $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string|max:300',
        ]);

        $canale->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('canales.index')->with('mensaje', 'Canal actualizado exitosamente.');
    }

    public function destroy(Canal $canale)
    {
        if (Gate::denies('manage-canal', $canale)) {
            return redirect('/')->with('mensaje', 'No tienes permiso para eliminar este canal.');
        }

        // Eliminar archivos físicos de todos los recursos asociados al canal
        foreach ($canale->recursos as $recurso) {
            if ($recurso->archivo && \Illuminate\Support\Facades\Storage::disk('public')->exists($recurso->archivo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($recurso->archivo);
            }
        }

        $canale->delete();

        return redirect()->route('canales.index')->with('mensaje', 'Canal eliminado correctamente.');
    }

    /**
     * Enroll in a channel via access code.
     */
    public function inscribir(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:10',
        ], [
            'codigo.required' => 'El código de acceso es obligatorio.',
        ]);

        $codigo = strtoupper(trim($request->codigo));
        $canal = Canal::where('codigo_acceso', $codigo)->first();

        if (!$canal) {
            return redirect()->back()->with('mensaje', 'Código de canal inválido o inexistente.');
        }

        $user = auth()->user();

        // Creador del canal no necesita inscribirse
        if ($canal->maestro_id === $user->id) {
            return redirect()->back()->with('mensaje', 'Eres el creador de este canal. Ya tienes acceso.');
        }

        // Verificar si ya está inscrito
        $yaInscrito = \App\Models\Inscripcion::where('user_id', $user->id)
                                             ->where('canal_id', $canal->id)
                                             ->exists();

        if ($yaInscrito) {
            return redirect()->back()->with('mensaje', 'Ya estás inscrito en este canal.');
        }

        // Crear la inscripción
        \App\Models\Inscripcion::create([
            'user_id' => $user->id,
            'canal_id' => $canal->id,
        ]);

        return redirect()->route('inicio', ['canal' => $canal->id])
                         ->with('mensaje', "Inscripción al canal '{$canal->nombre}' realizada con éxito.");
    }
}
