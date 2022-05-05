<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProveedorRequest extends FormRequest
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
            'identificacion' => 'required|unique:proveedores',
            'nombre' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
            'email' => 'required|email',
            'razon_social' => 'required',
            
        ];
    }

    public function messages()
    {
        return [
            'identificacion.required' => 'El campo identificación es obligatorio',
            'identificacion.unique' => 'El campo identificación ya existe',
            'nombre.required' => 'El campo nombre es obligatorio',
            'direccion.required' => 'El campo dirección es obligatorio',
            'telefono.required' => 'El campo teléfono es obligatorio',
            'email.required' => 'El campo email es obligatorio',
            'razon_social.required' => 'El campo razón social es obligatorio',
        ];
    }
}
