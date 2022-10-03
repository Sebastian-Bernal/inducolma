<?php


namespace App\Repositories;

use App\Models\Cubicaje;
use App\Models\Proceso;
use App\Models\Transformacion;

class RegistroProcesos
{

    public function registrar_ruta($procesos)
    {
        $procesos = json_decode(json_encode($procesos));
        $errores = array();
        foreach ($procesos as $proceso) {
            $registrar = new Proceso();
            $registrar->observacion = $proceso->observa;
            $registrar->entrada = strtoupper($proceso->entra);
            $registrar->salida = strtoupper($proceso->sale);
            $registrar->cm3_entrada = $this->seleccionCm3TipoCorte($proceso);
            $registrar->cantidad_items = $this->seleccionCantidadItemsTipoCorte($proceso);
            $registrar->estado = 'PENDIENTE';
            $registrar->item_id = $proceso->item_id;
            $registrar->orden_produccion_id = $proceso->orden_id;
            $registrar->maquina_id = $proceso->idMaquina;
            $registrar->cubicaje_id = $proceso->cubicaje_id;
            $registrar->updated_at = null;

            if ($registrar->save()) {
                continue;
            } else {
                array_push($errores, "no se pudo registrar un proceso de la orden $registrar->orden_id");
            }
        }

        if (empty($errores)) {
            return response()->json(['error' => false, 'mensaje' => "procesos guardados con éxito"]);
        } else {
            return response()->json(['error' => true, 'mensaje' => $errores]);
        }
    }

    /**
     * cm3Paqueta() retorna los cm3 de la paqueta
     * @param integer $cubicaje_id [id del cubicaje, con el se obtine el numero de paqueta]
     * @return float
     */

    function cm3Paqueta($cubicaje_id): float
    {
        $cubicaje = Cubicaje::where('id', $cubicaje_id)->get();
        $result = Cubicaje::where('entrada_madera_id', $cubicaje[0]->entrada_madera_id)
            ->where('paqueta', $cubicaje[0]->paqueta)
            ->get();
        $cm3 = $result->sum('cm3');
        return $cm3;
    }

    /**
     * cm3Transformacion(), retorna los cm3 de la transformaciones dependiendo del tipo de corte
     * @param integer $orden_id [el id de la orden de produccion]
     * @param string $tipo_corte [tipo de conte del proceso]
     * @return float
     *
     */

    public function cm3Transformacion($orden_id, $tipo_corte): float
    {
        $result = Transformacion::where('orden_produccion_id', $orden_id)
            ->where('tipo_corte', $tipo_corte)
            ->get();
        $cm3 = $result->sum(function ($row) {
            return $row->alto * $row->largo * $row->ancho * $row->cantidad;
        });
        return $cm3;
    }

    /**
     * cantidadBloquesPaqueta(), retorna la cantidad de bloques de la paqueta
     * @param integer $cubicaje_id [id del cubicaje, asociado a una entrada de madera y a una paqueta]
     * @return integer
     */

    function cantidadBloquesPaqueta($cubicaje_id): int
    {
        $cubicaje = Cubicaje::where('id', $cubicaje_id)->get();
        $result = Cubicaje::where('entrada_madera_id', $cubicaje[0]->entrada_madera_id)
            ->where('paqueta', $cubicaje[0]->paqueta)
            ->get();
        $cantidad = $result->count();
        return $cantidad;
    }

    /**
     * cantidadItemsTransformacion(), retorna la cantidad de items por transformacion
     * @param integer $orden_id [el id de la orden de produccion]
     * @param string $tipo_corte [tipo de conte del proceso]
     * @return integer
     */

    public function cantidadItemsTransformacion($orden_id, $tipo_corte): int
    {
        $result = Transformacion::where('orden_produccion_id', $orden_id)
            ->where('tipo_corte', $tipo_corte)
            ->get();
        $cantidad = $result->sum('cantidad');
        return $cantidad;
    }

    /**
     * seleccionCm3TipoCorte(), hace la seleccion de la funcion a usar y la consulta a ejecutar
     * para obtener el resultado de cm3 del proceso
     * @param Object $proceso [objeto proceso]
     */

    public function seleccionCm3TipoCorte($proceso): float
    {
        switch ($proceso->tipo_corte) {
            case 'INICIAL':
                return $this->cm3Paqueta($proceso->cubicaje_id);
                break;
            case 'INTERMEDIO':
                return $this->cm3Transformacion($proceso->orden_id, 'INICIAL');
                break;
            case 'FINAL':
                return $this->cm3Transformacion($proceso->orden_id, 'INTERMEDIO');
                break;
            case 'ACABADOS':
                return $this->cm3Transformacion($proceso->orden_id, 'FINAL');
        };
    }

    /**
     * seleccionCantidadItemsTipoCorte(), hace la seleccion de la funcion a usar y la consulta a ejecutar
     * para obtener el resultado de cm3 del proceso
     * @param Object $proceso [objeto proceso]
     */

    public function seleccionCantidadItemsTipoCorte($proceso): int
    {
        switch ($proceso->tipo_corte) {
            case 'INICIAL':
                return $this->cantidadBloquesPaqueta($proceso->cubicaje_id);
                break;
            case 'INTERMEDIO':
                return $this->cantidadItemsTransformacion($proceso->orden_id, 'INICIAL');
                break;
            case 'FINAL':
                return $this->cantidadItemsTransformacion($proceso->orden_id, 'INTERMEDIO');
                break;
            default:
                return $this->cantidadItemsTransformacion($proceso->orden_id, 'FINAL');
        };
    }
}
