<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostosOperacion extends Model
{
    use HasFactory;

    public function maquina()
    {
        return $this->belongsTo(Maquina::class);
    }
    
    public function descripcion()
    {
        return $this->belongsTo(Descripcion::class);
    }
}
