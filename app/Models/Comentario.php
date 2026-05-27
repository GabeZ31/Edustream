<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comentario extends Model
{
    use HasFactory;

    protected $table = 'comentarios';

    protected $fillable = [
        'user_id',
        'recurso_id',
        'contenido',
    ];

    // UN COMENTARIO PERTENECE A UN USUARIO
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // UN COMENTARIO PERTENECE A UN RECURSO
    public function recurso()
    {
        return $this->belongsTo(Recurso::class);
    }
}
