<?php

namespace App\Repositories;

use Exception;
use App\Models\Item;
use App\Models\Pedido;
use App\Models\Maquina;
use App\Models\TurnoUsuario;
use App\Models\InsumosAlmacen;
use App\Models\EnsambleAcabado;
use App\Models\ProductoMaquina;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductosTerminados {
    public function guardar($request)
    {
        $pedido = Pedido::find($request->pedido);
        return $this->agregarProducto($request, $pedido);
    }

    /**
     * Consulta si existe productos producidos para el pedido, si no existe crea uno
     * si existe agrega uno a la cantidad_producida
     */

    public function agregarProducto($request, $pedido)
    {
        try {
            DB::beginTransaction();

            $syncProductosPedido = $this->syncPedidoProducto($pedido, $request);

            if(!$syncProductosPedido){
                throw new Exception('No se pudo sincronizar el pedido');
            }

            $saveProductoMaquina = $this->registrarProductoMaquina(Auth::id(),$pedido->diseno_producto_final_id, $request->cantidad);

            if(!$saveProductoMaquina){
                throw new Exception('No se pudo registrar el producto maquina');
            }

            $actualizarPreprocesadosItems = $this->actualizaExistenciasItems($pedido, $request->cantidad);

            if(!$actualizarPreprocesadosItems){
                throw new Exception('No se pudo actualizar las existencias de los items');
            }

            $actualizarInsumos =  $this->updateInsumosAlmacen($pedido, $request->cantidad);

            if(!$actualizarInsumos){
                throw new Exception('No se pudo actualizar los insumos del almacen');
            }

            $terminarEnsamble = $this->analizaEnsamble($pedido, $request->ensambeAcabadoId, $request->maquinaId);
            if(!$terminarEnsamble){
                throw new Exception('No se pudo terminar el ensamble, la cantidad ensamblada es menor a la requerida en el pedido');
            }

            DB::commit();

            return $pedido;

        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Error : ".$e->getMessage());

        }

    }

    /**
     * Actualiza las existencias de los items del diseno
     */

    public function actualizaExistenciasItems($pedido, $cantidad)
    {
        $items = $pedido->diseno_producto_final->items;

        foreach ($items as $item) {
            $success = $item->decrement('preprocesado', $item->cantidad * $cantidad);
            if (!$success) {
                return false;
            }
        }

        return true;
    }

    /**
     * actualiza la cantidad de insumos del almacen cuando un producto ha sido creado
     * @param Pedido $pedido
     */
    public function updateInsumosAlmacen($pedido, $cantidad)
    {
        $insumos = $pedido->diseno_producto_final->insumos_almacen;

        foreach ($insumos as $insumo) {
            $success = $insumo->decrement('cantidad', $insumo->cantidad_diseno * $cantidad);
            if (!$success) {
                return false;
            }
        }

        return true;
    }

    /**
     * Actualiza el estado del pedido
     *
     * @param Pedidos $pedido
     */

    public function actualizaEstadoPedido($pedido, $estado)
    {
        $pedido->update([
            'estado' => $estado
        ]);
    }

    public function analizaEnsamble($pedido, $ensambleId, $maquinaId)  {

        $ensamble = EnsambleAcabado::find($ensambleId);

        $pedido = Pedido::find($pedido->id);

        $tipoCorteMaquina = Maquina::find($maquinaId)->corte;

        $cantidadEnsambles = $pedido->ensambles_acabados->filter(function ($ensamble_acabado) use($tipoCorteMaquina){
            return $ensamble_acabado->estado == 'TERMINADO' && $ensamble_acabado->maquina->corte == $tipoCorteMaquina;
        })->sum('cantidad') + $ensamble->cantidad;

        $cantidadProducida = $pedido->pedido_producto->first()->cantidad_producida;

        if ($cantidadEnsambles == $cantidadProducida) {
            return $this->terminarEnsamble($pedido, $ensambleId);
        }else {
            $this->actualizarEnsamble($ensamble, 'EN ENSAMBLE');
        }

        return true;

    }

    public function terminarPedido($pedido)  {

        $producido = $pedido->pedido_producto->first();
        if ($producido == null) {
            return false;
        }

        if ($producido->cantidad_producida < $pedido->cantidad ) {
            return false;
        }

        $this->actualizaEstadoPedido($pedido, 'TERMINADO');

        return true;
    }

    public function terminarEnsamble($pedido, $ensambleId)  {

        $ensamble = EnsambleAcabado::find($ensambleId);
        $pedido = Pedido::find($pedido->id);

        $atualizaEstadoAcabado = $this->actualizarEnsamble($ensamble, 'TERMINADO');
        if(!$atualizaEstadoAcabado){
            return false;
        }

        $pedido_ensambles_acabados_pendientes = $pedido->ensambles_acabados->filter(function ($ensamble_acabado){
            return $ensamble_acabado->estado == 'PENDIENTE' || $ensamble_acabado->estado == 'EN ENSAMBLE';
        })->count();

        if($pedido_ensambles_acabados_pendientes == 0){
            return $this->terminarPedido($pedido);
        }

        return true;
    }

    public function actualizarEnsamble($ensamble, $estado)  {
        $ensamble->update([
            'fecha_inicio' => $ensamble->fecha_inicio ?? now(),
            'fecha_fin' => now(),
            'estado' => $estado
        ]);
        return true;
    }

    public function registrarProductoMaquina($usuario, $diseno, $cantidad) :?bool
    {
        $turno = TurnoUsuario::where('user_id', $usuario)
                            ->whereDate('fecha', now())
                            ->first();

        $registros = ProductoMaquina::where('user_id', $turno->user_id)
                                ->where('maquina_id', $turno->maquina_id)
                                ->whereDate('created_at', $turno->fecha)
                                ->first();

        if (!$registros) {
            $productoMaquina = new ProductoMaquina();
            $productoMaquina->user_id = $turno->user_id;
            $productoMaquina->maquina_id = $turno->maquina_id;
            $productoMaquina->cantidad = $cantidad;
            $productoMaquina->diseno_producto_final_id = $diseno;
            $productoMaquina->save();
        } else {
            $registros->update([
                'cantidad' => $registros->cantidad + $cantidad,
                'updated_at' => now(),
                'diseno_producto_final_id' => $diseno,
            ]);
        }

        return true;
    }


    public function syncPedidoProducto($pedido, $request) :?bool  {

        $pedido_producto = $pedido->pedido_producto->first();

        if($pedido_producto == null){
            $pedido->pedido_producto()->sync([
                $request->diseno => [
                    'cantidad_producida' => $request->cantidad,
                    'user_id' => Auth::id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);

            return true;
        }

        $nuevaCantidad = $pedido_producto->cantidad_producida + $request->cantidad;

        $pedido->pedido_producto()->updateExistingPivot($request->diseno, [
            'cantidad_producida' => $nuevaCantidad,
            'updated_at' => now(),
            'user_id' => Auth::id(),
        ]);

        return true;

    }
}
