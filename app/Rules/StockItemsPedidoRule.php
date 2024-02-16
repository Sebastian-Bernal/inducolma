<?php

namespace App\Rules;

use App\Models\Pedido;
use Illuminate\Contracts\Validation\Rule;

class StockItemsPedidoRule implements Rule
{

    private $cantidad, $item;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($cantidad)
    {
        $this->cantidad = $cantidad;

    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $pedido = Pedido::find($value);

        $cantidad = $this->cantidad;
        $itemNoStock = $pedido->diseno_producto_final->items->first(function ($item) use ($cantidad) {
            return $item->cantidad * $cantidad > $item->preprocesado;
        });

        if ($itemNoStock) {
            $this->setItem($itemNoStock);
            return false;
        }

        return true;



        /* return $pedido->diseno_producto_final->items->every(function ($item) use($cantidad) {
            return $item->cantidad * $cantidad <= $item->preprocesado;
        }); */

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $itemNoStock = $this->getItem()->descripcion;
        return "Error de inventario, El item $itemNoStock, no tiene cantidades suficientes para crear la cantidad de productos ingresados, revise la cantidad ingresada o contacte con el administrador de planta.";
    }

    /**
     * Get the value of item
     */
    private function getItem()
    {
        return $this->item;
    }

    /**
     * Set the value of item
     *
     * @return  self
     */
    private function setItem($item)
    {
        $this->item = $item;

        return $this;
    }
}
