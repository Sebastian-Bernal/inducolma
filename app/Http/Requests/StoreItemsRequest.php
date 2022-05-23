<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemsRequest extends FormRequest
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
            'descripcion' => 'required|max:255',
            'alto' => 'required|numeric|min:0',
            'ancho' => 'required|numeric|min:0',
            'largo' => 'required|numeric|min:0',
           // 'existencias' => 'required|numeric|min:0',
            'tipo_madera' => 'required|max:255',
            'codigo_cg' => 'required|max:255',
            //'preprocesado' => 'boolean',
            //'carretos' => 'required|numeric|min:0',
        ];
    }

    public function messages(){
        return [
            'descripcion.required' => 'La descripción es requerida',
            'descripcion.max' => 'La descripción no puede tener más de 255 caracteres',
            'alto.required' => 'El alto es requerido',
            'alto.numeric' => 'El alto debe ser un número',
            'alto.min' => 'El alto debe ser mayor a 0',
            'ancho.required' => 'El ancho es requerido',
            'ancho.numeric' => 'El ancho debe ser un número',
            'ancho.min' => 'El ancho debe ser mayor a 0',
            'largo.required' => 'El largo es requerido',
            'largo.numeric' => 'El largo debe ser un número',
            'largo.min' => 'El largo debe ser mayor a 0',
            //'existencias.required' => 'Las existencias son requeridas',
           // 'existencias.numeric' => 'Las existencias deben ser un número',
            'existencias.min' => 'Las existencias deben ser mayor a 0',
            'tipo_madera.required' => 'El tipo de madera es requerido',
            'tipo_madera.max' => 'El tipo de madera no puede tener más de 255 caracteres',
            'codigo_cg.required' => 'El código CG es requerido',
            'codigo_cg.max' => 'El código CG no puede tener más de 255 caracteres',
           // 'preprocesado.boolean' => 'El campo preprocesado debe ser un SI o NO',
            //'carretos.required' => 'El número de carretos es requerido',
            //'carretos.numeric' => 'El número de carretos debe ser un número',
           // 'carretos.min' => 'El número de carretos debe ser mayor a 0',
            
        ];
    }
}
