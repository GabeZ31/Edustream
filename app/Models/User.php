<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable{
/** @use HasFactory<UserFactory> */
use HasFactory, Notifiable;

protected $fillable = [
    'name',
    'email',
    'password',
    'rol',
];

protected $hidden = [
    'password',
    'remember_token',
];

protected function casts(): array{
    return [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}

// UN USER (MAESTRO) TIENE MUCHOS CANALES
public function canales(){
    return $this->hasMany(Canal::class, 'maestro_id');
}

// UN USER TIENE MUCHAS INSCRIPCIONES
public function inscripciones(){
    return $this->hasMany(Inscripcion::class);
}

// UN USER TIENE MUCHOS COMENTARIOS
public function comentarios(){
    return $this->hasMany(Comentario::class);
}
}
