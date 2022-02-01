<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostosInfraestructura extends Model
{
    use HasFactory;
    protected $fillable = [
            'id',
            'valor_operativo',
            'tipo_material',
            'tipo_madera',
            'proceso_madera',
            'promedio_piezas',
            'minimo_piezas',
            'maximo_piezas',
            'maquina_id',
            ];
    public function maquina()
    {
        return $this->belongsTo(Maquina::class);
    }
    
}
