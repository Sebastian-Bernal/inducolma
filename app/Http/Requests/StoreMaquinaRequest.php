<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaquinaRequest extends FormRequest
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
            'maquina' => [
                'required',
                'unique:maquinas,maquina',
                'max:50',
                'min:5'
            ]

        ];
    }

    public function messages()
    {
        return [
            'maquina.required' => 'El campo :attribute es obligatorio',
            'maquina.unique' => 'El nombre de :attribute ya existe',
            'maquina.max' => 'El nombre de :attribute debe tener un máximo de 50 caracteres',
            'maquina.min' => 'El nombre de :attribute debe tener un mínimo de 5 caracteres'
        ];
    }
}
