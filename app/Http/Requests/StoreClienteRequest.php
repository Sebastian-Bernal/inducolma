<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClienteRequest extends FormRequest
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
            "nit" => "required|numeric|unique:clientes",
            "nombre" => "required",
            "direccion" => "required",
            "telefono" => "required",
            "email" => "required|email",            
        ];
    }

    public function messages()
    {
        return [
            "nit.required" => "El campo NIT es obligatorio",
            "nit.unique" => "El NIT ya existe",
            "nit.numeric" => "El NIT debe ser un número",
            "nombre.required" => "El campo Nombre es obligatorio",
            "direccion.required" => "El campo Dirección es obligatorio",
            "telefono.required" => "El campo Teléfono es obligatorio",
            "email.required" => "El campo Email es obligatorio",
            "email.email" => "El campo Email debe ser un email válido",
        ];
    }
}
