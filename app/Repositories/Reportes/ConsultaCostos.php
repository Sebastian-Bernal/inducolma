<?php

namespace App\Repositories\Reportes;

use App\Models\Cliente;
use App\Models\CostosOperacion;
use App\Models\EstadoMaquina;
use App\Models\Pedido;
use Illuminate\Support\Facades\DB;

class ConsultaCostos {

    public function consultaDatos($request)
    {
        //return $request->all();
        $desde = $request->pedidoDesde;
        $hasta = $request->pedidoHasta;
        $tipoReporte = $request->tipoReportePedidos;

        switch ($tipoReporte) {
            case '1':
                $data = $this->costosFechaMaquina($request->maquina, $desde, $hasta);
                if (count($data) > 0) {
                    $encabezado = "COSTOS DE LA MAQUINA :  EN LAS FECHAS $desde - $hasta"; ;
                    $vista = 'modulos.reportes.costos.index-costos';
                    $vistaPdf = 'modulos.reportes.costos.pdf-costos';

                }else{
                    $encabezado = '';
                    $vista = '';
                    $vistaPdf = '';
                }
                break;
            case '2':
                /* $data = $this->pedidosPendientesCliente();
                if (count($data)> 0) {
                    $encabezado = "COSTOS DE LA MAQUINA :  EN LAS FECHAS $desde - $hasta ";
                    $vista = 'modulos.reportes.costos.index-costos';
                    $vistaPdf = 'modulos.reportes.costos.pdf-costos';
                }else{
                    $encabezado = 'algo';
                    $vista = '';
                    $vistaPdf = '';
                } */

                break;
            case '3':
                /* $data = $this->pedidosVencidosCliente();
                if (count($data)> 0) {
                    $encabezado = "COSTOS DE LA MAQUINA :  EN LAS FECHAS $desde - $hasta";
                    $vista = 'modulos.reportes.costos.index-costos';
                    $vistaPdf = 'modulos.reportes.costos.pdf-costos';
                }else{
                    $encabezado = 'algo';
                    $vista = '';
                    $vistaPdf = '';
                } */

                break;

            case '4':
                /*  $data = $this->pedidosTerminadosFecha($desde, $hasta);
                if (count($data)> 0) {
                    $encabezado = "COSTOS DE LA MAQUINA :  EN LAS FECHAS $desde - $hasta";
                    $vista = 'modulos.reportes.costos.index-costos';
                    $vistaPdf = 'modulos.reportes.costos.pdf-costos';
                }else{
                    $encabezado = 'algo';
                    $vista = '';
                    $vistaPdf = '';
                } */
                break;


        }
        return [$data, $encabezado, $vista, $vistaPdf];
    }

    /**
     * consulta los pedidos de un cliente en un rango de fechas
     * @param String $cliente
     * @param String $desde
     * @param String $hasta
     *
     *@return Array
     */

    public function costosFechaMaquina($maquina, $desde, $hasta)
    {
        $costos = DB::query("
                    select procesos.id, procesos.fecha_ejecucion, procesos.hora_inicio, procesos.hora_fin,
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

                    where procesos.maquina_id = $maquina and procesos.fecha_ejecucion between $desde and $hasta

                    GROUP by (procesos.id, procesos.fecha_ejecucion, procesos.hora_inicio, procesos.hora_fin,
                    estado_maquinas.created_at, costos_operacion.valor_dia, costos_operacion.costo_kwh,
                    procesos.salida, items.descripcion, users.name, cubicajes.entrada_madera_id, cubicajes.paqueta,
                    cubicajes.cm3,  entradas_madera_maderas.costo)

        ");

        $costo_segundos_maquina = $this->valorSegundosMaquina($maquina, $desde, $hasta);
        $valor_segundos = $costo_segundos_maquina['costo_segudo'];

        $datos = collect($costos);


        return $this->calcularAgregarCosto($datos, $valor_segundos);
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
                                ->whereBetween('created_at',[$desde, $hasta])
                                ->orderBy('id')
                                ->get();
        $apagada = EstadoMaquina::where('maquina_id', $maquina)
                                ->where('estado_id', 2)
                                ->whereBetween('created_at',[$desde, $hasta])
                                ->orderBy('id')
                                ->get();

        $costos = CostosOperacion::where('maquina_id', $maquina)->first();

        $segundos_totales = 0 ;

        foreach ($encendida as $key => $enc) {
            if (isset($apagada[$key])) {
                $segundos_totales += (strtotime($enc->created_at) - strtotime($apagada[$key]->created_at));
            }

        }

        $costo_segudo = ($costos->valor_dia/$segundos_totales) + ($costos->costo_kwh/3600);

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
        # code...
    }


    /*
        para poder agrupar la consulta es necesario crear una collection, $collect = collect(datos);
        luego se agrupa de acuerdo a la mquina $group = $collect->groupBy('maquina_id', preserveKeys: true)
        luego puede recorrerse con foreach y agregarle el dato
        foreach($group as $maquina){ foreach ($maquina as $m){ $m->nuevo_dato = 1;} }

    */
}
