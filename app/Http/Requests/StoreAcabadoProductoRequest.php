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
}
