<?php

namespace App\Models;

use App\Traits\CheckRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Madera extends Model
{
    use HasFactory, CheckRelations;
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
        return $this->hasMany(EntradasMaderaMaderas::class);
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

    /**
     * relacion maderas belongsTo tipo_maderas
     */
   public function tipo_madera()
    {
        return $this->belongsTo(TipoMadera::class,);
    }
}
