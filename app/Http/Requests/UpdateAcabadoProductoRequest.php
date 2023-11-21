<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAcabadoProductoRequest extends FormRequest
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
            'pedido_id' => 'required|integer|exists:pedidos,id',
            'maquina_id' => 'required|integer|exists:maquinas,id',
            'cantidad' => 'required|integer|min:1',
            'user_id' => 'required|integer|exists:users,id',
        ];
    }
}
