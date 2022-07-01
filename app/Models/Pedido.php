<?php

namespace App\Models;

use DateTime;
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

    // funcion para obtener los dias restantes de entrega

    public function getDiasAttribute()
    {
        $actual = date_create(date('Y-m-d'));
    
        $entrega = date_create($this->fecha_entrega);
    
        if ($actual > $entrega) return 0;
        $dias = 0;
        while ($actual <= $entrega) {
    
            $day_week = $actual->format('w');
           
            if ($day_week > 0 && $day_week < 6) {
                $dias += 1;
            }
            date_add($actual, date_interval_create_from_date_string('1 day'));
        }
        return $dias -1;        
    }

    // obtener el atributo cantidad_total de items para el pedido 
    public function getItemsPedidoAttribute()
    {
        $existencias = OrdenProduccion::join('items','items.id','=','ordenes_produccion.item_id')
                                        ->where('pedido_id',$this->id)
                                        ->get(['cantidad', 'item_id']);        

        $pedido = Pedido::where('id',$this->id)->get(['id','diseno_producto_final_id','cantidad']);
        $pedido = $pedido[0];                               
        $items = DisenoItem::join('items','items.id','=','diseno_items.item_id')
                                    ->where('diseno_producto_final_id', $pedido->diseno_producto_final_id)
                                    ->get([
                                        'diseno_items.id',
                                        'items.descripcion',
                                        'diseno_items.cantidad',
                                        'items.existencias',
                                        'items.id as item_id',
                                        ]);
        $items_pedido = collect();
        
        foreach ($items as $item) {
           
            $existencia = $existencias->firstWhere('item_id',$item->item_id);
            if (empty($existencia)) {
                $items_pedido->push((object)[
                    'id' => $item->id,
                    'descripcion' => $item->descripcion,
                    'cantidad' => $item->cantidad,
                    'existencias' => $item->existencias,
                    'total' => $item->cantidad * $pedido->cantidad,
                    'item_id' => $item->item_id,
                    
                ]);
            } else {
                $items_pedido->push((object)[
                    'id' => $item->id,
                    'descripcion' => $item->descripcion,
                    'cantidad' => $item->cantidad,
                    'existencias' => $item->existencias,
                    'total' => $item->cantidad * $pedido->cantidad - $existencia->cantidad,
                    'item_id' => $item->item_id,
                    
                ]);
            }
           
        }
       
       return (object)$items_pedido;
       
    }

    // retornar los datos del pedido con cliente y el diseño final 

    public function datos()
    {
        $pedido = Pedido::join('clientes','pedidos.cliente_id','=','clientes.id')
                            ->join('diseno_producto_finales','pedidos.diseno_producto_final_id','=','diseno_producto_finales.id')
                            ->where('pedidos.id', $this->id)
                            ->orderBy('pedidos.fecha_entrega','asc')
                            ->get([ 
                                    'pedidos.id',
                                    'pedidos.cantidad',
                                    'pedidos.created_at',
                                    'pedidos.fecha_entrega',
                                    'pedidos.estado',
                                    'clientes.nombre',
                                    'diseno_producto_finales.descripcion',  
                                    'diseno_producto_finales.id as diseno_id',                                  
                                ]);
        $pedido = $pedido[0];

        return $pedido;
    }
    
}
