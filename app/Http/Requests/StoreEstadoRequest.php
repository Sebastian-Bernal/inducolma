<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEstadoRequest extends FormRequest
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
            'descripcion' => 'required|unique:estados,descripcion',
        ];
    }

    public function messages()
    {
        return [
            'descripcion.required' => 'La descripción es obligatorio.',
            'descripcion.unique' => 'La descripción ya existe.',
        ];
    }
}
