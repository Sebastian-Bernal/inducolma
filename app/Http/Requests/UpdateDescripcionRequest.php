<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDescripcionRequest extends FormRequest
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
            'descripcion' => [
                'required',
                'unique:operaciones,operacion',
                'max:50',
                'min:5'
            ],
            'idOperacion' => [
                'required',                
            ]

        ];
    }

    public function messages()
    {
        return [
            'operacion.required' => 'El campo :attribute es obligatorio',
            'operacion.unique' => 'El nombre de :attribute ya existe',
            'operacion.max' => 'El nombre de :attribute debe tener un máximo de 50 caracteres',
            'operacion.min' => 'El nombre de :attribute debe tener un mínimo de 5 caracteres',
            'idOperacion.required' => 'El campo :attribute es obligatorio',
        ];
    }
}
