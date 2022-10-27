<?php

namespace App\Repositories;

use App\Models\Maquina;
use App\Models\OrdenProduccion;
use App\Models\Proceso;
use App\Models\Subproceso;
use Illuminate\Support\Facades\Auth;

class guardarSubproceso{

    public function guardar($subproceso_existente, $request)
    {
        if ($subproceso_existente == null) {
            if ($request->terminar == 3) {
                return $this->guardaSubproceso($subproceso_existente, $request, 3);
            }
            return $this->guardaSubproceso($subproceso_existente, $request, 1);
        } else{
            if((integer)$request->terminar == 3){
                return $this->guardaSubproceso($subproceso_existente, $request, 3);
            }
            return $this->guardaSubproceso($subproceso_existente, $request, 2);
        }
    }

    public function guardaSubproceso($subproceso_existente, $request, $accion)
    {
        $subproceso = new Subproceso();
        $subproceso->paqueta = $request->paqueta;
        $subproceso->observacion_subproceso = $request->observacionSubpaqueta;
        $subproceso->entrada = $request->itemEntrante;
        $subproceso->salida = $request->itemSaliente;
        $subproceso->cantidad_entrada = $request->cantidadEntrada;
        $subproceso->cantidad_salida = $request->cantidadSalida;
        $subproceso->fecha_ejecucion = now();
        if($accion == 1){
            $subproceso->sub_paqueta = 1;
        }else if($accion == 3 && $subproceso_existente == null){
            $subproceso->sub_paqueta = 1;
        }
        else{
            $subproceso->sub_paqueta = $subproceso_existente->sub_paqueta + 1;
        }
        $subproceso->tarjeta_entrada = $request->tarjetaEntrada;
        $subproceso->tarjeta_salida = $request->tarjetaSalida;
        $subproceso->sobrante = $request->sobrante;
        $subproceso->lena = $request->lena;
        $subproceso->proceso_id = $request->procesoId;
        $subproceso->user_id = Auth::user()->id;
        $subproceso->maquina_id = $request->maquinaId;
        $subproceso->cm3_salida = $request->cm3Salida;
        $subproceso->largo = $request->largo;
        $subproceso->alto  = $request->alto;
        $subproceso->ancho = $request->ancho;

        try {
            $subproceso->save();
            if ($accion == 1) {
                $this->actualizaProceso($request, 1);
            } else if ($accion == 2 ) {
                $this->actualizaProceso($request, 2);
            }else {
                if ($subproceso_existente == null) {
                    $subproceso_existente = $subproceso;
                }
                $this->actualizaProceso($request,3, $subproceso_existente);
                return redirect()->route('trabajo-maquina.index')->with('status','La orden se guardo con éxito');
            }
            return redirect()->route('trabajo-maquina.show',$request->procesoId)->with('status','La subpaqueta se guardo con éxito');
        } catch (\Throwable $th) {
            return redirect()->back()->with('status','La subpaqueta no pudo ser guardada');
        }
    }

    public function actualizaProceso($request, $accion, $subproceso_existente = null)
    {
        $proceso = Proceso::find($request->procesoId);
        $orden = OrdenProduccion::where('id', $proceso->orden_produccion_id)->first();
       // return $orden;
        $proceso->cm3_salida += (float)$request->cm3Salida;
        if ($accion == 1) {
            $proceso->estado = 'EN PRODUCION';
            $proceso->hora_inicio = date('G:i:s');
            $proceso->fecha_ejecucion= now();
            $orden->estado = 'EN PRODUCION';
            $orden->save();
        } else if ($accion == 3){
            $proceso->estado = 'TERMINADO';
            $proceso->hora_fin = date('G:i:s');
            $proceso->fecha_finalizacion = now();
            $proceso->sub_paqueta = Subproceso::where('proceso_id', $request->procesoId)->count();
            $maquina = Maquina::where('id',$proceso->maquina_id)->first();

            if ($maquina->corte == 'FINAL') {
                $orden->estado = 'TERMINADO';
                $orden->save();
            } else{
                $orden->estado = 'EN PRODUCION';
                $orden->save();
            }

        }else{
            $proceso->estado = 'EN PRODUCION';
            $orden->estado = 'EN PRODUCION';
            $orden->save();
        }
        try {
            $proceso->save();
        } catch (\Throwable $th) {
            return redirect()->back()->with('status','El proceso no pudo ser actualizado, contacte al administrador');
        }

    }
}
