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
            'name' => 'required',
            'email' => 'required|unique:users,email',
            
        ];

        
    }

    public function messages()
    {
        return [
            'identificacionUsuario.required' => 'El campo identificación es obligatorio',
            'identificacionUsuario.unique' => 'El campo identificación ya existe',
            'name.required' => 'El campo nombre es obligatorio',
            'email.required' => 'El campo email es obligatorio',
            'email.unique' => 'El campo email ya existe',
        ];
    }

    
}
