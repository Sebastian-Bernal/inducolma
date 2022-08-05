<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory, SoftDeletes ;
    protected $table = 'clientes';

    // relacion de cliente con pedido
    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    /**
     * relacion  cliente con diseño_producto_finales
     *
     */
    public function disenos()
    {
        return $this->belongsToMany(DisenoProductoFinal::class,'diseno_cliente');
    }
}
