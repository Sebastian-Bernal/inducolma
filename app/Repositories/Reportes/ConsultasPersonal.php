<?php

namespace App\Repositories\Reportes;

use Illuminate\Support\Facades\DB;

class ConsultasPersonal {

    public function consultaDatos($request)
    {
        //return $request->all();
        $desde = $request->personalDesde;
        $hasta = $request->personalHasta;
        $tipoReporte = $request->tipoReportePersonal;

        switch ($tipoReporte) {
            case '1':
                $data = $this->consultaTurnosEmpleado($desde, $hasta);
                if (count($data) > 0) {
                    $encabezado = "TURNOS DEL PERSONAL EN LAS FECHAS DESDE: $desde -- HASTA: $hasta" ;
                    $vista = 'modulos.reportes.administrativos.personal.index-turno-empleado';
                    $vistaPdf = 'modulos.reportes.administrativos.personal.pdf-turno-empleado';
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
                    $encabezado = "CALIFICACION DE LA MADERA VIAJE No {$data[0]->id}";
                    $vista = 'modulos.reportes.administrativos.personal.index-horas-trabajadores';
                    $vistaPdf = 'modulos.reportes.administrativos.personal.pdf-horas-trabajadores';
                }else{
                    $encabezado = 'algo';
                    $vista = '';
                    $vistaPdf = '';
                }

                break;

            /*case '4':
                $data = $this->consultaCalificacionesProveedor($desde, $hasta, $request->filtroCubiaje2);
                if (count($data)> 0) {
                    $encabezado = "CALIFICACIONES DE LA MADERA POR PROVEEDOR {$data[0]->nombre}";
                    $vista = 'modulos.reportes.administrativos.personal.index-calificaciones-viaje';
                    $vistaPdf = 'modulos.reportes.administrativos.personal.pdf-calificaciones-viaje';
                }else{
                    $encabezado = 'algo';
                    $vista = '';
                    $vistaPdf = '';
                } */

        }
        return [$data, $encabezado, $vista, $vistaPdf];
    }

    /**
     * consulta los turnos los empleados en un rango de fechas
     *
     * @param String $desde
     * @param String $hasta
     *
     *@return Array
     */

    public function consultaTurnosEmpleado($desde, $hasta)
    {
        $turnos = DB::select("select name, maquinas.maquina, turno_usuarios.fecha, turnos.turno
                            from turno_usuarios join users ON users.id = turno_usuarios.user_id
                            join maquinas on maquinas.id = turno_usuarios.maquina_id
                            join turnos ON turnos.id = turno_usuarios.turno_id
                            where turno_usuarios.fecha between '$desde' and '$hasta'
                            order by fecha");

        return $turnos;
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
        $horas = DB::select("select name, entrada, salida , maquina, turno_usuarios.fecha
                            from users join tiepo_usuario_dias ON tiepo_usuario_dias.usuario_id = users.id
                            join maquinas on maquinas.id = tiepo_usuario_dias.maquina_id
                            join turno_usuarios on turno_usuarios.user_id = users.id
                            where turno_usuarios.fecha between '$desde' and '$hasta'"
                        );

        return $horas;
    }



}
