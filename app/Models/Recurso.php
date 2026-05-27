<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recurso extends Model{
    use HasFactory;
protected $table = 'recursos';
protected $fillable = [
    'titulo',
    'canal_id',
    'descripcion',
    'tipo',
    'archivo',
    'torrent',
    'portada',
];

// Un recurso pertenece a un canal
public function canal(){
    return $this->belongsTo(Canal::class);
}

// Un recurso tiene muchos comentarios
public function comentarios(){
    return $this->hasMany(Comentario::class)->orderBy('created_at', 'asc');
}
}