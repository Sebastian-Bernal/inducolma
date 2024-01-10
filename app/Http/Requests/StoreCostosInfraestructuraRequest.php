<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCostosInfraestructuraRequest extends FormRequest
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
            'maquina' => 'required|integer|exists:maquinas,id',
            'tipo_material' => 'required',
            'tipo_madera' => 'required|integer|exists:tipo_maderas,id',
            'unidades_minuto' => 'required|min:1',
        ];
    }
}
