<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contenidos extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['categoria_id', 'nombre', 'descripcion', 'orden', 'slug', 'imagen'];
}
