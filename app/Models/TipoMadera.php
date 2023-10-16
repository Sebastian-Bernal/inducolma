<?php

namespace App\Models;

use App\Traits\CheckRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoMadera extends Model
{
    use HasFactory, SoftDeletes, CheckRelations;

    /**
     * Relacion tipo_maderas hasMany maderas
     */

    public function maderas()
    {
        return $this->hasMany(Madera::class);
    }

    /**
     * Relacion tipo_maderas hasMany items
     */

    public function items()
    {
        return $this->hasMany(Item::class,'madera_id');
    }

    /**
     * Relacion tipo_maderas hasMany diseno_producto_finales
     */

    public function disenos()
    {
        return $this->hasMany(DisenoProductoFinal::class);
    }
}
