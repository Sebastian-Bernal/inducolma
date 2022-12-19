<?php

namespace App\Repositories\Reportes;

use App\Models\Cliente;
use App\Models\EstadoMaquina;
use App\Models\EventoProceso;
use App\Models\Pedido;
use App\Models\Proceso;
use App\Models\Subproceso;
use Illuminate\Support\Facades\DB;

class ConsultaProcesos {

    public function consultaDatos($request)
    {
        //return $request->all();
        $desde = $request->procesoDesde;
        $hasta = $request->procesoHasta;
        $tipoReporte = $request->tipoReporteConstruccion;
        $maquina = $request->maquina;
        $item  = $request->item;


        switch ($tipoReporte) {
            case '1':
                $data = $this->consultaProcesoFecha($maquina, $desde, $hasta);
                if (count($data) > 0) {
                    $encabezado = "PROCESOS DE LA MAQUINA {$data[0]->maquina} EN LAS FECHAS $desde - $hasta"; ;
                    $vista = 'modulos.reportes.procesos.inde-proceso-fecha';
                    $vistaPdf = 'modulos.reportes.procesos.pdf-procesos-fecha';

                }else{
                    $encabezado = '';
                    $vista = '';
                    $vistaPdf = '';
                }
                break;
            case '2':
                $data = $this->procesoItemFecha($maquina, $item, $desde, $hasta);
                if (count($data)> 0) {
                    $encabezado = "PROCESOS DE LA MAQUINA: {$data[0]->maquina}, ITEM {$data[0]->descripcion} EN LAS FECHAS $desde - $hasta";
                    $vista = 'modulos.reportes.procesos.index-proceso-item-maquina';
                    $vistaPdf = 'modulos.reportes.procesos.pdf-proceso-item-maquina';
                }else{
                    $encabezado = 'algo';
                    $vista = '';
                    $vistaPdf = '';
                }

                break;
            case '3':
                $data = $this->eventosProcesos($maquina,$desde, $hasta);
                if (count($data)> 0) {
                    $encabezado = "EVENTOS DE LA MAQUINA {$data[0]->maquina} EN LAS FECHAS $desde - $hasta";
                    $vista = 'modulos.reportes.procesos.index-eventos-maquina';
                    $vistaPdf = 'modulos.reportes.procesos.pdf-eventos-maquina';
                }else{
                    $encabezado = 'algo';
                    $vista = '';
                    $vistaPdf = '';
                }

                break;

            case '4':
                $data = $this->estadosProceso( $maquina, $desde, $hasta);
                if (count($data)> 0) {
                    $encabezado = "ESTADOS DE LA MAQUINA {$data[0]->maquina} EN LAS FECHAS $desde - $hasta";
                    $vista = 'modulos.reportes.procesos.index-estados-maquina';
                    $vistaPdf = 'modulos.reportes.procesos.pdf-estados-maquina';
                }else{
                    $encabezado = 'algo';
                    $vista = '';
                    $vistaPdf = '';
                }
                break;
            case '5':
                $data = $this->ordenesProduccion($desde, $hasta);
                if (count($data)> 0) {
                    $encabezado = "ORDENES DE PRODUCCION EN LAS FECHAS $desde -- $hasta";
                    $vista = 'modulos.reportes.procesos.index-ordenes-fecha';
                    $vistaPdf = 'modulos.reportes.procesos.pdf-ordenes-fecha';
                }else{
                    $encabezado = 'algo';
                    $vista = '';
                    $vistaPdf = '';
                }
                break;

            case '6':

                $data = $this->ordenesPendientes();
                if (count($data)> 0) {
                    $encabezado = "ORDENES DE PRODUCCION PENDIENTES";
                    $vista = 'modulos.reportes.procesos.index-ordenes-fecha';
                    $vistaPdf = 'modulos.reportes.procesos.pdf-ordenes-fecha';
                }else{
                    $encabezado = 'algo';
                    $vista = '';
                    $vistaPdf = '';
                }
                break;
            case '7':
                $data = $this->itemsEnProductos($request->item, $desde, $hasta);
                if (count($data)> 0) {
                    $encabezado = "ITEMS USADOS EN EL PRODUCTO: {$data[0]->producto}";
                    $vista = 'modulos.reportes.procesos.index-items-ensamblados';
                    $vistaPdf = 'modulos.reportes.procesos.pdf-items-ensamblados';
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
     * consulta los procesos por fecha
     * @param String $maquina
     * @param String $desde
     * @param String $hasta
     *
     *@return Array
     */

    public function consultaProcesoFecha($maquina, $desde, $hasta)
    {
        $proceso = Proceso::where('maquina_id', $maquina)
                            ->whereBetween('fecha_ejecucion',[$desde, $hasta])
                            ->first();
        $subproceso = Subproceso::where('proceso_id', $proceso->id)->first();

        if ($subproceso == "") {
            $proceso = DB::select("select maquinas.maquina, items.descripcion, procesos.cantidad_items,
                                procesos.sub_paqueta, procesos.hora_inicio, procesos.hora_fin, users.name
                                from
                                procesos join maquinas ON maquinas.id = procesos.maquina_id
                                join items ON items.id = procesos.item_id
                                join turno_usuarios on turno_usuarios.maquina_id = procesos.maquina_id and
                                turno_usuarios.fecha = procesos.fecha_ejecucion
                                join users on users.id = turno_usuarios.user_id
                                where maquinas.corte != 'ENSAMBLE' and
                                procesos.fecha_ejecucion between '$desde' and '$hasta' and
                                procesos.maquina_id = $maquina
                                group by (maquinas.maquina, items.descripcion, procesos.cantidad_items
                                ,procesos.sub_paqueta, procesos.hora_inicio, procesos.hora_fin, users.name)");
        } else {
            $proceso = DB::select("select maquinas.maquina, items.descripcion, procesos.cantidad_items,
                                procesos.sub_paqueta, procesos.hora_inicio, procesos.hora_fin, users.name,
                                sum(subprocesos.sobrante) as sobrante, sum(subprocesos.lena) as lena
                                from
                                procesos join maquinas ON maquinas.id = procesos.maquina_id
                                join items ON items.id = procesos.item_id
                                join subprocesos on subprocesos.proceso_id = procesos.id
                                join turno_usuarios on turno_usuarios.maquina_id = procesos.maquina_id and
                                turno_usuarios.fecha = procesos.fecha_ejecucion
                                join users on users.id = turno_usuarios.user_id
                                where maquinas.corte != 'ENSAMBLE' and
                                procesos.fecha_ejecucion between '$desde' and '$hasta'
                                and procesos.maquina_id = $maquina
                                group by (maquinas.maquina, items.descripcion, procesos.cantidad_items
                                ,procesos.sub_paqueta, procesos.hora_inicio, procesos.hora_fin, users.name)");
        }


        return $proceso;
    }

    /**
     * consulta los procesos por maquina, item fechas
     *
     * @param String $maquina
     * @param String $item
     * @param String $desde
     * @param String $hasta
     *
     *
     *@return Array
     */

    public function procesoItemFecha($maquina, $item, $desde, $hasta)
    {
        $proceso = Proceso::where('maquina_id', $maquina)
                            ->whereBetween('fecha_ejecucion',[$desde, $hasta])
                            ->first();
        $subproceso = Subproceso::where('proceso_id', $proceso->id)->first();

        if ($subproceso == "") {
            $proceso = DB::select("select maquinas.maquina, items.descripcion, procesos.cantidad_items,
                                    procesos.sub_paqueta, procesos.hora_inicio, procesos.hora_fin, users.name, items.id,
                                    pedidos.cantidad, diseno_producto_finales.descripcion as producto
                                    from
                                    pedidos join diseno_producto_finales ON diseno_producto_finales.id = pedidos.diseno_producto_final_id
                                    join ordenes_produccion on ordenes_produccion.pedido_id = pedidos.id
                                    join procesos ON procesos.orden_produccion_id = ordenes_produccion.id
                                    join maquinas ON maquinas.id = procesos.maquina_id
                                    join items ON items.id = procesos.item_id
                                    join turno_usuarios on turno_usuarios.maquina_id = procesos.maquina_id and turno_usuarios.fecha = procesos.fecha_ejecucion
                                    join users on users.id = turno_usuarios.user_id
                                    where maquinas.corte != 'ENSAMBLE' and
                                    procesos.fecha_ejecucion between '$desde' and '$hasta'
                                    and procesos.maquina_id = $maquina
                                    and procesos.item_id = $item
                                    group by (maquinas.maquina, items.descripcion, procesos.cantidad_items
                                    ,procesos.sub_paqueta, procesos.hora_inicio, procesos.hora_fin, users.name,
                                    items.id, pedidos.id, diseno_producto_finales.descripcion  )");
        } else {
            $proceso = DB::select("select maquinas.maquina, items.descripcion, procesos.cantidad_items,
                                procesos.sub_paqueta, procesos.hora_inicio, procesos.hora_fin, users.name,
                                sum(subprocesos.sobrante) as sobrante, sum(subprocesos.lena) as lena
                                from
                                procesos join maquinas ON maquinas.id = procesos.maquina_id
                                join items ON items.id = procesos.item_id
                                join subprocesos on subprocesos.proceso_id = procesos.id
                                join turno_usuarios on turno_usuarios.maquina_id = procesos.maquina_id and
                                turno_usuarios.fecha = procesos.fecha_ejecucion
                                join users on users.id = turno_usuarios.user_id
                                where maquinas.corte != 'ENSAMBLE' and
                                procesos.fecha_ejecucion between '$desde' and '$hasta'
                                and procesos.maquina_id = $maquina and
                                procesos.item_id = $item
                                group by (maquinas.maquina, items.descripcion, procesos.cantidad_items
                                ,procesos.sub_paqueta, procesos.hora_inicio, procesos.hora_fin, users.name)");
        }


        return $proceso;
    }

    /**
     * consulta los eventos de la maquina
     *
     * @param String $maquina [id de la maquina]
     * @param String $desde
     * @param String $hasta
     *
     * @return Array
     */

    public function eventosProcesos($maquina, $desde, $hasta)
    {
        $eventos = EventoProceso::join('eventos', 'eventos.id','=','evento_procesos.evento_id')
                                ->join('users','users.id', '=','evento_procesos.user_id')
                                ->join('maquinas','maquinas.id', '=','evento_procesos.maquina_id')
                                ->where('evento_procesos.maquina_id', $maquina)
                                ->whereBetween('evento_procesos.created_at', [$desde, $hasta])
                                ->get([
                                    'eventos.descripcion',
                                    'users.name',
                                    'evento_procesos.created_at',
                                    'maquinas.maquina'
                                ]);
        return $eventos;
    }


    /**
     * consulta los estados de la maquina en un rango de fechas
     * @param String $maquina
     * @param String $desde, $hasta
     *
     * @return Array
     */

    public function estadosProceso( $maquina, $desde , $hasta)
    {
        $estados = EstadoMaquina::join('estados', 'estados.id', '=', 'estado_maquinas.estado_id')
                                ->join('users','users.id', '=','estado_maquinas.user_id')
                                ->join('maquinas','maquinas.id', '=','estado_maquinas.maquina_id')
                                ->where('estado_maquinas.maquina_id', $maquina)
                                ->whereBetween('estado_maquinas.created_at', [$desde, $hasta])
                                ->get([
                                    'estados.descripcion',
                                    'users.name',
                                    'estado_maquinas.created_at',
                                    'maquinas.maquina'
                                ]);
        return $estados;
    }

    /**
     * consulta las ordenes de produccion en un rango de fechas
     *
     * @param String $desde, $hasta
     *
     * @return Array
     */

    public function ordenesProduccion($desde, $hasta)
    {
        $ordenes = Pedido::join('ordenes_produccion','pedidos.id', '=', 'ordenes_produccion.pedido_id')
                        ->join('diseno_producto_finales', 'diseno_producto_finales.id','=','pedidos.diseno_producto_final_id')
                        ->join('items', 'items.id', '=', 'ordenes_produccion.item_id')
                        ->join('clientes', 'clientes.id', '=', 'pedidos.cliente_id')
                        ->join('users', 'users.id','=','pedidos.user_id')
                        ->whereBetween('ordenes_produccion.created_at',[$desde, $hasta])
                        ->get([
                            'ordenes_produccion.id',
                            'clientes.nombre',
                            'diseno_producto_finales.descripcion as producto',
                            'items.descripcion as item',
                            'ordenes_produccion.created_at',
                            'users.name',
                            'ordenes_produccion.estado'
                        ]);

        return $ordenes;
    }

    /**
     * consulta las ordenes pendientes
     *
     * @param String $cliente
     *
     * @return Array
     */

    public function ordenesPendientes()
    {
        $ordenes = Pedido::join('ordenes_produccion','pedidos.id', '=', 'ordenes_produccion.pedido_id')
                ->join('diseno_producto_finales', 'diseno_producto_finales.id','=','pedidos.diseno_producto_final_id')
                ->join('items', 'items.id', '=', 'ordenes_produccion.item_id')
                ->join('clientes', 'clientes.id', '=', 'pedidos.cliente_id')
                ->join('users', 'users.id','=','pedidos.user_id')
                ->where('ordenes_produccion.created_at', '<', now())
                ->where('ordenes_produccion.estado', 'PENDIENTE')
                ->get([
                    'ordenes_produccion.id',
                    'clientes.nombre',
                    'diseno_producto_finales.descripcion as producto',
                    'items.descripcion as item',
                    'ordenes_produccion.created_at',
                    'users.name',
                    'ordenes_produccion.estado'
                ]);
        return $ordenes;
    }

    /**
     * consulta los items usados en un producto
     * consultar
     * @param String $producto
     *
     * @return Array
     */

    public function itemsEnProductos($producto, $desde, $hasta)
    {
        $itemsProducto = DB::select("
                        select diseno_producto_finales.descripcion as producto, items.descripcion as item,
                        sum(diseno_items.cantidad * pedido_producto.cantidad_producida ) as cantidad
                        from
                        pedido_producto join diseno_items on diseno_items.diseno_producto_final_id = pedido_producto.diseno_producto_final_id
                        join diseno_producto_finales on diseno_producto_finales.id = diseno_items.diseno_producto_final_id
                        join items on items.id = diseno_items.item_id
                        where diseno_producto_finales.id = $producto and
                        pedido_producto.created_at between '$desde' and '$hasta'
                        group by (diseno_producto_finales.descripcion, items.descripcion)
                    ");

        return $itemsProducto;
    }

    /**
     * consulta los items usados en un producto
     * consultar
     * @param String $desde, $hasta
     *
     * @return Array
     */

    public function procesosEnsambleFecha($maquina, $desde, $hasta)
    {

    }

}
