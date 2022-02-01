<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCostosOperacionRequest extends FormRequest
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
            'cantidad' =>[
                'numeric',
                'required',
            ],
            'valorMes' =>[
                'numeric',
                'required',
            ],
            'valorDia' =>[
                'numeric',
                'required',
            ],
            'costokwh' =>[
                'numeric',
                'required',
            ],
            'idMaquina' =>[
                'numeric',
                'required',
            ],
            'idDescripcion' =>[
                'numeric',
                'required',
            ],
        ];
    }

    public function messages()    
    {
        return [
            'cantidad.numeric' => 'El campo cantidad debe ser un número',
            'cantidad.required' => 'El campo cantidad es obligatorio',
            'valorMes.numeric' => 'El campo valor mes debe ser un número',
            'valorMes.required' => 'El campo valor mes es obligatorio',
            'valorDia.numeric' => 'El campo valor dia debe ser un número',
            'valorDia.required' => 'El campo valor dia es obligatorio',
            'costokwh.numeric' => 'El campo costo kwh debe ser un número',
            'costokwh.required' => 'El campo costo kwh es obligatorio',
            'idMaquina.numeric' => 'El campo maquina debe ser un número',
            'idMaquina.required' => 'El campo maquina es obligatorio',
            'idDescripcion.numeric' => 'El campo descripcion debe ser un número',
            'idDescripcion.required' => 'El campo descripcion es obligatorio',
        
        ];
    }
}
