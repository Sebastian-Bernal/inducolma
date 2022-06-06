<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    //relacion de pedido pertenece a un cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class,'id');
    }

    //relacion de pedido pertenece a un diseño
    public function nombre_diseno()
    {
        return $this->belongsTo(DisenoProductoFinal::class);
    }
    
}
