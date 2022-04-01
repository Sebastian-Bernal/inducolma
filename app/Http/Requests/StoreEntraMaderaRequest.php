<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEntraMaderaRequest extends FormRequest
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
            'mes' => 'required',
            'ano' => 'required',
            'hora' => 'required',
            'fecha' => 'required',
            'actoAdministrativo' => 'required|unique:entrada_maderas',
            'salvoconducto' => 'required|numeric',
            'titularSalvoconducto' => 'required',
            'procedencia' => 'required',
            'entidadVigilante' => 'required',            
            'condicionMadera' => 'required',
            'm3entrada' => 'required|numeric',
            'proveedor' => 'required',
            'madera' => 'required',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     * @return array
     */
    public function messages()
    {
        return [
            'mes.required' => 'El campo mes es obligatorio',
            'ano.required' => 'El campo año es obligatorio',
            'hora.required' => 'El campo hora es obligatorio',
            'fecha.required' => 'El campo fecha es obligatorio',
            'actoAdministrativo.required' => 'El campo acto administrativo es obligatorio',
            'salvoconducto.required' => 'El campo salvoconducto es obligatorio',
            'titularSalvoconducto.required' => 'El campo titular salvoconducto es obligatorio',
            'procedencia.required' => 'El campo procedencia es obligatorio',
            'entidadVigilante.required' => 'El campo entidad vigilante es obligatorio',
            
            'condicionMadera.required' => 'El campo condición madera es obligatorio',
            'm3entrada.required' => 'El campo m3 entrada es obligatorio',
            'proveedor.required' => 'El campo proveedor es obligatorio',
            'madera.required' => 'El campo madera es obligatorio',

            'salvoconducto.numeric' => 'Salvoconducto remision debe ser numérico',
            'm3entrada.numeric' => 'Metros cubicos de entrada  debe ser numérico',
        ];
    }
}
