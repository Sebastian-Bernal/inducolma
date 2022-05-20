<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreUsuariosRequest extends FormRequest
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
            'identificacionUsuario' => 'required|unique:users,identificacion',
            'primer_nombre' => 'required|string|max:255',
            'segundo_nombre' => 'string|max:255',
            'primer_apellido' => 'required|string|max:255',
            'segundo_apellido' => 'string|max:255',
            'email' => 'required|email|unique:users,email',
           
            
        ];

        
    }

    public function messages()
    {
        return [
            'identificacionUsuario.required' => 'identificación es obligatorio',
            'identificacionUsuario.unique' => 'identificación ya existe',
            'primer_nombre.required' => 'primer nombre es obligatorio',
            'primer_nombe.string' => 'primer nombre debe ser una cadena de texto',
            'primer_nombre.max' => 'primer nombre debe tener máximo 255 caracteres',
            'segundo_nombre.string' => 'segundo nombre debe ser una cadena de texto',
            'segundo_nombre.max' => 'segundo nombre debe tener máximo 255 caracteres',
            'primer_apellido.required' => 'primer apellido es obligatorio',
            'primer_apellido.string' => 'primer apellido debe ser una cadena de texto',
            'primer_apellido.max' => 'primer apellido debe tener máximo 255 caracteres',
            'segundo_apellido.string' => 'segundo apellido debe ser una cadena de texto',
            'segundo_apellido.max' => 'segundo apellido debe tener máximo 255 caracteres',
            'email.required' => 'email es obligatorio',
            'email.unique' => 'email ya existe',
        ];
    }

    
}
