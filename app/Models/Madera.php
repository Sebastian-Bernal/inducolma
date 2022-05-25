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

    /**
     * relacion entradas_madera_maderas hasMany maderas
     * 
     */
    public function entradas_madera_maderas()
    {
       // return $this->belongsToMany(EntradasMaderaMaderas::class, 'madera_id');
    }

    /**
     * relacion maderas hasMany costos_infraestructura
     */
    public function costos_infraestructura()
    {
        return $this->hasMany(CostosInfraestructura::class, 'tipo_madera');
    }
    
    /**
     * relacion maderas hasMany items
     */

    public function items()
    {
        return $this->hasMany(Item::class, 'tipo_madera');
    }

    /**
     * relacion maderas belongsTo diseño_producto_finales
     */
    public function diseño_producto_finales()
    {
        return $this->belongsTo(DiseñoProductoFinal::class, 'madera_id');
    }

}
