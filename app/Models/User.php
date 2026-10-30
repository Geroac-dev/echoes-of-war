<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $guarded = [];
    
    // Indicamos explícitamente que la llave primaria se llama 'id' y no es autoincremental
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    
    // Desactivamos los timestamps (created_at / updated_at) de Eloquent
    public $timestamps = false;
}