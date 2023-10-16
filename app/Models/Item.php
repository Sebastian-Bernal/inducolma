<?php

namespace App\Models;

use App\Traits\CheckRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes, CheckRelations;

    /**
     * relacion items hasMany costos_infraestructura

     */
    public function costos_infraestructura()
    {
        return $this->hasMany(CostosInfraestructura::class,'id');
    }

    /**
     * relacion items belongsTo madera
     */
    // public function madera()
    // {
    //     return $this->belongsTo(Madera::class, 'madera_id');
    // }

    /**
     * relacion items belongsTo tipos_maderas
     */

    public function tipo_madera()
    {
        return $this->belongsTo(TipoMadera::class, 'madera_id')->withTrashed();
    }

    /**
     * relacion hasOne OrdenProduccion
     */
    public function orden_produccion()
    {
        return $this->hasOne(OrdenProduccion::class);

    }
}
