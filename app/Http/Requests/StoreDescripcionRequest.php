<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDescripcionRequest extends FormRequest
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
                'unique:descripciones,descripcion',
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
            'descripcion.required' => 'El campo :attribute es obligatorio',
            'descripcion.unique' => 'El nombre de :attribute ya existe',
            'descripcion.max' => 'El nombre de :attribute debe tener un máximo de 50 caracteres',
            'descripcion.min' => 'El nombre de :attribute debe tener un mínimo de 5 caracteres',
            'idOperacion.required' => 'El campo :attribute es obligatorio',
        ];
    }
}
