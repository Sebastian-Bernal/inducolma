<?php

namespace App\Repositories;

use App\Http\Requests\StoreEntraMaderaRequest;
use App\Models\User;
use App\Models\Contratista;
use App\Models\Recepcion;


class RegistroUsuarios{

    /**
     * function ingresoEmpleado, hace la consulta  a la tabla users si esta hace el registro en el modelp Recepcion
     * si no existe el usuario responde con un error
     */
    public function ingresoEmpleado($request){
        //return $request->cedula;

        $usuario = User::where('identificacion', $request->cedula)->first();
        if (empty($usuario)) {
            return response()->json(['error' => true, 'message' => "El usuario $request->cedula no es un empleado"]);
        } else {
            $registrado = Recepcion::where('cc', $usuario->identificacion)
                                    ->where('deleted_at', null)
                                    ->first();
            if (empty($registrado)) {
                $recepcion = new Recepcion();
                $recepcion->cc = $usuario->identificacion;
                $recepcion->nombre_completo = strtoupper($usuario->primer_nombre." ".$usuario->primer_apellido);
                $recepcion->visitante = false;
                $recepcion->type = 'EMPLEADO';
                $recepcion->user_id = Auth()->user()->id;
                $recepcion->save();
                return response()->json(['error' => false,
                                        'message' => "El usuario $request->cedula fue registrado",
                                        'title' => "Ingreso de usuario",
                    ]);
            } else {
                $registrado->deleted_at = now();
                $registrado->save();
                return response()->json(['error' => false,
                                        'message' => "SALIDA DEL USUARIO $request->cedula",
                                        'title' => "Salida de usuario" ]);
            }
        }
    }

    /**
     * function ingresoEmpleado, hace la consulta  a la tabla users si esta hace el registro en el modelp Recepcion
     * si no existe el usuario responde con un error
     */
    public function ingresoContratista($request){
        //return $request->cedula;

        $usuario = Contratista::where('cedula', $request->cedula)
                                ->where('acceso', true)
                                ->first();
       // return $usuario;
        if (empty($usuario)) {
            return response()->json(['error' => true,
                                    'message' => "El usuario $request->cedula no es un contratista, o no tiene permitido el acceso, contacte al administrador"]);
        } else {
            $registrado = Recepcion::where('cc', $usuario->cedula)
                                    ->where('deleted_at', null)
                                    ->first();
            if (empty($registrado)) {
                $recepcion = new Recepcion();
                $recepcion->cc = $usuario->cedula;
                $recepcion->nombre_completo = strtoupper($usuario->primer_nombre." ".$usuario->primer_apellido);
                $recepcion->visitante = false;
                $recepcion->type = 'CONTRATISTA';
                $recepcion->user_id = Auth()->user()->id;
                $recepcion->save();
                return response()->json(['error' => false,
                                        'message' => "El contratista $request->cedula fue registrado",
                                        'title' => "Ingreso de contratista",
                    ]);
            } else {
                $registrado->deleted_at = now();
                $registrado->save();
                return response()->json(['error' => false,
                                        'message' => "Salida contratista $request->cedula",
                                        'title' => "Salida de contratista" ]);
            }
        }
    }

    /**
     * Registro de un nuevo visitante, si no esta registrado en recepciones se crea el registro, si ya esta registrado y
     * la fecha deleted_at es null seactualiza.
     */

    public function ingresoVisitante($request)
    {
        $recepcion = Recepcion::where('cc', $request->cc)
                                ->where('deleted_at', null)
                                ->first();
        if (empty($recepcion)) {
            $recepcion = new Recepcion();
            $recepcion->cc = $request->cc;
            $recepcion->nombre_completo = strtoupper($request->primer_nombre . " " . $request->primer_apellido);
            $recepcion->visitante = true;
            $recepcion->type = 'VISITANTE';
            $recepcion->user_id = Auth()->user()->id;
            $recepcion->save();
            return response()->json(['error' => false,
                                    'message' => "El visitante $recepcion->nombre_completo fue registrado",
                                    'title' => "Ingreso de visitante",
                    ]);
        } else {
            $recepcion->deleted_at = now();
            $recepcion->save();
            return response()->json(['error' => false,
                                    'message' => "Salida del visitante $recepcion->nombre_completo",
                                    'title' => "Salida de visitante" ]);
        }
    }

}
