<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model{
protected $table = 'inscripciones';
protected $fillable = ['user_id', 'canal_id'];

// PERTENECE A UN USUARIO
public function user(){
    return $this->belongsTo(User::class);
}

// PERTENECE A UN CANAL
public function canal(){
    return $this->belongsTo(Canal::class);
}
}