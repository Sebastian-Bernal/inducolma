<?php

namespace App\Http\Requests;

use App\Rules\StockItemsPedidoRule;
use Illuminate\Foundation\Http\FormRequest;

class StreProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cantidad' => 'required|numeric|min:1',
            'pedido' => [
                'required',
                new StockItemsPedidoRule($this->cantidad)
            ]
        ];
    }


    /**
     * Get messages from validations
     */
    public function messages()
    {
        return [
            'cantidad.required' => 'La cantidad es requerida',
            'cantidad.numeric' => 'La cantidad debe ser un número',
            'cantidad.min' => 'La cantidad debe ser mayor a 0',
            'pedido.required' => 'El pedido es requerido',
        ];
    }
}
