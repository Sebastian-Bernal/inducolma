<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operacion extends Model
{
    use HasFactory;

    public function descripciones()
    {
        return $this->hasMany(Descripcion::class);
    }
    
    
}
