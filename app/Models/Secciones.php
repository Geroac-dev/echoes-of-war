<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Secciones extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'contenido_id',
        'titulo',
        'texto',
        'posicion',
        'orden',
    ];
    public function imagenes(){
        return $this->hasMany(Imagenes::class, 'seccion_id', 'id');
    }
}
