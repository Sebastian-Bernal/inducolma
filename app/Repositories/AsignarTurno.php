<?php

namespace App\Repositories;

use App\Models\TurnoUsuario;
use App\Models\User;

class AsignarTurno {

    public function crearTurnos($request)
    {
        $usuario = User::findOrFail($request->usuario);
        if ($request->desde == $request->hasta) {
            $fecha = $request->desde;
            $turno = $this->guardarTurno($request, $fecha);
            if ($turno != False){
                return ['status' => "El turno para el usuario: $usuario->name, fue creado con éxito"];
            }else{
                return ['status' => "No se pudo asignar el turno, comuniquese con el administrador de la aplicacion"];
            }
        } else{
            $dias = date_diff(date_create($request->desde), date_create($request->hasta));
            $dias = $dias->format('%a')+1;
            for ($i=0; $i < $dias ; $i++) {
                $fecha =  date("d-m-Y",strtotime($request->desde."+ ".$i." days"));
                $turno = $this->guardarTurno($request, $fecha);
                if ($turno !== false) {
                    continue;
                } else{
                    return ['status' => "No se pudo asignar el turno, comuniquese con el administrador de la aplicacion"];
                    break;
                }
            }
            return ['status' => "Los turnos para el usuario: $usuario->name, se crearon con éxito"];

        }

    }

    /**
     * devulve la confirmacion del turno creado en la base de datos
     * recibe por parametro el request con los datos del turno a crear
     *
     * @param Request $request
     * @return boolean
     */

    public function guardarTurno($request, $fecha)
    {

        $usuario = User::find($request->usuario);
        try {
            $usuario->turnos()->attach([$request->turno => ['maquina_id' => $request->maquina, 'fecha' => $fecha]]);
            return $usuario;
        } catch (\Throwable $th) {
            return false;
        }

    }

    /**
     *
     */
    public function consultaTurno($request)
    {
        if ($request->hasta == null) {
            $turno = TurnoUsuario::where('user_id', $request->usuario)
                    ->where('fecha', $request->desde)
                    ->orderBy('fecha', 'asc')
                    ->get();

            if(count($turno) <= 0) {
                return response()->json(array('turno' => false));
            } else {
                return response()->json(array('turno' => true, 'turnos' => $turno));
            }
        } else {
            $turnos = TurnoUsuario::where('user_id', $request->usuario)
                                ->whereBetween('fecha', [$request->desde, $request->hasta])
                                ->orderBy('fecha', 'asc')
                                ->get();

            if(count($turnos) <= 0) {
                return response()->json(array('turno' => false));
            } else {
                return response()->json(array('turno' => true, 'turnos' => $turnos));
            }
        }

    }



}
