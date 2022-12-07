<?php

namespace App\Repositories;

use App\Models\EstadoMaquina;
use App\Models\EventoProceso;
use App\Models\Subproceso;
use App\Models\TiepoUsuarioDia;
use App\Models\TurnoUsuario;
use Illuminate\Support\Facades\Auth;

class RegistroAsistencia {

    public function guardar($request)
    {
        $asistencia  = new TiepoUsuarioDia();
        $asistencia->fecha = date('Y-m-d');
        $asistencia->entrada = date('G:i:s');
        $asistencia->usuario_id = $request->usuario_id;
        $asistencia->maquina_id = $request->maquina_id;

        $turno = TurnoUsuario::where('user_id', $request->usuario_id)
                            ->where('maquina_id', $request->maquina_id)
                            ->where('fecha', date('Y-m-d'))
                            ->first();


        if ($request->estado == true) {

            try {
                $asistencia->save();
                $turno->asistencia = true;
                $turno->save();
                $usuarios = $this->usuariosDia();
                return response()->json(array('error' => false, 'mensaje' => "Asistencia guardada", "usuarios" => $usuarios ));
            } catch (\Throwable $th) {
                $usuarios = $this->usuariosDia();
                return response()->json(array('error' => true, 'mensaje' => "Asistencia no pudo ser guardada", "usuarios" => $usuarios ));
            }
        } else {
            try {
                $turno->asistencia = false;
                $turno->save();
                $evento = EventoProceso::where('maquina_id', $turno->maquina_id)->latest()->first();
                $evento->user_id = $turno->user_id;
                $evento->save();
                $usuarios = $this->usuariosDia();
                return response()->json(array('error' => false, 'mensaje' => "Falta guardada", "usuarios" => $usuarios ));
            } catch (\Throwable $th) {
                $usuarios = $this->usuariosDia();
                return response()->json(array('error' => true, 'mensaje' => "Falta no pudo ser guardada", "usuarios" => $usuarios ));
            }
        }
    }

    /**
     * devuelve una coleccion de usuarios para el turno en la fecha actual
     *
     * @param request $reques
     *
     * @return Response json
     */

    public function usuariosDia()
    {
        $turno = TurnoUsuario::where('user_id',Auth::user()->id)
                                ->where('fecha', date('Y-m-d'))
                                ->first();
        $usuarios = TurnoUsuario::where('turno_id', $turno->turno_id)
                            ->where('asistencia', null)
                            ->where('fecha',date('Y-m-d'))
                            ->where('maquina_id', $turno->maquina_id)
                            ->get()
                            ->load('user');
                            //->except($turno->id);
        return $usuarios;
    }

    /**
     * guarda el estado de la maquina
     * @param Request $request
     * @return Response JSON
     */
    public function guardaEstado($request)
    {
        $estado = new EstadoMaquina();
        $estado->maquina_id = $request->maquina_id;
        $estado->estado_id = $request->estado_id;
        $estado->fecha = now();
        //$estado->save();
        try {
            $estado->save();
            return response()->json(array('error' => False, 'mensaje' => 'Esado de la maquina guardado'));
        } catch (\Throwable $th) {
            return response()->json(array('error' => true, 'mensaje' => 'Esado de la maquina no pudo ser guardado'));
        }
    }

    public function apagarMaquina($request)
    {
        $ultimo_subproceso = Subproceso::where('maquina_id', $request->maquina_id)
                                        ->latest('id')
                                        ->first();

        $estado = new EstadoMaquina();
        $estado->maquina_id = $request->maquina_id;
        $estado->estado_id = 2;
        $estado->fecha = now();//$ultimo_subproceso->created_at;
        try {
            $estado->save();
            return response()->json(array('error' => False, 'mensaje' => 'Esado de la maquina guardado'));
        } catch (\Throwable $th) {
            return response()->json(array('error' => true, 'mensaje' => 'Esado de la maquina no pudo ser guardado'));
        }

    }

}
