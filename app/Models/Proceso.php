<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proceso extends Model
{
    use HasFactory;

    /**
     * relacion belongsTo cubicaje
     */
    public function cubicaje()
    {
        return $this->belongsTo(Cubicaje::class);
    }

    /**
     * relacion belongsTo ordenProduccion
     */

    public function orden_produccion()
    {
        return $this->belongsTo(ordenProduccion::class);
    }
    /**
     * relacion belongsTo items
     *
     */

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * relacion belongsTo maquinas
     */

    public function maquina()
    {
        return $this->belongsTo(Maquina::class);
    }

    /**
     * relation has many subprocreso::class
     */
    public function subprocesos() :?HasMany {
        return $this->hasMany(Subproceso::class);
    }
}
