<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenProduccion extends Model
{
    use HasFactory;
    protected $table = 'ordenes_produccion';

    /**
     * relacion belongsTo Item
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * relacion belongsTo Pedido
     */

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    /**
     * relacion hasMany transformaciones
     */

    public function transformaciones()
    {
        return $this->hasMany(Transformacion::class);
    }

}
