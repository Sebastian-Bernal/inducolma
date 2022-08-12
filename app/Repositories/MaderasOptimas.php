<?php

namespace App\Repositories;

use App\Models\Cubicaje;
use App\Models\DisenoItem;
use App\Models\Pedido;
use App\Models\OrdenProduccion;
use App\Models\Transformacion;
use Illuminate\Database\Eloquent\Collection;
use PhpParser\Node\Expr\Cast\Object_;

use function PHPUnit\Framework\returnSelf;

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

        $sobrantes = $this->Sobrantes($item_diseno);
        $maderas = $this->Maderas($item_diseno);

       if ($maderas->count() == 0 && $sobrantes->count() == 0) {
            return ['status' => 'No hay maderas disponibles.'];
       } else{

             return [
                'maderas_usar' => $maderas_usar = $this->corteInicial($maderas, $item_diseno),
                'sobrantes_usar' => $sobrantes_usar = $this->sobrantesUsar($sobrantes, $item_diseno),
                'item' => $item_diseno,
                //'status' => 'maderas disponibles',
             ];
       }
    }

    // Funcion SObrantes(), retorna una coleccion de items que se pueden producir
    // con los sobrantes que cumplen con largo = $item_diseno->largo, ancho > ($item_diseno->ancho + 0.5), alto > ($item_diseno->alto +0.5)
    // y madera = $item_diseno->madera_id
    // que no esten en uso.

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
     * calificacion eprovado  = true hacer join
     */

    public function Maderas($item_diseno)
    {
        $maderas = Cubicaje::join('entradas_madera_maderas','entradas_madera_maderas.entrada_madera_id','=','cubicajes.entrada_madera_id')
                            ->join('maderas','maderas.id','=','entradas_madera_maderas.madera_id')
                            ->join('calificacion_maderas','calificacion_maderas.entrada_madera_id','=','entradas_madera_maderas.entrada_madera_id')
                            ->where('largo','>=',$item_diseno->largo)
                            ->where('ancho','>',$item_diseno->ancho + 0.5)
                            ->where('alto','>',$item_diseno->alto + 0.5)
                            ->where('maderas.tipo_madera_id',$item_diseno->madera_id)
                            ->where('calificacion_maderas.aprobado','=','true')
                            ->get(['cubicajes.id',
                                    'cubicajes.bloque',
                                    'cubicajes.paqueta',
                                    'cubicajes.entrada_madera_id',
                                    'cubicajes.largo',
                                    'cubicajes.ancho',
                                    'cubicajes.alto',
                                    'cubicajes.pulgadas_cuadradas',
                                    'cubicajes.cm3',
                                    'calificacion_maderas.total',
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
     * Funcion corte1(), agrega a cada elemto de la coleccion $sobrantes
     * cantidad_items, porcentaje_uso, desperdicio
     */

    public function corteInicial($maderas, $item_diseno ){
        $corteInicial = [];

        foreach ($maderas as $madera) {
            $madera->cantidad_largo = (int)($madera->largo/$item_diseno->largo);

            $restante_largo = (int)($madera->largo-($item_diseno->largo * $madera->cantidad_largo));
            if ($restante_largo > 15) {
                $desperdicio_largo = 0;
                $sobrante_largo = $restante_largo;
            } else {
                $sobrante_largo = 0;
                $desperdicio_largo = $restante_largo;
            }

            if ($sobrante_largo > 0) {
                $madera->sobrante_largo = $sobrante_largo;
               // $madera += $madera->sobrante_largo;
            } else {
                $madera->desperdicio_largo = 0;
            }

            if ($desperdicio_largo > 0) {
               $madera += $madera->desperdicio_largo;
            } else {
                $madera->sobrante_largo = 0;
            }

            $corteInicial[] = $madera;
        }

        return $this->corteIntermedio($corteInicial, $item_diseno);
    }

    public function corteIntermedio($corteInicial, $item_diseno)
    {
        foreach ($corteInicial as $corte){
            $nuevo_corte = (object)[];
            $corte->cantidad_ancho = (int)$corte->ancho/$item_diseno->ancho * $corte->cantidad_largo;
            $restante_ancho = $corte->ancho - ($item_diseno->ancho * $corte->cantidad_ancho);

            if ($restante_ancho >= 15 && $restante_ancho <= 16) {
                $corte->sobrante_ancho = $restante_ancho;
            }else{
                if ($restante_ancho > 0.7 && $corte->alto > $item_diseno->ancho) {
                    $nuevo_corte->id = $corte->id;
                    $nuevo_corte->bloque = $corte->bloque;
                    $nuevo_corte->paqueta = $corte->paqueta;
                    $nuevo_corte->entrada_madera_id = $corte->entrada_madera_id;
                    $nuevo_corte->largo = $item_diseno->largo;
                    $nuevo_corte->ancho = $item_diseno->ancho;
                    $nuevo_corte->alto = $restante_ancho;
                    $nuevo_corte->cantidad_ancho_sobrante = (int)($corte->alto/$item_diseno->ancho)* $corte->cantidad_largo;

                    $restante_ancho_sobrante = $corte->alto -($item_diseno->ancho * $nuevo_corte->cantidad_ancho_sobrante);
                    if ($restante_ancho_sobrante >= 10) {
                        $nuevo_corte->sobrante = $restante_ancho_sobrante;
                    } else{
                        $nuevo_corte->desperdicio = $restante_ancho_sobrante;
                    }
                    $corteInicial[] = $nuevo_corte;
                } else{
                    if ($restante_ancho > 0.7) {
                        $corte->sobrante_ancho = $restante_ancho;
                    } else{
                        $corte->desperdicio_ancho = $restante_ancho;
                    }
                }
            }
        }

       return $this->corteFinal($corteInicial, $item_diseno);
    }

    public function corteFinal($corteInicial, $item_diseno)
    {
         // suma todos los cantidad_ancho de los elementos de la coleccion $corteInicial
         $cantidad_ancho = 0;
         foreach ($corteInicial as $corte) {
             $cantidad_ancho += $corte->cantidad_ancho;
         }
         if ($cantidad_ancho > 0) {
             foreach($corteInicial as $corte){
                 $corte->cantidad_items = (int)($corte->alto/$item_diseno->alto * $corte->cantidad_ancho);
                 $restante_item = $corte->alto - ($item_diseno->alto * $corte->cantidad_items);
                 if ($restante_item > 0.7) {
                     $corte->sobrante_item = $restante_item;
                 } else{
                     $corte->desperdicio_item = $restante_item;
                 }
             }

             // retornar


             $indice = 0;
             $items = 0;
             $cm3 = 0;
             $cm3_total = 0;

             for ($i=0; $i < count($corteInicial)-1 ; $i++) {

                if (isset($corteInicial[$i+1])) {

                    if ($corteInicial[$i+1]->entrada_madera_id == $corteInicial[$i]->entrada_madera_id
                        && $corteInicial[$i+1]->paqueta == $corteInicial[$i]->paqueta) {

                        $items += $corteInicial[$i]->cantidad_items;

                        $cm3 += $corteInicial[$i]->cm3;

                        if ($corteInicial[$i]->sobrante_largo > 0) {
                            $cm3_sobrante_largo = $corteInicial[$i]->sobrante_largo * $corteInicial[$i]->ancho * $corteInicial[$i]->sobrante->alto;
                        }else{
                            $cm3_sobrante_largo = 0;
                        }
                        if($corteInicial[$i]->sobrante_ancho > 0){
                            $cm3_sobrante_ancho = $corteInicial[$i]->sobrante_ancho * $item_diseno->largo * $corteInicial[$i]->alto * $corteInicial[$i]->cantidad_largo;
                        }else{
                            $cm3_sobrante_ancho = 0;
                        }

                        if ($corteInicial[$i]->sobrante_alto > 0) {
                            $cm3_sobrante_alto = $corteInicial[$i]->sobrante_alto * $item_diseno->ancho * $item_diseno->largo * $corteInicial[$i]->cantidad_ancho;
                        }else{
                            $cm3_sobrante_alto = 0;
                        }

                        $cm3_items = $item_diseno->alto * $item_diseno->ancho * $item_diseno->largo * $corteInicial[$i]->cantidad_items;
                        $cm3_total += $cm3_items + $cm3_sobrante_largo + $cm3_sobrante_ancho + $cm3_sobrante_alto;


                        $maderas_disponibles[$indice]['entrada_madera_id'] = $corteInicial[$i]->entrada_madera_id;
                        $maderas_disponibles[$indice]['paqueta'] = $corteInicial[$i]->paqueta;
                        $maderas_disponibles[$indice]['cantidad_items'] = $items;
                        $maderas_disponibles[$indice]['calificacion'] = (int)$corteInicial[$i]->total;
                        $maderas_disponibles[$indice]['cm3'] = $cm3;
                        $maderas_disponibles[$indice]['cm3_total'] = $cm3_total;
                        $maderas_disponibles[$indice]['veces_largo'] = $corteInicial[$i]->largo/$item_diseno->largo;


                    } else{
                        $items = 0;
                        $indice++;
                    }
                }

             }

             //$maderas_disponibles;
             for($i=0; $i < count($maderas_disponibles); $i++){

                $maderas_disponibles[$i]['porcentaje_uso'] = (int)($maderas_disponibles[$i]['cm3_total']/$maderas_disponibles[$i]['cm3']*100);
                $maderas_disponibles[$i]['margen_error'] = (100 - $maderas_disponibles[$i]['calificacion'])/2;
                switch ($maderas_disponibles[$i]['veces_largo']) {
                    case 1:
                        $maderas_disponibles[$i]['color'] = 'bg-primary';
                        break;
                    case 2:
                        $maderas_disponibles[$i]['color'] = 'bg-warning';
                        break;
                    case 3:
                        $maderas_disponibles[$i]['color'] = 'bg-secondary';
                        break;
                    default:
                        $maderas_disponibles[$i]['color'] = 'bg-danger ';
                        break;
                }

             }

            // ordenar por porcentaje de uso

            usort($maderas_disponibles, function($a, $b) {
                if ($a['porcentaje_uso'] == $b['porcentaje_uso']) return 0;
                return ($b['porcentaje_uso'] <=> $a['porcentaje_uso']) ;
            });
            return $maderas_disponibles;

         } else{
            return $mensaje = 'No se puede realizar el corte';
         }

    }
}
