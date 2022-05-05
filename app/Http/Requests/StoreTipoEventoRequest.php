<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTipoEventoRequest extends FormRequest
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
            'tipo_evento' => 'required|unique:tipo_eventos',
        ];
    }

    public function messages()
    {
        return [
            'tipo_evento.required' => 'El campo tipo de evento es obligatorio',
            'tipo_evento.unique' => 'El tipo de evento ya existe',
        ];
    }
}
