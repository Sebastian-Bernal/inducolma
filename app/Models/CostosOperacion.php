<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostosOperacion extends Model
{
    protected $fillable = ['cantidad', 'valor_mes', 'valor_dia', 'costo_kwh', 'maquina_id', 'descripcion_id'];
    protected $table = 'costos_operacion';
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
