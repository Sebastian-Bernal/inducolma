<?php

namespace App\Repositories;

use App\Models\Item;
use App\Models\Pedido;
use App\Models\TurnoUsuario;
use App\Models\InsumosAlmacen;
use App\Models\ProductoMaquina;
use Illuminate\Support\Facades\Auth;

class ProductosTerminados {
    public function guardar($request)
    {
        $pedido = Pedido::find($request->pedido);
        $i = 0;
        foreach ($pedido->diseno_producto_final->items as $item) {
            if ($item->existencias < $pedido->items_pedido[$i]->cantidad ) {
                return redirect()->route('trabajo-ensamble',$pedido)->with('status',
                    "<p class='text-danger'>
                            El item: $item->descripcion, no tiene existencias suficientes, no puede ensamblar el producto para el pedido No. $pedido->id
                        <i class='fa-solid fa-triangle-exclamation'></i>
                    </p>");
                break;
            }
            $i++;
        }

        return $this->agregarProducto($request, $pedido);
    }

    /**
     * Consulta si existe productos producidos para el pedido, si no existe crea uno
     * si existe agrega uno a la cantidad_producida
     */

    public function agregarProducto($request, $pedido)
    {
        $producidos = $pedido->pedido_producto->all();

        if (count($producidos) <= 0) {
            $cantidad = 1;
            $created_at = now();
        } else {
            $cantidad = $pedido->pedido_producto->first()->cantidad_producida + 1;
            $created_at = $pedido->pedido_producto->first()->created_at;
        }

        try {
            $this->registrarProductoMaquina(Auth::user()->id,$pedido->diseno_producto_final_id);
        } catch (\Throwable $th) {
            return redirect()->route('trabajo-ensamble', $pedido)->with('status',
                "<p class='text-danger'>
                    El producto no pudo ser agregado...
                    <i class='fa-solid fa-triangle-exclamation'></i>
                </p>");
        }

        try {
            $this->actualizaExistenciasItems($pedido);
            $this->updateInsumosAlmacen($pedido);
            $pedido->pedido_producto()->sync([
                    $request->diseno => [
                        'cantidad_producida' => $cantidad,
                        'user_id' => Auth::user()->id,
                        'created_at' => $created_at,
                        'updated_at' => now(),
                    ]
                ]);
            if($request->terminar == 2){
                $this->actualizaPedido($pedido);
                return redirect()->route('trabajo-maquina.create')->with('status',
                    "El pedido se termino con éxito");
            }
            return redirect()->route('trabajo-ensamble', $pedido)->with('status',
                    "El producto se agrego con éxito");
        } catch (\Throwable $th) {
            return redirect()->route('trabajo-ensamble', $pedido)->with('status',
                "<p class='text-danger'>
                    El producto no pudo ser agregado...
                    <i class='fa-solid fa-triangle-exclamation'></i>
                </p>");
        }
    }

    /**
     * Actualiza las existencias de los items del diseno
     */

    public function actualizaExistenciasItems($pedido)
    {
        foreach ($pedido->diseno_producto_final->items as $item) {
            $item_guardar = Item::find($item->id);
            $item_guardar->existencias -= $item->cantidad;
            $item_guardar->save();
        }
    }

    /**
     * actualiza la cantidad de insumos del almacen cuando un producto ha sido creado
     * @param Pedido $pedido
     */
    public function updateInsumosAlmacen($pedido)
    {
        foreach ($pedido->diseno_producto_final->insumos as $insumo) {
            $insumo_guardar = InsumosAlmacen::find($insumo->id);
            $insumo_guardar->cantidad -= $insumo->cantidad;
            $insumo_guardar->save();
        }
    }

    /**
     * Actualiza el estado del pedido
     *
     * @param Pedidos $pedido
     */

    public function actualizaPedido($pedido)
    {
        $actualiza = Pedido::find($pedido->id);
        $producido = $pedido->pedido_producto->first();
        if ($producido == null) {
            return redirect()->route('trabajo-ensamble', $pedido)->with('status',
                    "<p class='text-danger'>
                            NO PUEDE TERMINAR EL PEDIDO, NO HAY UNIDADES PRODUCIDAS.
                        <i class='fa-solid fa-triangle-exclamation'></i>
                    </p>"
                    );
        }
        if ($producido < $pedido->cantidad ) {
            return redirect()->route('trabajo-ensamble', $pedido)->with('status',
                    "<p class='text-danger'>
                            La cantidad producida e menor a la cantidad del pedido, NO puede terminar el pedido
                        <i class='fa-solid fa-triangle-exclamation'></i>
                    </p>"
                    );
        }
        $actualiza->estado = 'TERMINADO';

        try {
            $actualiza->save();
        } catch (\Throwable $th) {
            return redirect()->route('trabajo-ensamble', $pedido)->with('status',
            "<p class='text-danger'>
                Error al actualizar el pedido
                <i class='fa-solid fa-triangle-exclamation'></i>
            </p>");
        }

    }

    /**
     *
     */

    public function registrarProductoMaquina($usuario, $diseno)
    {
        $turno = TurnoUsuario::where('user_id', $usuario)
                            ->where('fecha', now())
                            ->first();

        $registros = ProductoMaquina::where('user_id', $turno->user_id)
                                ->where('maquina_id', $turno->maquina_id)
                                ->where('created_at','like', '%'.$turno->fecha.'%')
                                ->first();
        if ($registros == '') {
            $productoMaquina = new ProductoMaquina();
            $productoMaquina->user_id = $turno->user_id;
            $productoMaquina->maquina_id = $turno->maquina_id;
            $productoMaquina->cantidad = 1;
            $productoMaquina->diseno_producto_final_id = $diseno;
            $productoMaquina->save();
        }else{
            $productoMaquina = ProductoMaquina::where('id', $registros->id)->first();
            $productoMaquina->user_id = $turno->user_id;
            $productoMaquina->maquina_id = $turno->maquina_id;
            $productoMaquina->cantidad = $registros->cantidad + 1;
            $productoMaquina->updated_at = now();
            $productoMaquina->diseno_producto_final_id = $diseno;
            $productoMaquina->update();
        }

    }
}
