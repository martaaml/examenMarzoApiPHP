<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Canciones extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'id_canciones',
        'titulo',
        'artista',
        'album',
        'anio'
    ];
   public function cancion()
    {
        return $this->belongsTo(Canciones::class);
    }
    public function getAnioFormateadoAttribute()
    {
        return $this->anio;
    }
    public function getTituloFormateadoAttribute()
    {
        return $this->titulo;
    }
    public function getArtistaFormateadoAttribute()
    {
        return $this->artista;
    }
    public function getAlbumFormateadoAttribute()
    {
        return $this->album;
    }
}
