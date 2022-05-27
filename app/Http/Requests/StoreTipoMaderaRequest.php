<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTipoMaderaRequest extends FormRequest
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
            'descripcion' => 'required|unique:tipo_maderas,descripcion|max:255',
        ];


    }

    public function messages()
    {
        return [
            'descripcion.required' => 'El campo descripción es obligatorio',
            'descripcion.unique' => 'El tipo de madera ya existe',
            'descripcion.max' => 'El campo descripción debe tener máximo 255 caracteres',
        ];
    }
}
