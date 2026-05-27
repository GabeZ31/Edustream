<?php

namespace App\Http\Controllers;

use App\Models\Recurso;
use App\Models\Comentario;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, Recurso $recurso)
    {
        $request->validate([
            'contenido' => 'required|string|min:3|max:1000',
        ], [
            'contenido.required' => 'El comentario no puede estar vacío.',
            'contenido.min' => 'El comentario debe tener al menos 3 caracteres.',
            'contenido.max' => 'El comentario no puede superar los 1000 caracteres.',
        ]);

        Comentario::create([
            'user_id' => auth()->id(),
            'recurso_id' => $recurso->id,
            'contenido' => $request->contenido,
        ]);

        return redirect()->back()->with('mensaje', 'Comentario publicado con éxito.');
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Comentario $comentario)
    {
        $user = auth()->user();
        if (!$user) {
            abort(403);
        }

        // Autorización: Autor del comentario, dueño del canal del recurso, o Admin
        $esAutor = $comentario->user_id === $user->id;
        $esDuenioCanal = $comentario->recurso->canal->maestro_id === $user->id;
        $esAdmin = $user->rol === 'admin';

        if ($esAutor || $esDuenioCanal || $esAdmin) {
            $comentario->delete();
            return redirect()->back()->with('mensaje', 'Comentario eliminado correctamente.');
        }

        return redirect()->back()->with('mensaje', 'No tienes permiso para eliminar este comentario.');
    }
}
