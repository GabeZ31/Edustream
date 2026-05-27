<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Recurso;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Obtener metricas
        $usuarios = User::orderBy('created_at', 'desc')->get();
        $recursos = Recurso::with('canal.maestro')->orderBy('created_at', 'desc')->get();

        return view('admin.dashboard', compact('usuarios', 'recursos'));
    }

    public function updateRole(Request $request, User $user)
    {

        $request->validate([
            'rol' => 'required|in:admin,maestro,estudiante',
        ]);

        // Evitar que el admin se quite el rol a sí mismo por error (si es el único)
        if ($user->id === auth()->user()->id && $request->rol != 'admin') {
            return redirect()->back()->with('mensaje', 'No puedes quitarte el rol de administrador a ti mismo.');
        }

        $user->update(['rol' => $request->rol]);

        return redirect()->back()->with('mensaje', 'Rol actualizado correctamente.');
    }

    public function destroyUser(User $user)
    {

        if ($user->id === auth()->user()->id) {
            return redirect()->back()->with('mensaje', 'No puedes eliminarte a ti mismo.');
        }

        $user->delete();

        return redirect()->back()->with('mensaje', 'Usuario eliminado correctamente.');
    }
}
