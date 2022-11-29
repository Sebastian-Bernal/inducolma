<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalificacionMadera extends Model
{
    use HasFactory;

    /**
     * relacion calificacion madera belongsTo entrada madera
     */
    public function entradaMaderaMadera(){
        return $this->belongsTo(EntradasMaderaMaderas::class);
    }

    /**
     * relacion belongsTo entradas_madera_maderas
     *
     */

    public function entrada_madera_madera()
    {
        return $this->belongsTo(EntradasMaderaMaderas::class, 'entrada_madera_id', 'id', );
    }
}
