<?php

namespace App\Repositories;

use App\Models\Item;
use App\Models\Maquina;
use App\Models\OrdenProduccion;
use App\Models\Proceso;
use App\Models\Subproceso;
use Exception;
use Illuminate\Support\Facades\Auth;

class guardarSubproceso{
    /**
     * This is a method called "guardar" inside the class "guardarSubproceso".
     * It takes two parameters: $subproceso_existente and $request.
     *
     * The method checks the value of $subproceso_existente and performs different actions based on its value.
     * If $subproceso_existente is null, it checks the value of $request->terminar.
     * If $request->terminar is 3, it calls the method "guardaSubproceso" with the parameters $subproceso_existente, $request, and 3.
     * If $request->terminar is not 3, it calls the method "guardaSubproceso" with the parameters $subproceso_existente, $request, and 1.
     *
     * If $subproceso_existente is not null, it checks the value of (integer)$request->terminar.
     * If (integer)$request->terminar is 3, it calls the method "guardaSubproceso" with the parameters $subproceso_existente, $request, and 3.
     * If (integer)$request->terminar is not 3, it calls the method "guardaSubproceso" with the parameters $subproceso_existente, $request, and 2.
     *
     * The method returns the result of the called method "guardaSubproceso".
     */
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
    /**
     * This method is responsible for saving a subprocess.
     * It creates a new instance of the Subproceso class and sets its properties based on the provided request data.
     * The "accion" parameter determines the action to be taken when saving the subproceso.
     * If "accion" is 1, the sub_paqueta property of the subproceso is set to 1.
     * If "accion" is 3 and "subproceso_existente" is null, the sub_paqueta property is also set to 1.
     * Otherwise, the sub_paqueta property is set to the sub_paqueta property of the "subproceso_existente" plus 1.
     * After saving the subproceso, the method calls the "actualizaProceso" method based on the "accion" value.
     * If "accion" is 1, the "actualizaProceso" method is called with a parameter of 1.
     * If "accion" is 2, the "actualizaProceso" method is called with a parameter of 2.
     * Otherwise, if "subproceso_existente" is null, the "actualizaProceso" method is called with a parameter of 3 and the "subproceso_existente" set to the newly saved subproceso.
     * Finally, the method returns a redirect response based on the "accion" value.
     * If "accion" is 1, it redirects to the "trabajo-maquina.show" route with a success message.
     * If "accion" is 2, it redirects to the "trabajo-maquina.show" route with a success message.
     * Otherwise, it redirects to the "trabajo-maquina.index" route with a success message.
     * If an exception occurs during the process, the method catches it and returns a redirect response with an error message.
     */
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
                return redirect()->route('trabajo-maquina.index')->with('status','El proceso se guardo con éxito');
            }
            return redirect()->route('trabajo-maquina.show',$request->procesoId)->with('status','La subpaqueta se guardo con éxito');
        } catch (\Throwable $th) {
            return redirect()->back()->with('status','La subpaqueta no pudo ser guardada');
        }
    }
    /**
     * This method updates the process and order based on the given action.
     *
     * @param $request The request object containing the necessary data.
     * @param $accion The action to be performed.
     * @param $subproceso_existente The existing sub-process, if any.
     * @return Redirect The redirect response.
     */
    public function actualizaProceso($request, $accion, $subproceso_existente = null)
    {
        $proceso = Proceso::findOrFail($request->procesoId);
        $orden = OrdenProduccion::findOrFail($proceso->orden_produccion_id);
        try {
            if ($accion == 1) {
                $this->updateProceso($proceso, 'EN PRODUCCION', date('G:i:s'), now(), null, null, null, null, null);
                $this->updateOrden($orden, 'EN PRODUCCION');
            } elseif ($accion == 3) {
                $sub_paqueta = Subproceso::where('proceso_id', $request->procesoId)->count();
                $cantidadItems = Subproceso::where('proceso_id', $request->procesoId)->sum('cantidad_salida');
                $this->updateProceso($proceso, 'TERMINADO', null, null, date('G:i:s'), now(), $sub_paqueta, $request->cm3Salida, $cantidadItems);
                $this->terminarOrden($orden);
            } else {
                $proceso->estado = 'EN PRODUCCION';
                $this->updateProceso($proceso, 'EN PRODUCCION', null, null, null, null, null, null, null);
                $this->updateOrden($orden, 'EN PRODUCCION');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('status', 'El proceso no pudo ser actualizado, contacte al administrador: ' . $e->getMessage());
        }
    }
    /**
     * Updates the estado, hora_inicio, fecha_ejecucion, hora_fin, fecha_finalizacion, sub_paqueta, cm3_salida, and cantidad_items properties of a Proceso object.
     *
     * @param Proceso $proceso The Proceso object to update.
     * @param string|null $estado The new value for the estado property. If null, the estado property will not be updated.
     * @param string|null $hora_inicio The new value for the hora_inicio property. If null, the hora_inicio property will not be updated.
     * @param string|null $fecha_ejecucion The new value for the fecha_ejecucion property. If null, the fecha_ejecucion property will not be updated.
     * @param string|null $hora_fin The new value for the hora_fin property. If null, the hora_fin property will not be updated.
     * @param string|null $fecha_finalizacion The new value for the fecha_finalizacion property. If null, the fecha_finalizacion property will not be updated.
     * @param int|null $subpaqueta The new value for the sub_paqueta property. If null, the sub_paqueta property will not be updated.
     * @param float $cm3_salida The value to add to the cm3_salida property. If null, the cm3_salida property will not be updated.
     * @param int|null $cantidad_items The new value for the cantidad_items property. If null, the cantidad_items property will not be updated.
     * @throws \Exception If there is an error updating the proceso.
     */
    public function updateProceso($proceso, $estado = null, $hora_inicio = null, $fecha_ejecucion = null, $hora_fin = null, $fecha_finalizacion = null, $subpaqueta = null, $cm3_salida, $cantidad_items = null)
    {
        try {
            $proceso->update([
                'estado' => $estado ?? $proceso->estado,
                'hora_inicio' => $hora_inicio ?? $proceso->hora_inicio,
                'fecha_ejecucion' => $fecha_ejecucion ?? $proceso->fecha_ejecucion,
                'hora_fin' => $hora_fin ?? $proceso->hora_fin,
                'fecha_finalizacion' => $fecha_finalizacion ?? $proceso->fecha_finalizacion,
                'sub_paqueta' => $subpaqueta ?? $proceso->sub_paqueta,
                'cm3_salida' => $cm3_salida === null ? $proceso->cm3_salida : $proceso->cm3_salida += (float)$cm3_salida,
                'cantidad_items' => $cantidad_items ?? $proceso->cantidad_items,
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Error al actualizar el proceso: ' . $e->getMessage());
        }
    }
    /**
     * Updates the estado property of an Orden object.
     *
     * @param Orden $orden The Orden object to update.
     * @param string $estado The new value for the estado property.
     * @throws \Exception If there is an error updating the orden.
     */
    public function updateOrden($orden, $estado)
    {
        try {
            $orden->update([
                'estado' => $estado,
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Error al actualizar la orden: ' . $e->getMessage());
        }
    }
    /**
     * This method is responsible for terminating an order.
     * It checks if there are any pending or in production processes for the order.
     * If there are no pending or in production processes, it updates the order status to 'TERMINADO'
     * and calls the 'analizeQuantityPedidoOrden' method to analyze the quantity of the order.
     * If there are pending or in production processes, it updates the order status to 'EN PRODUCCION'.
     *
     * @param $orden The order to be terminated.
     * @throws \Exception If there is an error while terminating the order.
     */
    public function terminarOrden($orden)
    {
        try {
            $procesosPendientesOProduccion = $orden->procesos->filter(function ($proceso, $key) {
                return $proceso->estado == 'PENDIENTE' || $proceso->estado == 'EN PRODUCCION';
            })->count();

            if ($procesosPendientesOProduccion == 0) {
                $this->updateOrden($orden, 'TERMINADO');
                $this->analizeQuantityPedidoOrden($orden);
            } else {
                $this->updateOrden($orden, 'EN PRODUCCION');
            }
        } catch (\Exception $e) {
            throw new \Exception('Error al terminar la orden: ' . $e->getMessage());
        }
    }
    /**
     * Updates the 'existencias' and 'preprocesado' properties of an item.
     *
     * @param Item $item The item object to update.
     * @param int $existencias The value to add to the 'existencias' property.
     * @param int $preprocesado The value to add to the 'preprocesado' property.
     * @throws \Exception If there is an error updating the item.
     *//**
    * Updates the 'existencias' and 'preprocesado' properties of an item.
    *
    * @param Item $item The item object to update.
    * @param int $existencias The value to add to the 'existencias' property.
    * @param int $preprocesado The value to add to the 'preprocesado' property.
    * @throws \Exception If there is an error updating the item.
    */
    public function updateItem($item, $existencias, $preprocesado)
    {
        try {
            $item->update([
                'existencias' => $item->existencias + $existencias,
                'preprocesado' => $item->preprocesado + $preprocesado,
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Error al actualizar el item: ' . $e->getMessage());
        }
    }

    /**
     * This method analyzes the quantity of a pedido and an orden.
     * It calculates the quantity of an item in the pedido and the orden,
     * and checks if there are enough existencias (stock) for the item.
     * If there are enough existencias, it updates the item's existencias and preprocesado.
     * If there are not enough existencias, it throws an exception.
     *
     * @param $orden The orden object to analyze.
     * @throws \Exception If there are not enough existencias for the item.
     */
    public function analizeQuantityPedidoOrden($orden)
    {
        try {
            $pedido = $orden->pedido;
            $itemPedido = $pedido->items_pedido->where('id', $orden->item_id)->first();
            $cantidadItemPedido = $itemPedido->cantidad * $pedido->cantidad;
            $cantidadItemOrden = $orden->procesos->where('salida', 'ITEM FINAL')->first()->cantidad_items;
            $cantidadExistencias = $cantidadItemOrden - $cantidadItemPedido;

            if ($cantidadExistencias > 0) {
                $this->updateItem($orden->item, $cantidadExistencias, $cantidadItemPedido);
            } else {
                throw new \Exception('No hay existencias suficientes');
            }
        } catch (\Exception $e) {
            throw new \Exception('Error al analizar la cantidad del pedido y la orden: ' . $e->getMessage());
        }
    }

}
