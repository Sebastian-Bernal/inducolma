<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRolRequest extends FormRequest
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
            'nombre' => 'required|unique:rols,nombre',
            'descripcion' => 'required',
            'nivel' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return[
            'nombre.required' =>'El campo nombre es obligatorio',
            'nombre.unique' => 'El nombre ya existe',
            'descripcion.required' => 'El campo descripcion es obligatorio',
            'nivel.required' => 'El campo nivel es obligatorio',
            'nivel.numeric' => 'El campo nivel debe ser un número',
        ];
    }
}
