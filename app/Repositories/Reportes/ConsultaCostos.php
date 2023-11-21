<?php

namespace App\Repositories\Reportes;

use DateTime;
use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\Maquina;
use App\Models\Proceso;
use App\Models\EstadoMaquina;
use App\Models\CostosOperacion;
use App\Models\OrdenProduccion;
use App\Models\Subproceso;
use Illuminate\Support\Facades\DB;

class ConsultaCostos {

    public function consultaDatos($request)
    {
        //return $request->all();
        $desde = $request->costoDesde;
        $hasta = $request->costoHasta;
        $tipoReporte = $request->tipoReporteCosotos;

        switch ($tipoReporte) {
            case '1':
                $data = $this->costosProcesosFechasMaquina($request->maquina, $desde, $hasta);

                if (count($data) > 0) {
                    $maquina = Maquina::find($request->maquina);
                    $encabezado = "COSTOS DE LA MAQUINA : $maquina->maquina EN LAS FECHAS $desde - $hasta"; ;
                    $vista = 'modulos.reportes.costos.index-costos';
                    $vistaPdf = 'modulos.reportes.costos.pdf-costos';

                }else{
                    $encabezado = '';
                    $vista = '';
                    $vistaPdf = '';
                }
                break;
          /*  case '2':
                $data = $this->costoFiltroEspecifico($request->usuario, $desde, $hasta, 'users.id');
                if (count($data)> 0) {
                    $encabezado = "COSTOS EN LAS FECHAS $desde - $hasta ";
                    $vista = 'modulos.reportes.costos.index-costos';
                    $vistaPdf = 'modulos.reportes.costos.pdf-costos';
                }else{
                    $encabezado = 'algo';
                    $vista = '';
                    $vistaPdf = '';
                }

                break;
            case '3':
                $data = $this->costoFiltroEspecifico($request->pedido, $desde, $hasta, 'pedido_id');
                if (count($data)> 0) {
                    $encabezado = "COSTOS EN LAS FECHAS $desde - $hasta";
                    $vista = 'modulos.reportes.costos.index-costos';
                    $vistaPdf = 'modulos.reportes.costos.pdf-costos';
                }else{
                    $encabezado = 'algo';
                    $vista = '';
                    $vistaPdf = '';
                }

                break;

            case '4':
                $data = $this->costoFiltroEspecifico($request->item, $desde, $hasta, 'items.id');
                if (count($data)> 0) {
                    $encabezado = "COSTOS EN LAS FECHAS $desde - $hasta";
                    $vista = 'modulos.reportes.costos.index-costos';
                    $vistaPdf = 'modulos.reportes.costos.pdf-costos';
                }else{
                    $encabezado = 'algo';
                    $vista = '';
                    $vistaPdf = '';
                }
                break;*/


        }
        return [$data, $encabezado, $vista, $vistaPdf];
    }

    /**
     * consulta los costos de la maquina y fechas seleccionadas
     * @param String $cliente
     * @param String $desde
     * @param String $hasta
     *
     *@return Array
     */

    public function costosFechaMaquina($maquina, $desde, $hasta)
    {
        $costos = DB::select("
                    select procesos.id, procesos.fecha_ejecucion, procesos.hora_inicio, procesos.hora_fin, procesos.sub_paqueta,
                    estado_maquinas.created_at, costos_operacion.valor_dia, costos_operacion.costo_kwh,
                    procesos.salida, items.descripcion, users.name, cubicajes.entrada_madera_id, cubicajes.paqueta,
                    (procesos.cm3_salida + sum(subprocesos.sobrante)) as cm3_salida,
                    ((cubicajes.cm3 * entradas_madera_maderas.costo) / (procesos.cm3_salida + sum(subprocesos.sobrante))) as costo_cm3

                    from
                    procesos JOIN subprocesos ON subprocesos.proceso_id = procesos.id
                    join estado_maquinas on estado_maquinas.maquina_id = subprocesos.maquina_id
                    join costos_operacion on costos_operacion.maquina_id = procesos.maquina_id
                    join cubicajes on cubicajes.id = procesos.cubicaje_id
                    join entradas_madera_maderas on entradas_madera_maderas.id = cubicajes.entrada_madera_id
                    join items on items.id = procesos.item_id
                    join turno_usuarios on turno_usuarios.maquina_id = procesos.maquina_id
                    join users on users.id = turno_usuarios.user_id

                    where procesos.maquina_id = $maquina and procesos.fecha_ejecucion between '$desde' and '$hasta'

                    GROUP by (procesos.id, procesos.fecha_ejecucion, procesos.hora_inicio, procesos.hora_fin,
                    estado_maquinas.created_at, costos_operacion.valor_dia, costos_operacion.costo_kwh,
                    procesos.salida, items.descripcion, users.name, cubicajes.entrada_madera_id, cubicajes.paqueta,
                    cubicajes.cm3,  entradas_madera_maderas.costo)

        ");

        //$costo_segundos_maquina = $this->valorSegundosMaquina($maquina, $desde, $hasta);
        //$valor_segundos = $costo_segundos_maquina['costo_segudo'];

        $datos = collect($costos);

        //$costos_maquina = $this->calcularAgregarCosto($datos, $valor_segundos);
        //return json_decode(json_encode($costos_maquina));
    }


    /**
     * Consulta los costos en las fechas y usuario seleccionado
     *
     * @param String $usuario
     * @param String $desde
     * @param String $hasta
     *
     * @return array
     */

    public function costosProcesosFechasMaquina($maquina = null, $desde, $hasta)
    {
        $procesos = Proceso::with('orden_produccion')
                    ->whereDate('fecha_ejecucion', '>=', $desde)
                    ->whereDate('fecha_ejecucion', '<=', $hasta)
                    ->when($maquina !== null, function($query) use ($maquina) {
                        return $query->where('maquina_id', $maquina);
                    })
                    ->get();

        $maquinasIds = Maquina::whereIn('id', $procesos->pluck('maquina_id')->toArray())->get()->pluck('id')->toArray();

        $valorMinutoEnergia = CostosOperacion::whereIn('maquina_id', $maquinasIds )
                                            ->where('costo_kwh', '>', 0)
                                            ->get()
                                            ->map(function ($costo) {
                                                return [
                                                    'maquina_id' => $costo->maquina_id,
                                                    'valor_minuto_energia' => $costo->costo_kwh/60
                                                ];

                                            });
        $minutosTotalesMaquina = $this->minutosTotalesEnergia($maquinasIds, $desde, $hasta);

        $valorTotalDiaMaquina = $this->valorTotalDiaMaquina($maquinasIds);

        $numDias = (new DateTime($hasta))->diff(new DateTime($desde))->days;

        $cmCubicosMaderaProcesados = $this->cmCubicosMaderaProcesados($procesos);

        $datosReporte = $this->mapData($minutosTotalesMaquina,
                                        $valorMinutoEnergia,
                                        $valorTotalDiaMaquina,
                                        $cmCubicosMaderaProcesados,
                                        $numDias,
                                        $procesos
                                        );

        return $datosReporte;
    }

    public function minutosTotalesEnergia($maquinas, $desde, $hasta)
    {
        $estadosMaquina = EstadoMaquina::whereIn('maquina_id', $maquinas)
                                ->whereIn('estado_id', [1,2])
                                ->whereDate('created_at','>=', $desde)
                                ->whereDate('created_at','<=',$hasta)
                                ->orderBy('maquina_id')
                                ->orderBy('estado_id')
                                ->get();

        if($estadosMaquina->isEmpty()) {
            return 0;
        }

        $estadosMaquinaGroup = $estadosMaquina->groupBy('maquina_id');

        $minutosTotalesMaquina = $estadosMaquinaGroup->map(function ($maquina) {
            $estadosGroup = collect($maquina)->sortBy('id')->groupBy('estado_id');
            $onStates = $estadosGroup->get(1, []);
            $offStates = $estadosGroup->get(2, []);

            $minutos = 0;

            foreach ($onStates as $on) {
                $matchingOffState = collect($offStates)->first(function ($off) use ($on) {
                    return $off->id > $on->id;
                });

                if ($matchingOffState) {
                    $minutos += strtotime($matchingOffState->created_at) - strtotime($on->created_at);
                }
            }

            return [
                'maquina_id' => $maquina->first()->maquina_id,
                'minutos' => $minutos / 60
            ];
        })->values();

        return $minutosTotalesMaquina;
    }

    public function valorTotalDiaMaquina($maquinas)
    {
        $valorTotalDiaMaquina = CostosOperacion::whereIn('maquina_id', $maquinas)
        ->where('costo_kwh', '<=', 0)
        ->get()
        ->groupBy('maquina_id')
        ->map(function ($costo) {
            return [
                'maquina_id' => $costo->first()->maquina_id,
                'sum_valor_dia' => $costo->sum('valor_dia')
            ];
        })
        ->values();


        return $valorTotalDiaMaquina;
    }

    public function cmCubicosMaderaProcesados($procesos)
    {
        $cmCubicosMaderaProcesados = Subproceso::whereIn('proceso_id', $procesos->pluck('id'))
                                                ->whereIn('maquina_id', $procesos->pluck('maquina_id'))
                                                ->get()
                                                ->groupBy('maquina_id')
                                                ->map(function ($subproceso) {
                                                    return [
                                                        'maquina_id' => $subproceso->first()->maquina_id,
                                                        'sum_cm3' => $subproceso->sum('cm3_salida') + $subproceso->sum('sobrante')
                                                    ];

                                                })->values();
        return $cmCubicosMaderaProcesados;

    }

    public function mapData(
        $minutosTotalesMaquina,
        $valorMinutoEnergia,
        $valorTotalDiaMaquina,
        $cmCubicosMaderaProcesados,
        $numDias,
        $procesos
    ) {

        $mapData = $valorMinutoEnergia->map(function ($item) use (
            $numDias,
            $minutosTotalesMaquina,
            $valorTotalDiaMaquina,
            $cmCubicosMaderaProcesados,
            $procesos
        ) {
            $minutosItem = $minutosTotalesMaquina->where('maquina_id', $item['maquina_id'])->first();
            $valorDiaItem = $valorTotalDiaMaquina->where('maquina_id', $item['maquina_id'])->first();
            $cm3Item = $cmCubicosMaderaProcesados->where('maquina_id', $item['maquina_id'])->first();

            $procesosGroup = $procesos->groupBy('orden_produccion.pedido_id');
            return [
                'maquina_id' => $item['maquina_id'],
                'valor_minuto_energia' => $item['valor_minuto_energia'],
                'minutos' => $minutosItem ? $minutosItem['minutos'] : 0,
                'sum_valor_dia' => $valorDiaItem ? $valorDiaItem['sum_valor_dia'] : 0,
                'sum_cm3' => $cm3Item ? $cm3Item['sum_cm3'] : 0,
                'numero_dias' => $numDias,
                'procesos_pedido' => $procesosGroup->map(function ($proceso) use ($item) {
                    return [
                        'pedido_id' =>  $proceso->first()->orden_produccion->pedido_id,
                        'cliente' => $proceso->first()->orden_produccion->pedido->cliente->razon_social,
                        'producto' => $proceso->first()->orden_produccion->pedido->diseno_producto_final->descripcion,
                        'cantidad' => $proceso->first()->orden_produccion->pedido->cantidad,
                        'procesos' => $proceso->map(function ($proceso) use ($item) {
                            return [
                                'pedido_id' =>  $proceso->orden_produccion->pedido_id,
                                'fecha_ejecucion' => $proceso->fecha_ejecucion,
                                'hora_inicio' => $proceso->hora_inicio,
                                'fecha_fin' => $proceso->fecha_finalizacion,
                                'hora_fin' => $proceso->hora_fin,
                                'subprocesos' => $proceso->subprocesos->map(function ($subproceso, $item) {
                                    return [
                                        'tarjeta_entrada' => $subproceso->tarjeta_entrada,
                                        'tarjeta_salida' => $subproceso->tarjeta_salida,
                                        'subpaqueta' => $subproceso->sub_paqueta,
                                        'alto' => $subproceso->alto,
                                        'ancho' => $subproceso->ancho,
                                        'largo' => $subproceso->largo,
                                        'sobrante' => $subproceso->sobrante,
                                        'lena' => $subproceso->lena,
                                        'cm3_procesados' => $subproceso->cm3_salida
                                    ];
                                }),
                                'total_cm3' => $proceso->subprocesos->sum('cm3_salida'),
                                'total_sobrante' => $proceso->subprocesos->sum('sobrante'),
                                'total_lena' => $proceso->subprocesos->sum('lena'),

                                'consumo_energia' => round($item['valor_minuto_energia'] * (strtotime($proceso->fecha_finalizacion . $proceso->hora_fin) - strtotime($proceso->fecha_ejecucion .$proceso->hora_inicio))/60, 2)
                            ];
                        }),
                        'total_cm3_pedido' => $proceso->sum(function ($proceso) {
                            return $proceso->subprocesos->sum('cm3_salida');
                        }),
                        'total_sobrante_pedido' => $proceso->sum(function ($proceso) {
                            return $proceso->subprocesos->sum('sobrante');
                        }),
                        'total_lena_pedido' => $proceso->sum(function ($proceso) {
                            return $proceso->subprocesos->sum('lena');
                        }),
                        'consumo_energia' => $proceso->sum(function ($proceso) use($item) {
                            return round($item['valor_minuto_energia'] * (strtotime($proceso->fecha_finalizacion . $proceso->hora_fin) - strtotime($proceso->fecha_ejecucion .$proceso->hora_inicio))/60, 2);
                        })

                    ];
                })


            ];
        });

        return $mapData;


    }

}
