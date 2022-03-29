<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMaderaRequest extends FormRequest
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
            'nombre' => 'required|string|max:255',
            'nombre_cientifico' => 'required|string|max:255',
            'densidad' => 'required|numeric',
        
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El campo nombre es obligatorio',
            'nombre_cientifico.required' => 'El campo nombre cientifico es obligatorio',
            'densidad.required' => 'El campo densidad es obligatorio',
        ];
    }
}
