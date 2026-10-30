<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorias extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['nombre', 'descripcion', 'orden'];
}
