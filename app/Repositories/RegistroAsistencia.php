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
        $asistencia->entrada = time();
        $asistencia->usuario_id = $request->usuario_id;
        $asistencia->maquina_id = $request->maquina_id;

        $turno = TurnoUsuario::where('user_id', $request->usuario_id)
                            ->where('maquina_id', $request->maquina_id)
                            ->where('fecha', date('Y-m-d'))
                            ->first();

        if ($request->estado) {
            try {
                $asistencia->save();
                $turno->update(['asistencia' => $request->estado]);
                $usuarios = $this->usuariosDia($request);
                return response()->json(array('error' => false, 'mensaje' => "asistencia guardada", "usuarios" => $usuarios ));
            } catch (\Throwable $th) {
                return response()->json(array('error' => true, 'mensaje' => "asistencia no pudo ser guardada", "usuarios" => $usuarios ));
            }
        } else {
            try {
                $turno->update(['asistencia' => $request->estado]);
                $usuarios = $this->usuariosDia($request);
                return response()->json(array('error' => false, 'mensaje' => "Asistencia guardada", "usuarios" => $usuarios ));
            } catch (\Throwable $th) {
                return response()->json(array('error' => true, 'mensaje' => "asistencia no pudo ser guardada", "usuarios" => $usuarios ));
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
        $usuarios = TurnoUsuario::join('users','users.id', '=', 'turno_usuarios.user_id')
                            ->where('turno_id', $request->turno_id)
                            ->where('fecha',date('Y-m-d'))
                            ->get(['users.id','users.name','fecha', 'asistencia', 'turno_id', 'maquina_id'])
                            ->except($turno->id);
        return $usuarios;
    }

}
