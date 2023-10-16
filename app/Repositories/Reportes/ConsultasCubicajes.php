<?php

namespace App\Repositories\Reportes;

use App\Models\Cubicaje;
use Illuminate\Support\Facades\DB;

class ConsultasCubicajes {

    public function consultaDatos($request)
    {
        //return $request->all();
        $desde = $request->cubicajeDesde;
        $hasta = $request->cubicajeHasta;
        $tipoReporte = $request->tipoReporteCubicaje;

        switch ($tipoReporte) {
            case '1':
                $data = $this->consultaCubicajeViaje($request->filtroCubiaje1);
                if (count($data) > 0) {
                    $encabezado = "CUBICAJE DEL VIAJE No. {$data[0]->entrada_madera_id}" ;
                    $vista = 'modulos.reportes.administrativos.cubicajes.index-cubicajes';
                    $vistaPdf = 'modulos.reportes.administrativos.cubicajes.pdf-cubicajes-viaje';
                }else{
                    $encabezado = 'algo';
                    $vista = '';
                    $vistaPdf = '';
                }
                break;
            case '2':
                $data = $this->consultaTransfromacionesViaje($request->filtroCubiaje1, $request->generar);
                if (count($data)> 0) {
                    $encabezado = "TRANSFORMACION DE LA MADERA VIAJE No {$data[0]->entrada_madera_id}";
                    $vista = 'modulos.reportes.administrativos.cubicajes.index-transformaciones';
                    $vistaPdf = 'modulos.reportes.administrativos.cubicajes.pdf-transformaciones-viaje';
                }else{
                    $encabezado = 'algo';
                    $vista = '';
                    $vistaPdf = '';
                }

                break;
            case '3':
                $data = $this->consultaCalificacionesViaje($request->filtroCubiaje1);
                if (count($data)> 0) {
                    $encabezado = "CALIFICACION DE LA MADERA VIAJE No {$data[0]->id}";
                    $vista = 'modulos.reportes.administrativos.cubicajes.index-calificaciones-viaje';
                    $vistaPdf = 'modulos.reportes.administrativos.cubicajes.pdf-calificaciones-viaje';
                }else{
                    $encabezado = 'algo';
                    $vista = '';
                    $vistaPdf = '';
                }

                break;

            case '4':
                $data = $this->consultaCalificacionesProveedor($desde, $hasta, $request->filtroCubiaje2);
                if (count($data)> 0) {
                    $encabezado = "CALIFICACIONES DE LA MADERA POR PROVEEDOR {$data[0]->razon_social}";
                    $vista = 'modulos.reportes.administrativos.cubicajes.index-calificaciones-viaje';
                    $vistaPdf = 'modulos.reportes.administrativos.cubicajes.pdf-calificaciones-viaje';
                }else{
                    $encabezado = 'algo';
                    $vista = '';
                    $vistaPdf = '';
                }

        }
        return [$data, $encabezado, $vista, $vistaPdf];
    }


    public function consultaCubicajeViaje($viaje)
    {
        $cubicajes = Cubicaje::join('entradas_madera_maderas', 'cubicajes.entrada_madera_id', '=', 'entradas_madera_maderas.id')
                            ->join('entrada_maderas', 'entrada_maderas.id', '=','entradas_madera_maderas.entrada_madera_id')
                            ->join('proveedores', 'proveedores.id', '=','entrada_maderas.proveedor_id')
                            ->join('maderas', 'maderas.id', '=', 'entradas_madera_maderas.madera_id')
                            ->join('tipo_maderas', 'tipo_maderas.id' , '=', 'maderas.tipo_madera_id')
                            ->where('entrada_maderas.id', (integer)$viaje)
                            ->orderBy('paqueta', 'asc')
                            ->orderBy('bloque', 'asc')
                            ->get([
                                'proveedores.razon_social',
                                'cubicajes.paqueta',
                                'cubicajes.bloque',
                                'cubicajes.largo',
                                'cubicajes.alto',
                                'cubicajes.ancho',
                                'cubicajes.created_at',
                                'cubicajes.pulgadas_cuadradas',
                                'cubicajes.cm3',
                                'entradas_madera_maderas.entrada_madera_id',
                                'tipo_maderas.descripcion',

                            ]);



        return $cubicajes;

    }

    /**
     * realiza la consulta de los datos filtrando por el numero de viaje
     * si generar es diferente de 1 se retorna los datos de la consulta, sino se hace un agrupamiento de los datos
     *
     * @param Integer $viaje [numero de viaje seleccionado por el usuario]
     * @param String $generar [tipo de reporte seleccionado por el usuario]
     *
     * @return Array
     */
    public function consultaTransfromacionesViaje($viaje, $generar): array
    {
        $tranformaciones = DB::select("select  cubicajes.entrada_madera_id, cubicajes.id, paqueta, bloque, descripcion as tipo_madera, cubicajes.alto, cubicajes.largo, cubicajes.ancho, cubicajes.created_at,
                                        transformaciones.trnasformacion_final, sum(transformaciones.cantidad) as cantidad, orden_produccion_id,
                                        pedido_id, nombre,cubicajes.cm3, entrada_maderas.id as entrada
                                        from cubicajes
                                        join transformaciones on cubicajes.id = transformaciones.cubicaje_id
                                        join entradas_madera_maderas on entradas_madera_maderas.id = cubicajes.entrada_madera_id
                                        join entrada_maderas ON entrada_maderas.id = entradas_madera_maderas.entrada_madera_id
                                        join maderas on maderas.id = transformaciones.madera_id
                                        join tipo_maderas ON tipo_maderas.id = maderas.tipo_madera_id
                                        join ordenes_produccion on ordenes_produccion.id = transformaciones.orden_produccion_id
                                        join pedidos on pedidos.id = ordenes_produccion.pedido_id
                                        join clientes on clientes.id = pedidos.cliente_id
                                        where transformaciones.tipo_corte= 'FINAL'and entrada_maderas.id  = $viaje
                                        group by (cubicajes.entrada_madera_id,cubicajes.id, paqueta, bloque, tipo_madera, cubicajes.alto, cubicajes.largo, cubicajes.ancho, cubicajes.created_at,
                                        transformaciones.trnasformacion_final, orden_produccion_id, pedido_id, nombre,cubicajes.cm3, entrada_maderas.id)
                                        order by 3,4 asc");
        if ($generar != '1') {
            $data = json_decode(json_encode($tranformaciones));
            return $data;
        }

        $data = json_decode(json_encode($this->agrupar($tranformaciones)));
        return $data;
    }
    /**
     * realiza la consulta de las calificaciones filtrando por viaje
     *
     * @param Integer $viaje [numero de viaje seleccionado por el usuario]
     *
     * @return Array
     */

    public function consultaCalificacionesViaje($viaje)
    {
        $calificaciones = DB::select("select entrada_maderas.id, cubicajes.paqueta, total, longitud_madera, cantonera,
                        hongos, rajadura, bichos, organizacion,
                        areas_transversal_max_min, areas_no_conveniente, nombre, razon_social
                        from cubicajes join calificacion_maderas on calificacion_maderas.entrada_madera_id = cubicajes.entrada_madera_id
                        join entradas_madera_maderas on entradas_madera_maderas.id = cubicajes.entrada_madera_id
                        join entrada_maderas on entrada_maderas.id = entradas_madera_maderas.entrada_madera_id
                        join proveedores on proveedores.id = entrada_maderas.proveedor_id
                        where entrada_maderas.id= $viaje
                        group by (cubicajes.paqueta, total, longitud_madera, cantonera, hongos, rajadura, bichos, organizacion,
                        areas_transversal_max_min, areas_no_conveniente, nombre, entrada_maderas.id, razon_social)
                        order by paqueta asc");

        $data = json_decode(json_encode($calificaciones));
        return $data;
    }


    public function consultaCalificacionesProveedor($desde, $hasta, $proveedor)
    {
        $calificaciones = DB::select("select entrada_maderas.id, cubicajes.paqueta, total, longitud_madera, cantonera, hongos, rajadura, bichos, organizacion,
                        areas_transversal_max_min, areas_no_conveniente, nombre, razon_social
                        from cubicajes join calificacion_maderas on calificacion_maderas.entrada_madera_id = cubicajes.entrada_madera_id
                        join entradas_madera_maderas on entradas_madera_maderas.id = cubicajes.entrada_madera_id
                        join entrada_maderas on entrada_maderas.id = entradas_madera_maderas.entrada_madera_id
                        join proveedores on proveedores.id = entrada_maderas.proveedor_id
                        where proveedores.id = $proveedor and entrada_maderas.created_at between '$desde' AND '$hasta'
                        group by (cubicajes.paqueta, total, longitud_madera, cantonera, hongos, rajadura, bichos, organizacion,
                        areas_transversal_max_min, areas_no_conveniente, nombre, entrada_maderas.id, razon_social)
                        order by paqueta asc");

        $data = json_decode(json_encode($calificaciones));
        return $data;
    }

    /**
     * agrupa los datos pasados
     *
     * @param Object $data [ datos de la consulta ]
     * @return Array
     */

    public function agrupar($data) : array
    {
        $result = array();

        foreach ($data as $t) {
            $repetir = false;
            for ($i = 0; $i < count($result); $i++) {
                if($result[$i]['cubicaje_id'] == $t->id){
                    $transformacion = array(
                        'item' => $t->trnasformacion_final,
                        'cantidad' => $t->cantidad,
                        'orden_produccion_id' => $t->orden_produccion_id,
                        'pedido_id' => $t->pedido_id,
                        'cliente' => $t->nombre,
                    );
                    array_push($result[$i]['transformaciones'],$transformacion);
                    $repetir = true;
                    break;
                }
            }
            if ($repetir == false) {
                $result[] = array(
                    'entrada_madera_id' => $t->entrada_madera_id,
                    'cubicaje_id' => $t->id,
                    'paqueta' => $t->paqueta,
                    'bloque' => $t->bloque,
                    'alto' => $t->alto,
                    'largo' => $t->largo,
                    'ancho' => $t->ancho,
                    'fecha_creacion' => $t->created_at,
                    'tipo_madera' => $t->tipo_madera,
                    'transformaciones' => array(
                        array(
                            'item' => $t->trnasformacion_final,
                            'cantidad' => $t->cantidad,
                            'orden_produccion_id' => $t->orden_produccion_id,
                            'pedido_id' => $t->pedido_id,
                            'cliente' => $t->nombre,
                        )
                    )
                );

            }
        }
        return $result;
    }
}

