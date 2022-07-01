<?php 

namespace App\Repositories;

use App\Models\Cubicaje;
use App\Models\DisenoItem;
use App\Models\Pedido;
use App\Models\OrdenProduccion;
use App\Models\Transformacion;

class MaderasOptimas {

    public function Optimas($request)
    {
        
        $existencias_produccion = OrdenProduccion::join('items','items.id','=','ordenes_produccion.item_id')
                                        ->where('pedido_id',(int)$request->id_pedido)
                                        ->where('item_id',(int)$request->id_item)
                                        ->get(['cantidad', 'item_id']); 

        $pedido = Pedido::select('cantidad','id','diseno_producto_final_id')->find($request->id_pedido);

        $item_diseno = DisenoItem::join('items','items.id','=','diseno_items.item_id')
                                    ->where('diseno_producto_final_id',$pedido->diseno_producto_final_id)
                                    ->where('item_id',$request->id_item)
                                    ->first(['cantidad','descripcion','existencias','largo','ancho','alto','item_id','madera_id']);
       // return $item_diseno;
                                  
        if (!isset($existencias_produccion[0])) {
            return 'ninguna existencia en produccion';
        } else {
            $cantidad_produccion = 0;
            foreach ($existencias_produccion as $existencia_produccion) {
                $cantidad_produccion += $existencia_produccion->cantidad;
            }
            
        }
       // $producir = $pedido->cantidad - $cantidad_produccion;
        $sobrantes = $this->Sobrantes($item_diseno);
        $maderas = $this->Maderas($item_diseno);
       //return $sobrantes->count();

       if ($maderas->count() == 0 && $sobrantes->count() == 0) {
            return 'no hay maderas disponibles';
       } else{
             //return $maderas_usar = $this->MaderasUsar($maderas, $item_diseno);
            $sobrantes_usar = $this->sobrantesUsar($sobrantes, $item_diseno);
       }

       


    }

    // Funcion SObrantes(), retorna una coleccion de items que se pueden producir
    // con los sobrantes que cumplen con largo = $item_diseno->largo, ancho > ($item_diseno->ancho + 0.5), alto > ($item_diseno->alto +0.5)
    // y madera = $item_diseno->madera_id

    public function Sobrantes($item_diseno)
    {
        $sobrantes = Transformacion::where('trnasformacion_final','SOBRANTE')
                            ->where('largo',(int)$item_diseno->largo)
                            ->where('ancho','>',(int)$item_diseno->ancho + 0.5)
                            ->where('alto','>',(int)$item_diseno->alto + 0.5)
                            ->where('madera_id',(int)$item_diseno->madera_id)
                            ->get();
        return $sobrantes;

    }

    /**
     * Funcion Maderas(), retorna una coleccion de maderas de la tabla cubicajes que cumplen con las siguientes condiciones:
     * largo = $item_diseno->largo, ancho > ($item_diseno->ancho + 0.5), alto > ($item_diseno->alto +0.5)
     * y madera = $item_diseno->madera_id
     */

    public function Maderas($item_diseno)
    {
        $maderas = Cubicaje::join('entradas_madera_maderas','entradas_madera_maderas.entrada_madera_id','=','cubicajes.entrada_madera_id')
                            ->where('largo',$item_diseno->largo)
                            ->where('ancho','>',$item_diseno->ancho + 0.5)
                            ->where('alto','>',$item_diseno->alto + 0.5)
                            ->where('entradas_madera_maderas.madera_id',$item_diseno->madera_id)
                            ->get(['cubicajes.id',
                                    'cubicajes.bloque',
                                    'cubicajes.paqueta',
                                    'cubicajes.largo',
                                    'cubicajes.ancho',
                                    'cubicajes.alto',
                                    'cubicajes.pulgadas_cuadradas',
                                    'cubicajes.pulgadas_cuadradas_x3_metros',
                                   
                                ]);
        return $maderas;
    }

    /**
     * funcion sobrantesUsar(), agrega a cada elemto de la coleccion $maderas 
     * cantidad_items, porcentaje_uso, desperdicio
     */

    public function sobrantesUsar($sobrantes, $item_diseno)
    {
        $sobrantes_usar = [];
        
        foreach ($sobrantes as $sobrante) {
            $sobrante->cantidad_items = (int)(($sobrante->ancho/$item_diseno->ancho + $sobrante->alto/$item_diseno->alto ));//* $sobrante->cantidad
            $sobrante->porcentaje_uso = (int)(($sobrante->cantidad_items * $item_diseno->alto * $item_diseno->ancho * $item_diseno->largo)/($sobrante->alto*$sobrante->largo*$sobrante->ancho)*100) ;
            $sobrante->desperdicio = (int)(100 - $sobrante->porcentaje_uso) ;
            $sobrantes_usar[] = $sobrante;
        }
        return $sobrantes_usar;
    }

    /**
     * Funcion maderasUsar(), agrega a cada elemto de la coleccion $sobrantes
     * cantidad_items, porcentaje_uso, desperdicio
     */

    public function maderasUsar($maderas, $item_diseno){
        $maderas_usar = [];
        foreach ($maderas as $madera) {
            $madera->cantidad_largo = (int)($madera->largo/$item_diseno->largo);
            $madera->restante_largo = (int)($madera->largo-($item_diseno->largo * $madera->cantidad_largo));
            if ($madera->restante_largo > 15) {
                $madera->sobrante_largo = $madera->restante_largo;
            } else {
                $madera->desperdicio_largo = $madera->restante_largo;
            }
            
            $maderas_usar[] = $madera;
        }
        return $maderas_usar;
    }

}