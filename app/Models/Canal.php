<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Canal extends Model{
    use HasFactory;
protected $table = 'canals';
protected $fillable = [
    'nombre',
    'descripcion',
    'maestro_id',
    'codigo_acceso',
];

// Un canal tiene MUCHOS -- recursos --
public function recursos(){
    return $this->hasMany(Recurso::class);
}

// UN CANAL PERTENECE A UN MAESTRO
public function maestro(){
    return $this->belongsTo(User::class, 'maestro_id');
}

// UN CANAL TIENE MUCHOS ESTUDIANTES INSCRITOS
public function estudiantes(){
    return $this->hasMany(Inscripcion::class);
}
}