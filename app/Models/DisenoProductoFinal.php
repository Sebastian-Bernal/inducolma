<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DisenoProductoFinal extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'diseno_producto_finales';


    /**
     * relacion diseño_producto_finales hasOne maderas
     */
    public function madera()
    {
        return $this->belongsTo(Madera::class);
    }

    /**
     * relacion diseño_producto_finales belongsTo user
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * relacion diseño_producto_finales belongsToMany clientes
     */
    public function clientes()
    {
        return $this->belongsToMany(Cliente::class,'diseno_cliente');
    }

    /**
     * relacion diseno_producto_finales belongsToMany diseño_items
     */

    public function items()
    {
        return $this->belongsToMany(Item::class,'diseno_items')
                    ->select([
                        'items.id',
                        'descripcion',
                        'alto',
                        'largo',
                        'ancho',
                        'diseno_producto_final_id',
                        'item_id',
                        'preprocesado',
                        'madera_id',
                        'existencias',
                        'diseno_items.cantidad']);
    }

    /**
     * relacaion diseño_producto_finales belongsToMany diseño_insumos
     */

    public function insumos()
    {
        return $this->belongsToMany(InsumosAlmacen::class,'diseno_insumos', 'diseno_producto_final_id', 'insumo_almacen_id')
                    ->select(['diseno_producto_final_id', 'insumo_almacen_id', 'diseno_insumos.cantidad', 'descripcion']);

    }

    /**
     * relacion diseño_producto_finales belongsTo tipo_madera
     */

    public function tipo_madera()
    {
        return $this->belongsTo(TipoMadera::class);
    }
    /**
     * relacion diseño_producto_finales hasMany pedidos
     */

    public function pedidos()
    {
        return $this->hasMany(Pedido::class,'diseno_producto_final_id');
    }

}

