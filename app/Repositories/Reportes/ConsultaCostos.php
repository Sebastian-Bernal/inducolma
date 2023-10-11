<?php

namespace App\Repositories\Reportes;

use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\Maquina;
use App\Models\EstadoMaquina;
use App\Models\CostosOperacion;
use App\Models\OrdenProduccion;
use App\Models\Proceso;
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
                $data = $this->costosFechaMaquina($request->maquina, $desde, $hasta);

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
            case '2':
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
                break;


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

        $costo_segundos_maquina = $this->valorSegundosMaquina($maquina, $desde, $hasta);
        $valor_segundos = $costo_segundos_maquina['costo_segudo'];

        $datos = collect($costos);

        $costos_maquina = $this->calcularAgregarCosto($datos, $valor_segundos);
        return json_decode(json_encode($costos_maquina));
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

    public function costoFiltroEspecifico($filtro, $desde, $hasta, $where)
    {
        $costos = DB::select("
                    select distinct procesos.id, procesos.fecha_ejecucion, procesos.hora_inicio, procesos.hora_fin, procesos.sub_paqueta,
                    procesos.maquina_id,
                    estado_maquinas.created_at, costos_operacion.valor_dia, costos_operacion.costo_kwh,
                    procesos.salida, items.descripcion, users.name, cubicajes.entrada_madera_id, cubicajes.paqueta,
                    (procesos.cm3_salida + sum(subprocesos.sobrante)) as cm3_salida,
                    ((cubicajes.cm3 * entradas_madera_maderas.costo) / (procesos.cm3_salida + sum(subprocesos.sobrante))) as costo_cm3

                    from
                    ordenes_produccion join procesos on ordenes_produccion.id  = procesos.orden_produccion_id
                    JOIN subprocesos ON subprocesos.proceso_id = procesos.id
                    join estado_maquinas on estado_maquinas.maquina_id = subprocesos.maquina_id
                    join costos_operacion on costos_operacion.maquina_id = procesos.maquina_id
                    join cubicajes on cubicajes.id = procesos.cubicaje_id
                    join entradas_madera_maderas on entradas_madera_maderas.id = cubicajes.entrada_madera_id
                    join items on items.id = procesos.item_id
                    join turno_usuarios on turno_usuarios.maquina_id = procesos.maquina_id
                    join users on users.id = turno_usuarios.user_id

                    where $where = $filtro and procesos.fecha_ejecucion between '$desde' and '$hasta'

                    GROUP by (procesos.id, procesos.fecha_ejecucion, procesos.hora_inicio, procesos.hora_fin,
                    estado_maquinas.created_at, costos_operacion.valor_dia, costos_operacion.costo_kwh,
                    procesos.salida, items.descripcion, users.name, cubicajes.entrada_madera_id, cubicajes.paqueta,
                    cubicajes.cm3,  entradas_madera_maderas.costo)

        ");
/*
        $ordenesProduccion = OrdenProduccion::where($where, $filtro)->get()->pluck('id')->toArray();
        $procesos = Proceso::whereIn('orden_produccion_id', $ordenesProduccion)->get();
        $maquinas = Maquina::whereIn('id', $procesos->pluck('maquina_id')->toArray())->get();
        $costosOperacion = CostosOperacion::whereIn('maquina_id', $maquinas->pluck('id')->toArray())->get();
        $estados = EstadoMaquina::whereIn('maquina_id', $maquinas->pluck('id')->toArray())
                                ->whereDate('created_at','>=',$desde)
                                ->whereDate('created_at','<=',$hasta)
                                ->orderBy('maquina_id')
                                ->orderBy('estado_id')
                                ->distinct('maquina_id', 'estado_id')
                                ->get();
*/
        //print_r($ordenesProduccion);
        if (empty($costos)) {
            return array();
        }

        $costos_agrupados = collect($costos)->groupBy('maquina_id');
        $array_costos = array();

        foreach ($costos_agrupados as $costo) {
            $costo_segundos_maquina = $this->valorSegundosMaquina($costo->first()->maquina_id, $desde, $hasta);
            //print_r($hasta);
            $valor_segundos = $costo_segundos_maquina['costo_segudo'];
            //print_r($valor_segundos);
            $array_costos += [$this->calcularAgregarCosto($costo, $valor_segundos)];
        }
        //($array_costos[0]);
        return json_decode(json_encode($array_costos[0]));
    }


    /**
     * obtiene el valor del costo de segundos de la maquina
     *
     * @param Integer   $maquina
     * @param String    $desde
     * @param String    $hasta
     *
     *@return Array
     */

    public function valorSegundosMaquina($maquina, $desde, $hasta) : array
    {
        $encendida = EstadoMaquina::where('maquina_id', $maquina)
                                ->where('estado_id', 1)
                                ->whereDate('created_at','>=',$desde)
                                ->whereDate('created_at','<=',$hasta)
                                ->orderBy('id')
                                ->get();
        $apagada = EstadoMaquina::where('maquina_id', $maquina)
                                ->where('estado_id', 2)
                                ->whereDate('created_at','>=',$desde)
                                ->whereDate('created_at','<=',$hasta)
                                ->orderBy('id')
                                ->get();

        $costos = CostosOperacion::where('maquina_id', $maquina)->first();

        $segundos_totales = 0 ;


        foreach ($encendida as $key => $enc) {
            if (isset($apagada[$key])) {

                $segundos_totales += (strtotime($apagada[$key]->created_at) - strtotime($enc->created_at)  );

            }

        }

        if($segundos_totales == 0) {
            $costo_segudo = 0   ;
        } else {
            // valor_dias = sum(valor_dia)
            //
            $costo_segudo = ($costos->valor_dia/$segundos_totales) + ($costos->costo_kwh/3600);
        }

        return array('costo_segudo' => $costo_segudo, 'segundos_totales' => $segundos_totales);
    }

    /**
     * Calcula el valor del costo y lo agrega a cada uno de los datos generados en la consulta,
     *
     * @param Collection $datos
     * @param float $costo
     *
     * @return Collection
     */

    public function calcularAgregarCosto($datos, $costo)
    {
        foreach($datos as $dato){
            //print_r( $costo);
            if (is_null($dato->hora_fin) || is_null($dato->hora_inicio)) {
                $dato->costo = 0;
            } else {
                $dato->costo = $costo * (strtotime($dato->hora_fin) - strtotime($dato->hora_inicio));
            }
        }
        return $datos;
    }


    /*
        para poder agrupar la consulta es necesario crear una collection, $collect = collect(datos);
        luego se agrupa de acuerdo a la mquina $group = $collect->groupBy('maquina_id', preserveKeys: true)
        luego puede recorrerse con foreach y agregarle el dato
        foreach($group as $maquina){ foreach ($maquina as $m){ $m->nuevo_dato = 1;} }

    */
}
