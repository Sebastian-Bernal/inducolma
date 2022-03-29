<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Madera extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'nombre_cientifico', 'densidad'];


    /**
     * Funcion que guarda el nombre de la madera en mayusculas
     */
    public function setNombreAttribute($value)
    {
        $this->attributes['nombre'] = strtoupper($value);
    }
    /**
     * Funcion que guarda el nombre cientifico de la madera en mayusculas
     */
    public function setNombreCientificoAttribute($value)
    {
        $this->attributes['nombre_cientifico'] = strtoupper($value);
    }
}
