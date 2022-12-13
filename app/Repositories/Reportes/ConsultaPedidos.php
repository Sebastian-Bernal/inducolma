<?php

namespace App\Repositories\Reportes;

use App\Models\Cliente;
use App\Models\Pedido;
use Illuminate\Support\Facades\DB;

class ConsultaPedidos {

    public function consultaDatos($request)
    {
        ///return $request->all();
        $desde = $request->pedidoDesde;
        $hasta = $request->pedidoHasta;
        $tipoReporte = $request->tipoReportePedidos;
        $cliente = Cliente::where('id', $request->filtroPedido1)->first();
        switch ($tipoReporte) {
            case '1':
                $data = $this->consultaPedidoFechaCliente($request->filtroPedido1, $desde, $hasta);
                if (count($data) > 0) {
                    $encabezado = "PEDIDOS PENDIENTES PARA EL CLIENTE: $request->filtroPedido1" ;
                    $vista = 'modulos.reportes.ventas.index-pendientes-cliente';
                    $vistaPdf = 'modulos.reportes.ventas.pdf-pendientes-cliente';

                }else{
                    $encabezado = '';
                    $vista = '';
                    $vistaPdf = '';
                }
                break;
            case '2':
                $data = $this->consultaEventosEmpleados($desde,$hasta);
                if (count($data)> 0) {
                    $encabezado = "EVENTOS DE USUARIO EN LAS FECHAS DESDE: $desde -- HASTA: $hasta";
                    $vista = 'modulos.reportes.administrativos.personal.index-eventos-usuarios';
                    $vistaPdf = 'modulos.reportes.administrativos.personal.pdf-eventos-usuarios';
                }else{
                    $encabezado = 'algo';
                    $vista = '';
                    $vistaPdf = '';
                }

                break;
            case '3':
                $data = $this->horasLaboradasPersonal($desde, $hasta);
                if (count($data)> 0) {
                    $encabezado = "HORAS LABORADAS DE EMPLEADOS EN LAS FECHAS DEL {$desde} AL {$hasta}";
                    $vista = 'modulos.reportes.administrativos.personal.index-horas-trabajadores';
                    $vistaPdf = 'modulos.reportes.administrativos.personal.pdf-horas-trabajadores';
                }else{
                    $encabezado = 'algo';
                    $vista = '';
                    $vistaPdf = '';
                }

                break;

            case '4':
                $data = $this->consultaHorasEmpleado($desde, $hasta, $request->filtroPersonal);
                if (count($data)> 0) {
                    $encabezado = "HORAS LABORADAS DEL EMPLEADO: {$data[0]->name} NE LAS FECHAS DEL $desde AL $hasta";
                    $vista = 'modulos.reportes.administrativos.personal.index-horas-empleado';
                    $vistaPdf = 'modulos.reportes.administrativos.personal.pdf-horas-empleado';
                }else{
                    $encabezado = 'algo';
                    $vista = '';
                    $vistaPdf = '';
                }

            case '5':
                $data = $this->consultaIngresoTerceros($desde, $hasta, $request->filtroPersonal);
                if (count($data)> 0) {
                    $encabezado = "INGRESO DE PERSONAL EXTERNO: {$data[0]->nombre} EN LAS FECHAS DEL $desde AL $hasta";
                    $vista = 'modulos.reportes.administrativos.personal.index-ingreso-terceros';
                    $vistaPdf = 'modulos.reportes.administrativos.personal.pdf-ingreso-terceros';
                }else{
                    $encabezado = 'algo';
                    $vista = '';
                    $vistaPdf = '';
                }

        }
        return [$data, $encabezado, $vista, $vistaPdf, $cliente];
    }

    /**
     * consulta los turnos los empleados en un rango de fechas
     *
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
     * consulta de los ventos tipo usuario de los empleados
     *
     * @param String $desde
     * @param String $hasta
     *
     *@return Array
     */

    public function consultaEventosEmpleados($desde, $hasta)
    {
        $eventos = DB::select("select name, eventos.descripcion, evento_procesos.observacion, turno_usuarios.fecha
                                from evento_procesos join users on evento_procesos.user_id = users.id
                                join turno_usuarios on turno_usuarios.user_id = users.id
                                join eventos on eventos.id = evento_procesos.evento_id
                                join tipo_eventos ON tipo_eventos.id = eventos.tipo_evento_id
                                where tipo_eventos.tipo_evento = 'USUARIO'
                                and turno_usuarios.fecha between '$desde' and '$hasta'
                                ");
        return $eventos;
    }

    /**
     *
     */

    public function horasLaboradasPersonal($desde, $hasta)
    {
        $horas = DB::select("select name, entrada, salida , maquina, turno_usuarios.fecha, (salida-entrada) as horas
                            from users join tiepo_usuario_dias ON tiepo_usuario_dias.usuario_id = users.id
                            join maquinas on maquinas.id = tiepo_usuario_dias.maquina_id
                            join turno_usuarios on turno_usuarios.user_id = users.id
                            where turno_usuarios.fecha between '$desde' and '$hasta'"
                        );

        return $horas;
    }


    /**
     *
     */

    public function consultaHorasEmpleado($desde , $hasta, $empleado)
    {
        $horas = DB::select("select name, entrada, salida , maquina, turno_usuarios.fecha, (salida-entrada) as horas
                            from users join tiepo_usuario_dias ON tiepo_usuario_dias.usuario_id = users.id
                            join maquinas on maquinas.id = tiepo_usuario_dias.maquina_id
                            join turno_usuarios on turno_usuarios.user_id = users.id
                            where users.id = $empleado
                            and turno_usuarios.fecha between '$desde' and '$hasta'"
                        );

        return $horas;
    }

    /**
     *
     */

    public function consultaIngresoTerceros($desde, $hasta, $tercero)
    {
        $terceros = DB::select("select CONCAT(primer_nombre, ' ', segundo_nombre, ' ', primer_apellido, ' ', segundo_apellido) as nombre,
                                recepcions.cc,recepcions.created_at, recepcions.deleted_at
                                from contratistas join recepcions on contratistas.cedula = CAST(recepcions.cc as varchar)
                                where contratistas.id = $tercero
                                and recepcions.created_at between '$desde' and '$hasta'");

        return $terceros;
    }

}
