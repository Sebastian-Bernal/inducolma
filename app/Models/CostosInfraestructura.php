<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostosInfraestructura extends Model
{
    use HasFactory;

    public function maquina()
    {
        return $this->belongsTo(Maquina::class);
    }
    
}
