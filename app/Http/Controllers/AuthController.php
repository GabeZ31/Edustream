<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistroRequest;

class AuthController extends Controller{

    // FORMULARIO DE LOGIN
    public function loginForm(){
        return view('auth.login');
    }

    // PROCESAR LOGIN
    public function login(LoginRequest $request){
        $throttleKey = strtolower($request->email) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            Log::warning("Intento de login bloqueado por fuerza bruta para: " . $request->email . " desde IP: " . $request->ip());
            return back()->withErrors([
                'email' => "Demasiados intentos de inicio de sesión. Por favor intente de nuevo en {$seconds} segundos."
            ]);
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            return redirect('/');
        }

        RateLimiter::hit($throttleKey, 60);
        Log::notice("Intento de login fallido para: " . $request->email . " desde IP: " . $request->ip());

        return back()->withErrors(['email' => 'Credenciales incorrectas.']);
    }

    // FORMULARIO DE REGISTRO
    public function registroForm(){
        return view('auth.registro');
    }

    // PROCESAR REGISTRO
    public function registro(RegistroRequest $request){
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'rol'      => $request->rol,
        ]);
        Auth::login($user);
        return redirect('/');
    }

    // LOGOUT
    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}