<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class lista_canciones extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'id_lista',
        'id_cancion',
    ];
    public function canciones()
    {
        return $this->belongsTo(Canciones::class);
    }
    public function getIdCancionFormateadoAttribute()
    {
        return $this->id_cancion;
    }
    public function getIdListaFormateadoAttribute()
    {
        return $this->id_lista;
    }
    
}
