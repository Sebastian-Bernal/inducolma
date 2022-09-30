<?php

namespace App\Repositories;

use App\Models\Cubicaje;
use App\Models\Proceso;
use App\Models\Transformacion;
use Illuminate\Support\Facades\Auth;

class DeleteOrden {

    public function deleteOrden($orden)
    {

        $delete_proceso = $this->eliminarProcesos($orden->id);
        $update_cubicaje = $this->actualizarCubicajes($orden->id);
        $delete_transformacion = $this->eliminarTransformacion($orden->id);
        if ($delete_proceso == true && $update_cubicaje == true && $delete_transformacion == true) {
            $orden->update(['user_id' => Auth::user()->id]);
            $orden->delete();
            return response()->json(array('error' => False, 'mensaje' => 'la orden se elimino con éxito'));
        } else {
            return response()->json(array(
                                'error' => True,
                                'mensaje' =>'No se pudo eliminar o actualizar los registros, contacte al administrador'
                            ));
        }
    }

    /**
     * eliminarProcesos(), elimina los procesos asociados a la orden
     * @param int $orden->id [identificador de la orden]
     * @return void
     */
    private function eliminarProcesos($orden_id) : bool
    {
        $procesos = Proceso::where('orden_produccion_id', $orden_id)->get();
        foreach ($procesos as $proceso) {
                $proceso->delete();
        }

        if(count(Proceso::where('orden_produccion_id', $orden_id)->get()) == 0 ){
            return True;
        } else {
            return False;
        }
    }

    /**
     * actualizarCubicajes(), actualiza el estado de los cubicajes a Disponible
     * @param int $orden_id [identifiador de la orden de produccion]
     * @return boolean
     */

    public function actualizarCubicajes($orden_id) : bool
    {
        $tansformaciones = Transformacion::where('orden_produccion_id', $orden_id)
                                        ->where('tipo_corte', 'INICIAL')
                                        ->get();

        $actualizados = 0;
        foreach ($tansformaciones as $tansformacion) {
            $actualiza = Cubicaje::where('id', $tansformacion->cubicaje_id)
                    ->update(['estado' => 'DISPONIBLE']);
            $actualizados += $actualiza;
        }

        if (count($tansformaciones) == $actualizados) {
            return True;
        } else {
            return False;
        }
    }

    /**
     * eliminarTransformacion(), elimina las transfomaciones asociadas a la orden de produccion
     * @param int $orden_id [numero de la orden]
     * @return boolean
     */

    public function eliminarTransformacion($orden_id) : bool
    {
        Transformacion::where('orden_produccion_id', $orden_id)
                        ->delete();
        if (count(Transformacion::where('orden_produccion_id', $orden_id)->get()) == 0){
            return true;
        } else {
            return false;
        }

    }




}
