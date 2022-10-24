<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTurnoUserRequest extends FormRequest
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
            'usuario' => 'required|integer',
            'turno' => 'required|integer',
            'maquina' => 'required|integer',
            'desde' => 'required|date',
            'hasta' => 'required|date',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages()
    {
        return [
            'usuario.required' => 'Usuario es obligatorio',
            'usuario.integer' => 'El tipo de dato de usuario es incorrecto',
            'turno.required' => 'Turno es obligatorio',
            'turno.integer' => 'El tipo de dato de turno es incorrecto',
            'maquina.required' => 'Maquina es obligatorio',
            'maquina.integer' => 'El tipo de dato de maquina es incorrecto',
            'desde.required' => 'Hasta es obligatorio',
            'desde.date' => 'El tipo de dato de la fecha desde es incorrecto',
            'hasta.required' => 'Hasta es obligatorio',
            'hasta.integer' => 'El tipo de dato de la fecha desde es incorrecto',
        ];
    }
}
