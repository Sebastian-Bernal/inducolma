<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTraajoMaquinaRequest extends FormRequest
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
            'procesoId' => 'required',
            'maquinaId' => 'required',
            'paqueta' => 'required',
            //'terminar' => 'required',
            'itemEntrante' => 'required',
            'itemSaliente' => 'required',
            'cm3Entrada' => 'required',
            'cantidadEntrada' => 'required',
            'cantidadSalida' => 'required',
            'alto' => 'required',
            'ancho' => 'required',
            'largo' => 'required'
        ];
    }


    /**
     * Get messages from validations
     */
    public function messages()
    {
        return [
            'procesoId' => 'El campo proceso es obligatorio',
            'maquinaId' => 'El campo maquina es obligatorio',
            'paqueta' => 'El campo paqueta es obligatorio',
            //'terminar' => 'El campo terminar es obligatorio',
            'itemEntrante' => 'El campo itemEntrante es obligatorio',
            'itemSaliente' => 'El campo itemSaliente es obligatorio',
            'cm3Entrada' => 'El campo cm3Entrada es obligatorio',
            'cantidadEntrada' => 'El campo cantidadEntrada es obligatorio',
            'cantidadSalida' => 'El campo cantidadSalida es obligatorio',
            'alto' => 'El campo alto es obligatorio',
            'ancho' => 'El campo ancho es obligatorio',
            'largo' => 'El campo largo es obligatorio'

        ];
    }
}
