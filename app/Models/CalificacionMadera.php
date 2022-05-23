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
    public function entradaMadera(){
        return $this->belongsTo(EntradaMadera::class);
    }
}
