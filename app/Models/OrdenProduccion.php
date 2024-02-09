<?php

namespace App\Models;

use App\Traits\CheckRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdenProduccion extends Model
{
    use HasFactory,  CheckRelations;
    protected $table = 'ordenes_produccion';
    protected $fillable = [
        'user_id',
        'estado',
    ];
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
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    /**
     * relacion hasMany transformaciones
     */

    public function transformaciones()
    {
        return $this->hasMany(Transformacion::class);
    }

    /**
     * relacion hasMany Procesos
     */

    public function procesos()
    {
        return $this->hasMany(Proceso::class);
    }

}
