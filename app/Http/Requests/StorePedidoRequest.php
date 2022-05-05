<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePedidoRequest extends FormRequest
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
            'descripcion' => 'required',
            'cantidad' => 'required||numeric|min:1',
            'fecha_entrega' => 'required|date|after:today',
            'cliente' => 'required',
        
        ];
        
    }

    public function messages(){
        return [
            'descripcion.required' => 'La descripcion es requerida',
            'cantidad.required' => 'La cantidad es requerida',
            'cantidad.numeric' => 'La cantidad debe ser un numero',
            'cantidad.min' => 'La cantidad debe ser mayor a 0',
            'fecha_entrega.required' => 'La fecha de entrega es requerida',
            'fecha_entrega.date' => 'La fecha de entrega debe ser una fecha',
            'fecha_entrega.after' => 'La fecha de entrega debe ser mayor a la fecha actual',
            'cliente.required' => 'El cliente es requerido',
        ];

    }
}
