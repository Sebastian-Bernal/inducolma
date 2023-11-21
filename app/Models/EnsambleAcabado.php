<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnsambleAcabado extends Model
{
    use HasFactory;

    protected $table = 'ensambles_acabados';
    protected $fillable = [ 'cantidad', 'observaciones', 'fecha_inicio', 'fecha_fin', 'estado', 'pedido_id', 'maquina_id', 'maquina_id', 'user_id'];


    /**
     * relation with pedidos
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);

    }

    /**
     * relation with maquinas
     */
    public function maquina()
    {
        return $this->belongsTo(Maquina::class);

    }

    /**
     * relation with users
     */
    public function user()
    {
        return $this->belongsTo(User::class);

    }
}
