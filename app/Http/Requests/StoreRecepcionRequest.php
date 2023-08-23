<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecepcionRequest extends FormRequest
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
            'cc' => 'required|numeric|digits_between:7,15',
            'primer_apellido' => 'required|string|max:50',

            'primer_nombre' => 'required|string|max:50',

            //'visitante' => 'required|boolean',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */

    public function messages()
    {
        return [
            'cc.required' => 'Cédula es obligatorio',
            'cc.numeric' => 'Cédula debe ser un número',
            'cc.digits_between' => 'Cédula debe tener entre 7 y 15 dígitos',
            'primer_apellido.required' => 'Primer Apellido es obligatorio',
            'primer_apellido.string' => 'Primer Apellido debe ser una cadena de caracteres',
            'primer_apellido.max' => 'Primer Apellido debe tener máximo 50 caracteres',

            'primer_nombre.required' => 'Primer Nombre es obligatorio',
            'primer_nombre.string' => 'Primer Nombre debe ser una cadena de caracteres',
            'primer_nombre.max' => 'Primer Nombre debe tener máximo 50 caracteres',

            //'visitante.required' => 'Visitante? es obligatorio',
            'visitante.boolean' => 'Visitante debe ser un Si o un No',
        ];
    }

}
