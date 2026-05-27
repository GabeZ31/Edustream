<?php

use Illuminate\Support\Facades\Route;


// INICIO
use App\Http\Controllers\RecursoController;

Route::get('/', [RecursoController::class, 'index'])->name('inicio');

// VER EL CONTENIDO
Route::get('/recursos/{recurso}', [RecursoController::class, 'show'])->name('recursos.show');

use App\Http\Controllers\CanalController;
use App\Http\Controllers\AdminController;

// RUTAS PROTEGIDAS CON AUTH
Route::middleware(['auth'])->group(function () {
    // ADMIN Y SUBIDA DE RECURSOS
    Route::get('/admin/recursos/create', [RecursoController::class, 'create'])->name('admin.create');
    Route::post('/admin/recursos', [RecursoController::class, 'store'])->name('admin.store');

    // EDICION Y ELIMINACION DE RECURSOS (Maestro y Admin)
    Route::get('/recursos/{recurso}/edit', [RecursoController::class, 'edit'])->name('recursos.edit');
    Route::put('/recursos/{recurso}', [RecursoController::class, 'update'])->name('recursos.update');
    Route::delete('/recursos/{recurso}', [RecursoController::class, 'destroy'])->name('recursos.destroy');

    // CANALES (Maestros)
    Route::resource('canales', CanalController::class)->except(['show']);
    Route::post('/canales/inscribir', [CanalController::class, 'inscribir'])->name('canales.inscribir');

    // PERFIL DE USUARIO
    Route::get('/perfil', [\App\Http\Controllers\PerfilController::class, 'index'])->name('perfil.index');
    Route::put('/perfil', [\App\Http\Controllers\PerfilController::class, 'update'])->name('perfil.update');

    // RUTAS SOLO ADMIN
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::put('/admin/usuarios/{user}/rol', [AdminController::class, 'updateRole'])->name('admin.usuarios.rol');
        Route::delete('/admin/usuarios/{user}', [AdminController::class, 'destroyUser'])->name('admin.usuarios.destroy');
    });

    // COMENTARIOS
    Route::post('/recursos/{recurso}/comentarios', [\App\Http\Controllers\ComentarioController::class, 'store'])->name('comentarios.store');
    Route::delete('/comentarios/{comentario}', [\App\Http\Controllers\ComentarioController::class, 'destroy'])->name('comentarios.destroy');
});


// SESION
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/registro', [AuthController::class, 'registroForm'])->name('registro');
Route::post('/registro', [AuthController::class, 'registro'])->middleware('throttle:5,10')->name('registro.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');