<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

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
     
}
