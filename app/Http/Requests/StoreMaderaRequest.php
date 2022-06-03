<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaderaRequest extends FormRequest
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
            'tipo_madera_id' => 'required|integer',
            'nombre_cientifico' => 'required',
            'densidad' => 'required',          
        
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El tipo de madera es obligatorio',
            'nombre.integer' => 'El debe seleccionar un tipo de madera correcto',
            'nombre_cientifico.required' => 'El campo nombre cientifico es obligatorio',
            'densidad.required' => 'El campo densidad es obligatorio',
        ];
    }
}
