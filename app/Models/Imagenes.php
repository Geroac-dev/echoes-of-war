<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Imagenes extends Model
{
    protected $keyType = 'string';
    protected $fillable = [
        'seccion_id',
        'ruta',
    ];
}
