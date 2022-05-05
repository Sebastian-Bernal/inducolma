<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventoRequest extends FormRequest
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
            'descripcion' => 'required|string|max:255|unique:eventos,descripcion',
            'tipoEvento' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'descripcion.required' => 'La descripción es obligatoria',
            'descripcion.string' => 'La descripción debe ser un texto',
            'descripcion.max' => 'La descripción debe tener un máximo de 255 caracteres',
            'descripcion.unique' => 'La descripción ya existe',
            'tipoEvento.required' => 'El tipo de evento es obligatorio',
            'tipoEvento.string' => 'El tipo de evento debe ser un texto',
            'tipoEvento.max' => 'El tipo de evento debe tener un máximo de 255 caracteres',

        ];
    }
}
