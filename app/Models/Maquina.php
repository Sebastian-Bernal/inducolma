<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maquina extends Model
{
    use HasFactory;

    public function costos_operacion()
    {
        return $this->hasMany(CostosOperacion::class);
    }

    public function costos_infraestructura()
    {
        return $this->hasOne(CostosInfraestructura::class);
    }
    
}
