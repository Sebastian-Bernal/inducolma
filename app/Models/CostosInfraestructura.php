<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostosInfraestructura extends Model
{
    use HasFactory;
    protected $fillable = [
            'id',
            'tipo_material',
            'tipo_madera',
            'maquina_id',
            'estandar_u_minuto'
            ];
    /**
     * relacion costos_infraestructura belongsTo Maquina
     */
    public function maquina()
    {
        return $this->belongsTo(Maquina::class);
    }

    /**
     * relacion costos_infraestructura hasOne Item
     */

    public function item()
    {
        return $this->belongsTo(Item::class, 'tipo_material');
    }

    /**
     * relacion costos_infraestructura hasOne Madera
     */
    public function madera()
    {
        return $this->belongsTo(TipoMadera::class, 'tipo_madera');
    }
}
