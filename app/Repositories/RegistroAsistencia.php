<?php

namespace App\Repositories;

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

        if ($request->estado) {

            try {
                $asistencia->save();
                $turno->asistencia = true;
                $turno->save();
                $usuarios = $this->usuariosDia($request);
                return response()->json(array('error' => false, 'mensaje' => "Asistencia guardada", "usuarios" => $usuarios ));
            } catch (\Throwable $th) {
                $usuarios = $this->usuariosDia($request);
                return response()->json(array('error' => true, 'mensaje' => "Asistencia no pudo ser guardada", "usuarios" => $usuarios ));
            }
        } else {
            try {
                $turno->asistencia = false;
                $turno->save();
                $usuarios = $this->usuariosDia($request);
                return response()->json(array('error' => false, 'mensaje' => "Asistencia guardada", "usuarios" => $usuarios ));
            } catch (\Throwable $th) {
                $usuarios = $this->usuariosDia($request);
                return response()->json(array('error' => true, 'mensaje' => "Asistencia no pudo ser guardada", "usuarios" => $usuarios ));
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

    public function usuariosDia($request)
    {
        $turno = TurnoUsuario::where('user_id',Auth::user()->id)
                                ->where('fecha', date('Y-m-d'))
                                ->first();
        $usuarios = TurnoUsuario::where('turno_id', $request->turno_id)
                            ->where('fecha',date('Y-m-d'))
                            ->get()
                            ->load('user')
                            ->except($turno->id);
        return $usuarios;
    }

    /**
     * crea un nueva asignacion de turno retorna json con datos de éxito o fallo
     */

    public function nuevoAuxiliar($request)
    {
        $nuevo_auxiliar = new TurnoUsuario();
        $nuevo_auxiliar->user_id = $request->usuario_id;
        $nuevo_auxiliar->maquina_id = $request->maquina_id;
        $nuevo_auxiliar->turno_id = $request->turno_id;
        $nuevo_auxiliar->fecha = date('Y-m-d');
        $nuevo_auxiliar->asistencia = true;

        $asistencia  = new TiepoUsuarioDia();
        $asistencia->fecha = date('Y-m-d');
        $asistencia->entrada = date('G:i:s');
        $asistencia->usuario_id = $request->usuario_id;
        $asistencia->maquina_id = $request->maquina_id;

        $asignado = TurnoUsuario::where('user_id', $nuevo_auxiliar->user_id)
                                ->where('fecha', $nuevo_auxiliar->fecha)
                                ->first();


        if ($asignado == ''){
            try {
                $asistencia->save();
                $nuevo_auxiliar->save();
                return response()->json(array('error' => false, 'mensaje' => "El axiliar se registro con éxito", ));
            } catch (\Throwable $th) {
                return response()->json(array('error' => true, 'mensaje' => "El axiliar no pudo ser registrado", ));
            }
        } else {
            return response()->json(array('error' => true, 'mensaje' => "El auxiliar ya tiene un turno asignado", ));
        }
    }

}
