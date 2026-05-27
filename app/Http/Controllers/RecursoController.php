<?php

namespace App\Http\Controllers;

use App\Models\Canal;
use App\Models\Recurso;
use App\Http\Requests\RecursoRequest;
use Illuminate\Support\Facades\Gate;

class RecursoController extends Controller{

    public function index(\Illuminate\Http\Request $request){
        $user = auth()->user();

        // 1. Obtener canales permitidos según el rol
        if ($user) {
            if ($user->rol === 'admin') {
                $canales = Canal::all();
            } elseif ($user->rol === 'maestro') {
                // Sus canales creados + canales inscritos
                $propiosIds = Canal::where('maestro_id', $user->id)->pluck('id');
                $suscritosIds = \App\Models\Inscripcion::where('user_id', $user->id)->pluck('canal_id');
                $todosIds = $propiosIds->merge($suscritosIds)->unique();
                $canales = Canal::whereIn('id', $todosIds)->get();
            } else {
                // Alumno: solo canales inscritos
                $suscritosIds = \App\Models\Inscripcion::where('user_id', $user->id)->pluck('canal_id');
                $canales = Canal::whereIn('id', $suscritosIds)->get();
            }
        } else {
            // Invitado: todos los canales
            $canales = Canal::all();
        }

        $canalIds = $canales->pluck('id');

        // 2. Filtrar recursos por canales permitidos
        if ($request->canal) {
            if (!$canalIds->contains($request->canal)) {
                return redirect('/')->with('mensaje', 'No tienes acceso al canal seleccionado.');
            }
            $recursos = Recurso::where('canal_id', $request->canal)->get();
        } else {
            $recursos = Recurso::whereIn('canal_id', $canalIds)->get();
        }

        $stats = [
            'recursos' => Recurso::whereIn('canal_id', $canalIds)->count(),
            'canales'  => $canales->count(),
            'usuarios' => \App\Models\User::count(),
        ];

        return view('recursos.index', ['recursos' => $recursos, 'canales' => $canales, 'stats' => $stats]);
    }

    public function show(Recurso $recurso){
        $user = auth()->user();
        if ($user && $user->rol !== 'admin') {
            if ($user->rol === 'maestro') {
                // Maestro dueño del canal o inscrito a él
                $esDuenio = $recurso->canal->maestro_id === $user->id;
                $esSuscrito = \App\Models\Inscripcion::where('user_id', $user->id)
                                                     ->where('canal_id', $recurso->canal_id)
                                                     ->exists();
                if (!$esDuenio && !$esSuscrito) {
                    return redirect('/')->with('mensaje', 'No estás inscrito en el canal de este recurso.');
                }
            } else {
                // Estudiante inscrito
                $esSuscrito = \App\Models\Inscripcion::where('user_id', $user->id)
                                                     ->where('canal_id', $recurso->canal_id)
                                                     ->exists();
                if (!$esSuscrito) {
                    return redirect('/')->with('mensaje', 'No estás inscrito en el canal de este recurso.');
                }
            }
        }
        return view('recursos.show', ['recurso' => $recurso]);
    }

    public function create(){
        if (Gate::denies('create-content')) {
            return redirect('/')->with('mensaje', 'No tienes permiso para subir recursos.');
        }

        $user = auth()->user();
        if ($user->rol == 'admin') {
            $canales = Canal::all();
        } else {
            $canales = Canal::where('maestro_id', $user->id)->get();
        }

        return view('admin.create', ['canales' => $canales]);
    }

    public function store(RecursoRequest $request){
        if (Gate::denies('create-content')) {
            return redirect('/')->with('mensaje', 'No tienes permiso para subir recursos.');
        }

        $canal = Canal::findOrFail($request->canal_id);
        if (Gate::denies('manage-canal', $canal)) {
            return redirect('/')->with('mensaje', 'No puedes subir recursos a un canal que no es tuyo.');
        }

        // El archivo se guarda aparte porque necesita ir a storage
        $ruta = $request->file('archivo')->store('recursos', 'public');

        Recurso::create($request->except('archivo') + ['archivo' => $ruta]);

        return redirect('/')->with('mensaje', 'Recurso subido correctamente.');
    }

    public function edit(Recurso $recurso){
        if (Gate::denies('manage-recurso', $recurso)) {
            return redirect('/')->with('mensaje', 'No tienes permiso para editar este recurso.');
        }

        $canales = Canal::all();
        return view('recursos.edit', ['recurso' => $recurso, 'canales' => $canales]);
    }

    public function update(\Illuminate\Http\Request $request, Recurso $recurso){
        if (Gate::denies('manage-recurso', $recurso)) {
            return redirect('/')->with('mensaje', 'No tienes permiso para editar este recurso.');
        }

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'canal_id' => 'required|exists:canals,id',
            'tipo' => 'required|in:video,pdf,documento,otro',
            'archivo' => 'nullable|file', // Opcional al editar
        ]);

        $data = $request->except('archivo');

        if ($request->hasFile('archivo')) {
            // Eliminar archivo viejo si existe
            if ($recurso->archivo && \Illuminate\Support\Facades\Storage::disk('public')->exists($recurso->archivo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($recurso->archivo);
            }
            $ruta = $request->file('archivo')->store('recursos', 'public');
            $data['archivo'] = $ruta;
        }

        $recurso->update($data);

        return redirect()->route('recursos.show', $recurso->id)->with('mensaje', 'Recurso actualizado exitosamente.');
    }

    public function destroy(Recurso $recurso){
        if (Gate::denies('manage-recurso', $recurso)) {
            return redirect('/')->with('mensaje', 'No tienes permiso para eliminar este recurso.');
        }

        if ($recurso->archivo && \Illuminate\Support\Facades\Storage::disk('public')->exists($recurso->archivo)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($recurso->archivo);
        }

        $recurso->delete();

        return redirect('/')->with('mensaje', 'Recurso eliminado correctamente.');
    }
}
