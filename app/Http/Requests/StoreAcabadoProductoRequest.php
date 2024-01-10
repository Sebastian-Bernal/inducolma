<?php

namespace App\Http\Requests;

use Illuminate\Http\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreAcabadoProductoRequest extends FormRequest
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
            'user_id' => 'required|integer|exists:users,id',
            'rutas' => 'required|array',
            'rutas.*.cantidad' => 'required|integer|min:1',
            'rutas.*.maquina_id' => 'required|integer|exists:maquinas,id',
        ];
    }


    /**
    * Response failed validation in JSON response
    */
    public function failedValidation(Validator $validator) {
        throw new HttpResponseException(
            new Response([
                'success' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }

    /**
     * Get messages from validations
     */
    public function messages()
    {
        return [
            'pedido_id.required' => 'El pedido es obligatorio',
            'pedido_id.integer' => 'El pedido debe ser un número entero',
            'pedido_id.exists' => 'El pedido no existe',

            'users_id.required' => 'El usuario es obligatorio',
            'users_id.integer' => 'El usuario debe ser un número entero',
            'users_id.exists' => 'El usuario no existe',

            'rutas.required' => 'Las rutas son obligatorias',
            'rutas.array' => 'Las rutas deben ser un array',

            'rutas.*.cantidad.required' => 'La cantidad es obligatoria',
            'rutas.*.cantidad.integer' => 'La cantidad debe ser un número entero',
            'rutas.*.cantidad.min' => 'La cantidad debe ser mayor o igual a 1',

            'rutas.*.maquina_id' => 'La máquina es obligatoria',
            'rutas.*.maquina_id.integer' => 'La máquina debe ser un número entero',
            'rutas.*.maquina_id.exists' => 'La máquina no existe',
        ];
    }
}
