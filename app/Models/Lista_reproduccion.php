<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class lista_reproduccion extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'id_lista',
        'nombre',
        'descripcion',
        'fecha_creacion',
    ];

    public function lista_reproduccion()
    {
        return $this->belongsTo(lista_reproduccion::class);
    }
    public function getNombreFormateadoAttribute()
    {
        return $this->nombre;
    }
    public function getDescripcionFormateadoAttribute()
    {
        return $this->descripcion;
    }

    public function getFechaCreacionFormateadoAttribute()
    {
        return date('d/m/Y', strtotime($this->fecha_creacion));
    }

}
