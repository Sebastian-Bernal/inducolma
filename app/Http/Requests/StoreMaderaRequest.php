<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaderaRequest extends FormRequest
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
            'nombre' => 'required|unique:maderas,nombre',
            'nombre_cientifico' => 'required',
            'densidad' => 'required',          
        
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El campo nombre es obligatorio',
            'nombre.unique' => 'El nombre ya existe',
            'nombre_cientifico.required' => 'El campo nombre cientifico es obligatorio',
            'densidad.required' => 'El campo densidad es obligatorio',
        ];
    }
}
