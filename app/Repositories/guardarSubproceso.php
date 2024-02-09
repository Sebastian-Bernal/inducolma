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
                return redirect()->route('trabajo-maquina.index')->with('status','El proceso se guardo con éxito');
            }
            return redirect()->route('trabajo-maquina.show',$request->procesoId)->with('status','La subpaqueta se guardo con éxito');
        } catch (\Throwable $th) {
            return redirect()->back()->with('status','La subpaqueta no pudo ser guardada');
        }
    }

    public function actualizaProceso($request, $accion, $subproceso_existente = null)
    {
        $proceso = Proceso::find($request->procesoId);
        $item = Item::find($proceso->item_id);
        $orden = OrdenProduccion::where('id', $proceso->orden_produccion_id)->first();
        $cantidad_preprocesados = Proceso::where('orden_produccion_id', $orden->id)
                                            ->where('salida', 'ITEM FINAL')
                                            ->max('cantidad_items');

        $proceso->cm3_salida += (float)$request->cm3Salida;

        try {
            if ($accion == 1) {

                $this->updateProceso($proceso, 'EN PRODUCION', date('G:i:s'), now(), null, null, null, null, null);
                $this->updateOrden($orden, 'EN PRODUCCION');
            } else if ($accion == 3){

                $sub_paqueta = Subproceso::where('proceso_id', $request->procesoId)->count();
                $cantidadItems = Subproceso::where('proceso_id', $request->procesoId)->sum('cantidad_salida');
                $this->updateProceso($proceso, 'TERMINADO', null, null, date( 'G:i:s'), now(), $sub_paqueta, $request->cm3Salida, $cantidadItems);
                $this->terminarOrden($orden, $item, $cantidad_preprocesados);

            }else{
                $proceso->estado = 'EN PRODUCION';
                $this->updateProceso($proceso, 'EN PRODUCION', null, null, null, null, null, null, null);
                $this->updateOrden($orden, 'EN PRODUCCION');

            }
        } catch (Exception $e) {
            return redirect()->back()->with('status','El proceso no pudo ser actualizado, contacte al administrador'. $e->getMessage());
        }

    }


    public function updateProceso( $proceso, $estado = null, $hora_inicio = null, $fecha_ejecucion = null,$hora_fin = null,
                    $fecha_finalizacion = null, $subpaqueta = null, $cm3_salida, $cantidad_items = null)
    {
        $proceso->update([
            'estado' => $estado ?? $proceso->estado,
            'hora_inicio' => $hora_inicio ?? $proceso->hora_inicio,
            'fecha_ejecucion' => $fecha_ejecucion ?? $proceso->fecha_ejecucion,
            'hora_fin' => $hora_fin ?? $proceso->hora_fin,
            'fecha_finalizacion' => $fecha_finalizacion ?? $proceso->fecha_finalizacion,
            'sub_paqueta' => $subpaqueta ?? $proceso->sub_paqueta,
            'cm3_salida' => $cm3_salida ?? $proceso->cm3_salida,
            'cantidad_items' => $cantidad_items ?? $proceso->cantidad_items,
        ]);
    }

    public function updateOrden($orden , $estado)
    {
        $orden->update([
            'estado' => $estado,
        ]);
    }

    function terminarOrden($orden, $item, $cantidad_preprocesados)
    {
        $procesos = $orden->procesos;
        $procesosPendientesOProduccion = $procesos->filter( function($proceso, $key ){
                                                    return $proceso->estado == 'PENDIENTE' || $proceso->estado == 'EN PRODUCCION';
                                                })->count();

        if ($procesosPendientesOProduccion == 0 ) {
            $this->updateOrden($orden, 'TERMINADO');
            $this->analizeQuantityPedidoOrden($orden);
        } else{
            $this->updateOrden($orden, 'EN PRODUCCION');
        }
    }

    public function updateItem($item, $existencias, $preprocesado)
    {
        $item->update([
            'existencias' => $item->existencias + $existencias,
            'preprocesado' => $item->preprocesado + $preprocesado,
        ]);
    }

    public function analizeQuantityPedidoOrden($orden)
    {
        $pedido = $orden->pedido;

        $itemPedido = $pedido->items_pedido->where('id', $orden->item_id)->first();

        $cantidadItemPedido = $itemPedido->cantidad * $pedido->cantidad;

        $cantidadItemOrden = $orden->procesos->where('salida', 'ITEM FINAL')->first()->cantidad_items;

        $cantidadExistencias = $cantidadItemOrden - $cantidadItemPedido;

        if ($cantidadExistencias > 0 ){
            $this->updateItem($orden->item, $cantidadExistencias, $cantidadItemPedido);
        }else{
            throw new Exception('No hay existencias suficientes');
        }
    }
}
