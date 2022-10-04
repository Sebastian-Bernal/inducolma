<?php

namespace App\Repositories;

use App\Models\Cubicaje;
use App\Models\DisenoItem;
use App\Models\Pedido;
use App\Models\OrdenProduccion;
use App\Models\Transformacion;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\returnSelf;

class MaderasOptimas
{

    public function Optimas($request)
    {

        $pedido = $this->datosPedido($request);

        $item_diseno = $this->datosItemDiseno($pedido, $request);

        $sobrantes = $this->Sobrantes($item_diseno);
        $maderas = $this->Maderas($item_diseno);
        $producir = $this->producir($request, $item_diseno, $pedido);

        if ($maderas->count() == 0 && $sobrantes->count() == 0) {
            return ['status' => 'No hay maderas disponibles.'];
        } else {

            return [
                'maderas_usar' => $this->corteInicial($maderas, $item_diseno, 0, 0),
                'sobrantes_usar' => $this->sobrantesUsar($sobrantes, $item_diseno),
                'item' => $item_diseno,
                'producir' => $producir,
            ];
        }
    }
    /**
     * funcion datosPedido(), retorna la consulta de los datos del pedido
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     */

    public function datosPedido($request)
    {
        return Pedido::select('cantidad', 'id', 'diseno_producto_final_id')->find((int)$request->id_pedido);
    }
    /**
     * funcion datosItemDiseno(), retorna la consulta de los datos del item del diseno
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function datosItemDiseno($pedido, $request)
    {
        return DisenoItem::join('items', 'items.id', '=', 'diseno_items.item_id')
            ->where('diseno_producto_final_id', (int)$pedido->diseno_producto_final_id)
            ->where('item_id', (int)$request->id_item)
            ->first(['cantidad', 'descripcion', 'existencias', 'largo', 'ancho', 'alto', 'item_id', 'madera_id']);
    }

    // Funcion SObrantes(), retorna una coleccion de items que se pueden producir
    // con los sobrantes que cumplen con largo = $item_diseno->largo, ancho > (($item_diseno->ancho + 0.5) + 0.5), alto > (($item_diseno->alto + 0.5) +0.5)
    // y madera = $item_diseno->madera_id
    // que no esten en uso.

    public function Sobrantes($item_diseno)
    {
        $sobrantes = Transformacion::where('trnasformacion_final', 'SOBRANTE')
            ->where('largo', (int)$item_diseno->largo)
            ->where('ancho', '>', (int)($item_diseno->ancho + 0.5) + 0.5)
            ->where('alto', '>', (int)($item_diseno->alto + 0.5) + 0.5)
            ->where('madera_id', (int)$item_diseno->madera_id)
            ->get();
        return $sobrantes;
    }

    /**
     * Funcion Maderas(), retorna una coleccion de maderas de la tabla cubicajes que cumplen con las siguientes condiciones:
     * largo = $item_diseno->largo, ancho > (($item_diseno->ancho + 0.5) + 0.5), alto > (($item_diseno->alto + 0.5) +0.5)
     * y madera = $item_diseno->madera_id
     * calificacion eprovado  = true hacer join
     */

    public function Maderas($item_diseno)
    {
        $maderas = Cubicaje::join('entradas_madera_maderas', 'entradas_madera_maderas.entrada_madera_id', '=', 'cubicajes.entrada_madera_id')
            ->join('maderas', 'maderas.id', '=', 'entradas_madera_maderas.madera_id')
            ->join('calificacion_maderas', 'calificacion_maderas.entrada_madera_id', '=', 'entradas_madera_maderas.entrada_madera_id')
            ->where('largo', '>=', $item_diseno->largo)
            //->where('ancho','>',$item_diseno->ancho +  0.5)
            //->where('alto','>',$item_diseno->alto + 0.5)
            ->where('maderas.tipo_madera_id', $item_diseno->madera_id)
            ->where('calificacion_maderas.aprobado', '=', 'true')
            ->where('estado', 'DISPONIBLE')
            ->whereColumn('cubicajes.paqueta', 'calificacion_maderas.paqueta')
            ->orderBy('cubicajes.entrada_madera_id', 'asc')
            ->orderBy('cubicajes.paqueta', 'asc')
            ->orderBy('cubicajes.bloque', 'asc')
            ->get([
                'cubicajes.id',
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
            $sobrante->cantidad_items = (int)(($sobrante->ancho / ($item_diseno->ancho + 0.5) + $sobrante->alto / ($item_diseno->alto + 0.5))); //* $sobrante->cantidad
            $sobrante->porcentaje_uso = (int)(($sobrante->cantidad_items * ($item_diseno->alto + 0.5) * ($item_diseno->ancho + 0.5) * $item_diseno->largo) / ($sobrante->alto * $sobrante->largo * $sobrante->ancho) * 100);
            $sobrante->desperdicio = (int)(100 - $sobrante->porcentaje_uso);
            $sobrantes_usar[] = $sobrante;
        }
        return $sobrantes_usar;
    }

    /**
     * Funcion corte1(), agrega a cada elemto de la coleccion $sobrantes
     * cantidad_items, porcentaje_uso, desperdicio
     */

    public function corteInicial($maderas, $item_diseno, $accion, $orden_id)
    {


        $corteInicial = [];
        /*
        *  Agrupa la coleccion por entrada_madera_id y paqueta
            $corteInicial = Collection::make($maderas)->groupBy(['entrada_madera_id','paqueta']);
            return $corteInicial;
        */

        foreach ($maderas as $madera) {

            $madera->transformacion = $item_diseno->descripcion;
            $madera->cantidad_largo = (int)($madera->largo / $item_diseno->largo);

            if ($madera->cantidad_largo <= 0) {
                $restante_largo = $madera->largo;
            } else {
                $restante_largo = (int)($madera->largo - ($item_diseno->largo * $madera->cantidad_largo));
            }

            if ($restante_largo > 15) {
                $desperdicio_largo = 0;
                $sobrante_largo = $restante_largo;
            } else if ($restante_largo > 0 && $restante_largo < 15) {
                $sobrante_largo = 0;
                $desperdicio_largo = $restante_largo;
            } else {
                $sobrante_largo = 0;
                $desperdicio_largo = 0;
            }

            if ($sobrante_largo > 0) {

                $madera->sobrante_largo = $sobrante_largo;
                $madera->desperdicio_largo = 0;
            } else if ($desperdicio_largo > 0) {
                $madera->desperdicio_largo = $desperdicio_largo;
                $madera->sobrante_largo = 0;
            } else {
                $madera->sobrante_largo = 0;
                $madera->desperdicio_largo = 0;
            }

            $corteInicial[] = $madera;
        }

        $seleccion = [];
        if ($accion == 2) {
            foreach ($corteInicial as $guardar) {
                $seleccion =  $this->guardarTransformacion(
                    $guardar->ancho,
                    $guardar->largo,
                    $guardar->alto,
                    $item_diseno->descripcion,
                    $guardar->id,
                    Auth::user()->id,
                    $item_diseno->madera_id,
                    $guardar->sobrante_largo,
                    $guardar->desperdicio_largo,
                    $guardar->cantidad_largo,
                    'INICIAL',
                    $orden_id,
                );
            }
        }


        return $this->corteIntermedio($corteInicial, $item_diseno, $accion, $seleccion, $orden_id);
    }

    public function corteIntermedio($corteInicial, $item_diseno, $accion, $seleccion = [], $orden_id)
    {


        foreach ($corteInicial as $corte) {

            $nuevo_corte = (object)[];
            if ($corte->cantidad_largo != 0) {
                $corte->cantidad_ancho = ((int)($corte->ancho / (($item_diseno->ancho + 0.5)))) * $corte->cantidad_largo;

                $restante_ancho = $corte->ancho - (($item_diseno->ancho + 0.5) * ($corte->cantidad_ancho / $corte->cantidad_largo));

                // CUBICAJE ID
                if ($restante_ancho >= 15 && $restante_ancho <= 16) {
                    $corte->sobrante_ancho = $restante_ancho;
                } else {
                    if ($restante_ancho > 0.7 && $corte->alto > ($item_diseno->ancho + 0.5)) {
                        $corte->cm3 = $corte->cm3 - $item_diseno->largo * $corte->alto * $restante_ancho;
                        $nuevo_corte->tipo = 'sobrante corte';
                        $nuevo_corte->id = $corte->id;
                        $nuevo_corte->bloque = $corte->bloque;
                        $nuevo_corte->paqueta = $corte->paqueta;
                        $nuevo_corte->entrada_madera_id = $corte->entrada_madera_id;
                        $nuevo_corte->largo = (float)$item_diseno->largo;
                        $nuevo_corte->ancho = (float)($item_diseno->ancho + 0.5);
                        $nuevo_corte->alto = $restante_ancho;
                        $nuevo_corte->cm3 = $item_diseno->largo * $corte->alto * $restante_ancho;
                        $nuevo_corte->sobrante_largo = 0;
                        $nuevo_corte->cantidad_largo = 0;
                        $nuevo_corte->cantidad_ancho = ((int)($corte->alto / ($item_diseno->ancho + 0.5))) * $corte->cantidad_largo;
                        $nuevo_corte->total = $corte->total;

                        $restante_ancho_sobrante = $corte->alto - ((($item_diseno->ancho + 0.5) * $nuevo_corte->cantidad_ancho) / $corte->cantidad_largo);
                        if ($restante_ancho_sobrante >= 10) {
                            $nuevo_corte->desperdicio_ancho = 0;
                            $nuevo_corte->sobrante_ancho = $restante_ancho_sobrante;
                        } else {
                            $nuevo_corte->sobrante_ancho = 0;
                            $nuevo_corte->desperdicio_ancho = $restante_ancho_sobrante;
                        }
                        $corteInicial[] = $nuevo_corte;
                        $corte->sobrante_ancho = 0;
                        $corte->desperdicio_ancho = 0;
                    } else {
                        if ($restante_ancho > 0.7) {
                            $corte->sobrante_ancho = $restante_ancho;
                            $corte->desperdicio_ancho = 0;
                        } else if ($restante_ancho > 0 && $restante_ancho < 0.7) {
                            $corte->desperdicio_ancho = $restante_ancho;
                            $corte->sobrante_ancho = 0;
                        } else {
                            $corte->sobrante_ancho = 0;
                            $corte->desperdicio_ancho = 0;
                        }
                    }
                }
            }else{
                $corte->cantidad_ancho = 0;
            }

        }
        // return $corteInicial;

        // si accion == 2, guarda la tradnsformacion

        if ($accion == 2) {

            foreach ($corteInicial as $guardar) {
                $seleccion += $this->guardarTransformacion(
                    $item_diseno->ancho,
                    $item_diseno->largo,
                    $guardar->alto,
                    $item_diseno->descripcion,
                    $guardar->id,
                    Auth::user()->id,
                    $item_diseno->madera_id,
                    $guardar->sobrante_ancho,
                    $guardar->desperdicio_ancho,
                    $guardar->cantidad_ancho,
                    'INTERMEDIO',
                    $orden_id,
                );
            }
        }

        return $this->corteFinal($corteInicial, $item_diseno, $accion, $seleccion, $orden_id);
    }

    public function corteFinal($corteInicial, $item_diseno, $accion, $seleccion = [], $orden_id)
    {
        //return $corteInicial;
        // suma todos los cantidad_ancho de los elementos de la coleccion $corteInicial
        $cantidad_ancho = 0;
        foreach ($corteInicial as $corte) {

            $cantidad_ancho += $corte->cantidad_ancho;
        }

        if ($cantidad_ancho > 0) {
            foreach ($corteInicial as $corte) {
                if ($corte->cantidad_ancho != 0) {
                    $corte->cantidad_items = ((int)($corte->alto / ($item_diseno->alto + 0.5))) * $corte->cantidad_ancho;
                    if ($corte->cantidad_ancho > 0) {
                        $restante_item = $corte->alto - ((($item_diseno->alto + 0.5) * $corte->cantidad_items) / $corte->cantidad_ancho);
                    } else {
                        $restante_item = 0;
                    }

                    if ($restante_item > 0.7) {
                        $corte->sobrante_item = $restante_item;
                        $corte->desperdicio_item = 0;
                    } else if ($restante_item > 0 && $restante_item < 0.7) {
                        $corte->sobrante_item = 0;
                        $corte->desperdicio_item = $restante_item;
                    } else {
                        $corte->sobrante_item = 0;
                        $corte->desperdicio_item = 0;
                    }
                } else {
                    $corte->sobrante_item = 0;
                    $corte->desperdicio_item = 0;
                }

            }


            /* agrupa la coleccion por entrada_madera_id  y paqueta luego suma las cantidad_items
                    $corteInicial = Collection::make($corteInicial)->groupBy(['entrada_madera_id','paqueta']);
                    return $corteInicial;
                    foreach ($corteInicial as $key => $corte) {
                        foreach($corte as $j => $dato)
                        $corte[$j] = $dato->sum('cantidad_items');
                    }
                    return $corteInicial;
                */
            $corteInicial = array_values(Arr::sort($corteInicial, function ($value) {
                return $value->entrada_madera_id;
            }));

            //return $corteInicial;
            $maderas_disponibles = [];

            $resultado = $this->agrupar($corteInicial, $item_diseno);
            //return $resultado;

            $maderas_disponibles = $resultado;

            if ($accion == 1) {
                return $corteInicial;
            }

            if ($accion == 2) {
                foreach ($corteInicial as $guardar) {
                    $seleccion += $this->guardarTransformacion(

                        $item_diseno->ancho,
                        $item_diseno->largo,
                        $item_diseno->alto,
                        $item_diseno->descripcion,
                        $guardar->id,
                        Auth::user()->id,
                        $item_diseno->madera_id,
                        $guardar->sobrante_item,
                        $guardar->desperdicio_item,
                        $guardar->cantidad_items,
                        'FINAL',
                        $orden_id,
                    );
                }
                return $seleccion;
            }
            for ($i = 0; $i < count($maderas_disponibles); $i++) {

                $maderas_disponibles[$i]['porcentaje_uso'] = (int)($maderas_disponibles[$i]['cm3_total'] / $maderas_disponibles[$i]['cm3'] * 100);
                $maderas_disponibles[$i]['margen_error'] = (100 - $maderas_disponibles[$i]['calificacion']) / 2;
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

            usort($maderas_disponibles, function ($a, $b) {
                if ($a['porcentaje_uso'] == $b['porcentaje_uso']) return 0;
                return ($b['porcentaje_uso'] <=> $a['porcentaje_uso']);
            });
            return $maderas_disponibles;
        } else {
            return $mensaje = 'No se puede realizar el corte';
        }
    }
    /**
     * Crea el array de maderas_usar en el indice indicado cuando el id_entrada y paqueta es igual
     * @param  [array] $corteInicial[$i] [los elementos del corte en la posicion actual]
     * @param  [int] $indice [Indice actual del array en construccion]
     *
     */

    public function agrupar($corteInicial, $item_diseno)
    {

        $result = array();

        foreach ($corteInicial as $t) {

            $repeatir = false;

            for ($i = 0; $i < count($result); $i++) {
                if (
                    $result[$i]['entrada_madera_id'] == $t->entrada_madera_id
                    && $result[$i]['paqueta'] == $t->paqueta
                    //&& $result[$i]['bloque']==$t->bloque
                ) {
                    if ($t->sobrante_largo > 0) {
                        $cm3_sobrante_largo = $t->sobrante_largo * $t->ancho * $t->alto;
                    } else {
                        $cm3_sobrante_largo = 0;
                    }
                    if ($t->sobrante_ancho > 0) {

                        $cm3_sobrante_ancho = $t->sobrante_ancho * $item_diseno->largo * $t->alto ;//* $t->cantidad_largo;
                    } else {
                        $cm3_sobrante_ancho = 0;
                    }

                    if ($t->sobrante_item > 0) {
                        $cm3_sobrante_item = $t->sobrante_item * $item_diseno->ancho * $item_diseno->largo ;//* $t->cantidad_ancho;
                    } else {
                        $cm3_sobrante_item = 0;
                    }

                    $cm3_items = ($item_diseno->alto) * ($item_diseno->ancho) * $item_diseno->largo * $t->cantidad_items;
                    //$cm3_total += $cm3_items + $cm3_sobrante_largo + $cm3_sobrante_ancho + $cm3_sobrante_item;

                    $result[$i]['cantidad_items'] += $t->cantidad_items;
                    $result[$i]['cm3'] += $t->cm3;
                    $result[$i]['cm3_total'] += $cm3_items ;
                    $result[$i]['cm3_sobrantes'] += $cm3_sobrante_largo + $cm3_sobrante_ancho + $cm3_sobrante_item;

                    $repeatir = true;

                    break;
                }
            }

            if ($repeatir == false) {
                $result[] = array(
                    'entrada_madera_id' => $t->entrada_madera_id,
                    'paqueta' => $t->paqueta,
                    'cantidad_items' => $t->cantidad_items,
                    'calificacion' => (int)$t->total,
                    'cm3' => $t->cm3,
                    'cm3_total' => 0,
                    'cm3_sobrantes' => 0,
                    'veces_largo' => round($t->largo / $item_diseno->largo, 1),

                );
                $cm3_total = 0;
            }
        }
        return $result;
    }


    /**
     * funcion producir(), retorna la cantidad del total a producir del item
     */
    public function producir($request, $item_diseno, $pedido)
    {
        $existencias_produccion = OrdenProduccion::join('items', 'items.id', '=', 'ordenes_produccion.item_id')
            ->where('pedido_id', (int)$request->id_pedido)
            ->where('item_id', (int)$request->id_item)
            ->get(['cantidad', 'item_id']);

        if ($existencias_produccion->isEmpty()) {
            return $item_diseno->cantidad * $pedido->cantidad;
        } else {
            return $item_diseno->cantidad * $pedido->cantidad - $existencias_produccion->sum('cantidad');
        }
    }

    /**
     * funcion cubicaje(), retorna array deivido en 2 partes, con la informacion del bloque
     * y la cantidad_items que se puede cortar.
     * @param  [type] $request [description]
     * @return [type]   json       [description]
     *
     */

    public function cubicaje($request, $accion)
    {
        $pedido = $this->datosPedido($request);
        $item_diseno = $this->datosItemDiseno($pedido, $request);
        $maderas = $this->datosCubicaje($request, $item_diseno);
        $accionVer = $accion;
        $cubicajes = $this->corteInicial($maderas, $item_diseno, $accionVer, 0);
        $datos = Collection::make($cubicajes)->groupBy('bloque');
        //return $datos;
        $bloques = [];
        foreach ($datos as $k => $dato) {
            $repetir = false;
            foreach ($dato as $d) {
                if ($repetir == false) {
                    $bloques[$k]['bloque'] = $d->bloque;
                    $bloques[$k]['cantidad_items'] = $d->cantidad_items;
                    $repetir = true;
                } else {
                    $bloques[$k]['cantidad_items'] += $d->cantidad_items;
                }
            }
        }
        //return $bloques;
        $count = round(count($bloques) / 2, 0, PHP_ROUND_HALF_UP);
        $bloques = array_chunk($bloques, $count);
        return response()->json(['cubicajes' => $bloques]);
    }
    /**
     * funcion datosCubicaje(), retorna una connsulta del modelo Cubicaje
     * @param  [type] $pedido  [description]
     * @param  [type] $request [description]
     * @return [type]  Collection  [description]
     *
     */
    public function datosCubicaje($request, $item_diseno)
    {
        $maderas = Cubicaje::join('entradas_madera_maderas', 'entradas_madera_maderas.entrada_madera_id', '=', 'cubicajes.entrada_madera_id')
            ->join('maderas', 'maderas.id', '=', 'entradas_madera_maderas.madera_id')
            ->join('calificacion_maderas', 'calificacion_maderas.entrada_madera_id', '=', 'entradas_madera_maderas.entrada_madera_id')
            ->where('cubicajes.paqueta', (int)$request->paqueta)
            ->where('cubicajes.entrada_madera_id', (int)$request->entrada_madera_id)
            ->where('largo', '>=', $item_diseno->largo)
            ->where('cubicajes.estado', 'DISPONIBLE')
            ->where('calificacion_maderas.aprobado', '=', 'true')
            ->whereColumn('cubicajes.paqueta', 'calificacion_maderas.paqueta')
            ->orderBy('cubicajes.entrada_madera_id', 'asc')
            ->orderBy('cubicajes.paqueta', 'asc')
            ->orderBy('cubicajes.bloque', 'asc')
            ->get([
                'cubicajes.id',
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
     * funcion seleccionaPaqueta(), hace la seleccion de la paqueta
     * @param  [type] $request [description]
     * @return [type]          [description]
     *
     */

    public function seleccionaPaqueta($request, $guardar, $orden)
    {
        //return $orden;
        $pedido = $this->datosPedido($request);
        $item_diseno = $this->datosItemDiseno($pedido, $request);
        $maderas = $this->datosSeleccion($request, $item_diseno);
        $seleccion = $this->corteInicial($maderas, $item_diseno, $guardar, $orden);
        return $seleccion;
    }

    /**
     * funcion datosSeleccion(), retorna una connsulta del modelo Cubicaje, filtrada por un rango de bloques
     */
    public function datosSeleccion($request, $item_diseno)
    {
        $maderas = Cubicaje::join('entradas_madera_maderas', 'entradas_madera_maderas.entrada_madera_id', '=', 'cubicajes.entrada_madera_id')
            ->join('maderas', 'maderas.id', '=', 'entradas_madera_maderas.madera_id')
            ->join('calificacion_maderas', 'calificacion_maderas.entrada_madera_id', '=', 'entradas_madera_maderas.entrada_madera_id')
            ->where('cubicajes.paqueta', (int)$request->paqueta)
            ->where('cubicajes.entrada_madera_id', (int)$request->entrada_madera_id)
            ->where('largo', '>=', $item_diseno->largo)
            ->whereBetween('cubicajes.bloque', [$request->bloque_inicial, $request->bloque_final])
            ->where('estado', 'DISPONIBLE')
            ->where('calificacion_maderas.aprobado', '=', 'true')
            ->whereColumn('cubicajes.paqueta', 'calificacion_maderas.paqueta')
            ->orderBy('cubicajes.entrada_madera_id', 'asc')
            ->orderBy('cubicajes.paqueta', 'asc')
            ->get([
                'cubicajes.id',
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
     * funcion guardarTransformacion(), guarda las transformaciones en la base de datos
     * @param  [array] $corte [corte de cada proceso]
     * @return [type]        [description]
     *
     */
    public function guardarTransformacion($ancho, $largo, $alto, $transformacion, $cubicaje_id, $user_id, $madera_id, $sobrante, $desperdicio, $cantidad, $tipo_corte, $orden_id)
    {

        $cubicajes = [];
        $tansformacion = new Transformacion();
        $tansformacion->ancho = $ancho;
        $tansformacion->largo = $largo;
        $tansformacion->alto = $alto;
        $tansformacion->estado = 'DISPONIBLE';
        $tansformacion->trnasformacion_final = $transformacion;
        $tansformacion->cubicaje_id = $cubicaje_id;
        $tansformacion->user_id = $user_id;
        $tansformacion->madera_id = $madera_id;
        $tansformacion->sobrante = $sobrante;
        $tansformacion->desperdicio = $desperdicio;
        $tansformacion->cantidad = $cantidad;
        $tansformacion->tipo_corte = $tipo_corte;
        $tansformacion->orden_produccion_id = $orden_id;
        if ($tansformacion->save()) {
            $errorGuardar[] = array('error' => false);
            Cubicaje::where('id', $tansformacion->cubicaje_id)->update(['estado' => 'NO DISPONIBLE']);
        } else {

            $cubicajes[] += array('id' => $cubicaje_id);
            $errorGuardar[] = array('error' => true, 'cubicajes' => $cubicajes);
        }
        return $errorGuardar;
    }
}
