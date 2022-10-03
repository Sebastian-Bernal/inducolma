<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTurnoRequest extends FormRequest
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
            'turno' =>'required|unique:turnos,turno',
            'hora_inicio' =>'required',
            'hora_fin' =>'required',
            'estado' =>'required',
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
            'turno.required' => 'La descripcion del turno es obligatoria',
            'turno.unique' => 'La descripcion del turno ya existe',
            'hora_inicio.required' => 'La hora inicio es obligatoria',
            'hora_fin.required' => 'La hora fim es obligatoria',
            'estado.required' => 'El estado es obligatorio',

        ];
    }
}
