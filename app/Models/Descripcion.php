<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Descripcion extends Model
{
    protected $table = 'descripciones';
    use HasFactory;
    public function operacion()
    {
        return $this->belongsTo(Operacion::class);
    }


    public function costos_operacion()
    {
        return $this->hasMany(CostosOperacion::class);
    }
    
}
