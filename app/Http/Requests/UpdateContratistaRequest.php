<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContratistaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        return [
            'cedula' => 'required',
            'primer_nombre' => 'required|string|max:255',
            'segundo_nombre' => 'string|max:255',
            'primer_apellido' => 'required|string|max:255',
            'segundo_apellido' => 'string|max:255',
           // 'acceso' => 'boolean',
            'empresa_contratista'=> 'required|string|max:255',
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
            'cedula.required' => 'La cédula es requerida',
            'cedula.unique' => 'La cédula ya esta registrada',
            'primer_nombre.required' => 'El primer nombre es requerido',
            'primer_nombe.string' => 'El primer nombre debe ser una cadena de caracteres',
            'primer_nombre.max' => 'El primer nombre no debe exceder los 255 caracteres',
            'primer_apellido.required' => 'El primer apellido es requerido',
            'primer_apellido.string' => 'El primer apellido debe ser una cadena de caracteres',
            'primer_apellido.max' => 'El primer apellido no debe exceder los 255 caracteres',
            'empresa_contratista.required' => 'La empresa es requerida',
            'segundo_nombre.string' => 'El segundo nombre debe ser una cadena de caracteres',
            'segundo_nombre.max' => 'El segundo nombre debe ser menor a 255 caracteres',
            'segundo_apellido.string' => 'El segundo apellido debe ser una cadena de caracteres',
            'segundo_apellido.max' => 'El segundo apellido debe ser menor a 255 caracteres',
            'acceso.boolean' => 'El acceso debe ser activado o desactivado',

        ];
    }
}
