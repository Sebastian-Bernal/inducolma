<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubprocesoReuquest extends FormRequest
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
            'entrada' => 'required',
            'salida' => 'required',
            'sobrante' => 'required',
            'lena' => 'required',
            'cm3Salida' => 'required',
            'cantidad_entrada' => 'required',
            'cantidadSalida' => 'required',
            'largo' => 'required',
            'alto' => 'required',
            'ancho' => 'required',

        ];
    }

    /**
     * Return the message
     */

    public function messages()
    {
        return [
            'entrada.required' =>'El item entrante es obligatorio',
            'salida.required' =>'El item saliente es obligatorio',
            'cm3Salida.required' => 'Los cm3 de salida son obligatorios',
            'cantidad_entrada.required' =>'la cantidad de entrada es obligatoria',
            'cantidadSalida.required' =>'la cantidad de salida es obligatoria',
            'sobrante.required' => 'el sobrante es obligatorio',
            'lena.required' => 'leña es obligatorio',
            'alto.required' => 'Alto es obligatorio',
            'largo.required' => 'Largo es obligatorio',
            'ancho.required' =>'Ancho es obligatorio',

        ];
    }
}
