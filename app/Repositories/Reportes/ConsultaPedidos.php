<?php

namespace App\Repositories\Reportes;

use App\Models\Cliente;
use App\Models\Pedido;
use Illuminate\Support\Facades\DB;

class ConsultaPedidos {

    public function consultaDatos($request)
    {
        //return $request->all();
        $desde = $request->pedidoDesde;
        $hasta = $request->pedidoHasta;
        $tipoReporte = $request->tipoReportePedidos;
        $cliente = Cliente::where('id', $request->filtroPedido1)->first();
        switch ($tipoReporte) {
            case '1':
                $data = $this->consultaPedidoFechaCliente($request->filtroPedido1, $desde, $hasta);
                if (count($data) > 0) {
                    $encabezado = "PEDIDOS PENDIENTES  DEL CLIENTE: {$cliente->nombre}"; ;
                    $vista = 'modulos.reportes.ventas.index-reporte-cliente';
                    $vistaPdf = 'modulos.reportes.ventas.pdf-reporte-cliente';

                }else{
                    $encabezado = '';
                    $vista = '';
                    $vistaPdf = '';
                }
                break;
            case '2':
                $data = $this->pedidosPendientesCliente($cliente->id);
                if (count($data)> 0) {
                    $encabezado = "PEDIDOS PENDIENTES DEL CLIENTE: {$cliente->nombre}";
                    $vista = 'modulos.reportes.ventas.index-reporte-cliente';
                    $vistaPdf = 'modulos.reportes.ventas.pdf-reporte-cliente';
                }else{
                    $encabezado = 'algo';
                    $vista = '';
                    $vistaPdf = '';
                }

                break;
            case '3':
                $data = $this->pedidosVencidosCliente($cliente->id);
                if (count($data)> 0) {
                    $encabezado = "PEDIDOS VENCIDOS DEL CLIENTE {$cliente->nombre}";
                    $vista = 'modulos.reportes.ventas.index-reporte-cliente';
                    $vistaPdf = 'modulos.reportes.ventas.pdf-reporte-cliente';
                }else{
                    $encabezado = 'algo';
                    $vista = '';
                    $vistaPdf = '';
                }

                break;

            case '4':
                $data = $this->pedidosTerminadosFecha($desde, $hasta);
                if (count($data)> 0) {
                    $encabezado = "PEDIDOS TERMINADOS DESDE: $desde AL $hasta";
                    $vista = 'modulos.reportes.ventas.index-reporte-cliente';
                    $vistaPdf = 'modulos.reportes.ventas.pdf-reporte-cliente';
                }else{
                    $encabezado = 'algo';
                    $vista = '';
                    $vistaPdf = '';
                }
                break;
            case '5':
                $data = $this->consultaPedidosTerminadosCliente($cliente->id);
                if (count($data)> 0) {
                    $encabezado = "PEDIDOS TERMINADOS DEL CLIENTE: {$cliente->nombre}";
                    $vista = 'modulos.reportes.ventas.index-reporte-cliente';
                    $vistaPdf = 'modulos.reportes.ventas.pdf-reporte-cliente';
                }else{
                    $encabezado = 'algo';
                    $vista = '';
                    $vistaPdf = '';
                }
                break;

            case '6':

                $data = $this->consultaProceosPedido($request->nPedido);
                if (count($data)> 0) {
                    $encabezado = "PROCESOS DEL PEDIDO: {$request->nPedido} DEL CLIENTE: {$data[0]->cliente}";
                    $vista = 'modulos.reportes.ventas.index-procesos-pedido';
                    $vistaPdf = 'modulos.reportes.ventas.pdf-procesos-pedido';
                }else{
                    $encabezado = 'algo';
                    $vista = '';
                    $vistaPdf = '';
                }
                break;
            case '7':
                $data = $this->consultaUsuariosProceso($request->nPedido);
                if (count($data)> 0) {
                    $encabezado = "USUARIOS EN EL PEDIDO: {$request->nPedido} DEL CLIENTE: {$data[0]->cliente}";
                    $vista = 'modulos.reportes.ventas.index-usuarios-procesos';
                    $vistaPdf = 'modulos.reportes.ventas.pdf-usuarios-procesos';
                }else{
                    $encabezado = 'algo';
                    $vista = '';
                    $vistaPdf = '';
                }
                break;

        }
        return [$data, $encabezado, $vista, $vistaPdf, $cliente];
    }

    /**
     * consulta los pedidos de un cliente en un rango de fechas
     * @param String $cliente
     * @param String $desde
     * @param String $hasta
     *
     *@return Array
     */

    public function consultaPedidoFechaCliente($cliente, $desde, $hasta)
    {
        $pedidos = Pedido::join('diseno_producto_finales', 'diseno_producto_finales.id','=','pedidos.diseno_producto_final_id')
                        ->join('users', 'users.id','=','pedidos.user_id')
                        ->where('cliente_id', $cliente)
                        ->whereBetween('pedidos.created_at',[$desde,$hasta])
                        ->get([
                            'pedidos.id',
                            'diseno_producto_finales.descripcion',
                            'pedidos.cantidad',
                            'pedidos.fecha_entrega',
                            'users.name'
                        ]);

        return $pedidos;
    }

    /**
     * consulta los pedidos pendientes de un cliente
     *
     * @param String $cliente
     *
     *
     *@return Array
     */

    public function pedidosPendientesCliente($cliente)
    {
        $pendientes = Pedido::join('diseno_producto_finales', 'diseno_producto_finales.id','=','pedidos.diseno_producto_final_id')
                        ->join('users', 'users.id','=','pedidos.user_id')
                        ->where('cliente_id', $cliente)
                        ->where('pedidos.estado', 'PENDIENTE')
                        ->get([
                            'pedidos.id',
                            'diseno_producto_finales.descripcion',
                            'pedidos.cantidad',
                            'pedidos.fecha_entrega',
                            'users.name'
                        ]);

        return $pendientes;
    }

    /**
     * consulta los pedidos pendientes de un cliente
     *
     * @param String $cliente [id del cliente]
     *
     * @return Array
     */

    public function pedidosVencidosCliente($cliente)
    {
        $pendientes = Pedido::join('diseno_producto_finales', 'diseno_producto_finales.id','=','pedidos.diseno_producto_final_id')
                        ->join('users', 'users.id','=','pedidos.user_id')
                        ->where('cliente_id', $cliente)
                        ->where('pedidos.estado','PENDIENTE')
                        ->where('pedidos.fecha_entrega','<', now())
                        ->get([
                            'pedidos.id',
                            'diseno_producto_finales.descripcion',
                            'pedidos.cantidad',
                            'pedidos.fecha_entrega',
                            'users.name'
                        ]);

        return $pendientes;
    }


    /**
     * consulta los pedidos terminado en un rango de fechas
     *
     * @param string $desde, $hasta
     *
     * @return Array
     */

    public function pedidosTerminadosFecha($desde , $hasta)
    {
        $terminados = Pedido::join('diseno_producto_finales', 'diseno_producto_finales.id','=','pedidos.diseno_producto_final_id')
                        ->join('users', 'users.id','=','pedidos.user_id')
                        ->where('pedidos.estado','TERMINADO')
                        ->whereBetween('pedidos.fecha_solicitud', [$desde, $hasta])
                        ->get([
                            'pedidos.id',
                            'diseno_producto_finales.descripcion',
                            'pedidos.cantidad',
                            'pedidos.fecha_entrega',
                            'users.name'
                        ]);

        return $terminados;
    }

    /**
     * consulta los pedidos terminados de un cliente
     *
     * @param String $cliente
     *
     * @return Array
     */

    public function consultaPedidosTerminadosCliente($cliente)
    {
        $terminadosCliente = Pedido::join('diseno_producto_finales', 'diseno_producto_finales.id','=','pedidos.diseno_producto_final_id')
                        ->join('users', 'users.id','=','pedidos.user_id')
                        ->where('pedidos.estado','TERMINADO')
                        ->where('pedidos.cliente_id',$cliente)
                        ->get([
                            'pedidos.id',
                            'diseno_producto_finales.descripcion',
                            'pedidos.cantidad',
                            'pedidos.fecha_entrega',
                            'users.name'
                        ]);

        return $terminadosCliente;
    }

    /**
     * consulta los pedidos terminados de un cliente
     *
     * @param String $cliente
     *
     * @return Array
     */

    public function consultaProceosPedido($pedido)
    {
        $procesosPedido = Pedido::join('diseno_producto_finales', 'diseno_producto_finales.id','=','pedidos.diseno_producto_final_id')
                        ->join('ordenes_produccion', 'ordenes_produccion.pedido_id','=','pedidos.id')
                        ->join('items', 'items.id', '=','ordenes_produccion.item_id')
                        ->join('procesos','procesos.orden_produccion_id','=','ordenes_produccion.id')
                        ->join('clientes','clientes.id','=','pedidos.cliente_id')
                        ->join('maquinas','maquinas.id','=','procesos.maquina_id')
                        ->where('pedidos.id',(integer)$pedido)
                        ->get([
                            'pedidos.id as pedido_id',
                            'clientes.nombre as cliente',
                            'ordenes_produccion.id as orden_id',
                            'ordenes_produccion.estado as estado_orden',
                            'items.descripcion as item',
                            'procesos.id as id_proceso',
                            'procesos.estado as estado_proceso',
                            'maquinas.maquina',
                            'diseno_producto_finales.descripcion as producto',
                        ]);

        return $procesosPedido;
    }

    /**
     * consulta los pedidos terminados de un cliente
     * consultar
     * @param String $cliente
     *
     * @return Array
     */

    public function consultaUsuariosProceso($pedido)
    {
        $usuariosPedido = DB::select(" select distinct pedidos.id as pedido_id, ordenes_produccion.id as orden_id, ordenes_produccion.estado as estado_orden,
                        procesos.id as id_proceso, procesos.estado as estado_proceso, clientes.nombre as cliente,
                        items.descripcion as item, maquinas.maquina, diseno_producto_finales.descripcion as producto,
                        users.name
                        from pedidos
                        join diseno_producto_finales on diseno_producto_finales.id = pedidos.diseno_producto_final_id
                        join ordenes_produccion ON ordenes_produccion.pedido_id = pedidos.id
                        join items on items.id = ordenes_produccion.item_id
                        join procesos ON procesos.orden_produccion_id = ordenes_produccion.id
                        join clientes on clientes.id = pedidos.cliente_id
                        join maquinas on maquinas.id = procesos.maquina_id
                        join turno_usuarios on turno_usuarios.fecha = procesos.fecha_ejecucion and turno_usuarios.maquina_id = procesos.maquina_id
                        join users on users.id = turno_usuarios.user_id
                        where pedidos.id = 22

                    ");

        return $usuariosPedido;
    }

}
