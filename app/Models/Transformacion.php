<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transformacion extends Model
{
    use HasFactory;
    protected $table = 'transformaciones';


    /**
     * relacion hasOne con OrdenProduccion
     */

    public function orden()
    {
        return $this->belongsTo(OrdenProduccion::class);
    }

    /**
     * relacion belongsTo cubicajes
     */

    public function cubicaje()
    {
        return $this->belongsTo(cubicaje::class);
    }


    /**
     * returns trnasformacion_final attribute
     * @return string
     */
    public function getTipoAttribute(): ?string
    {
        return $this->trnasformacion_final;
    }
}
