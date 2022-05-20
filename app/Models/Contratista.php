<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contratista extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Funcion nombre_completo que concatena los nombres y apellidos
     * @return string
     */

    public function getNombreCompletoAttribute()
    {
        return $this->primer_nombre . ' ' .$this->segundo_nombre.' '. $this->primer_apellido. ' ' . $this->segundo_apellido;
    }
    
}
