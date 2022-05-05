<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInsumosRequest extends FormRequest
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
            "descripcion" => "required|string|max:255|unique:insumos_almacen,descripcion",
            "cantidad" => "required|numeric|min:0",
            "precio_unitario" => "required|numeric|min:0",
        ];
    }

    function messages()
    {
        return [
            "descripcion.required" => "La descripcion es requerida",
            "descripcion.string" => "La descripcion debe ser una cadena de caracteres",
            "descripcion.max" => "La descripcion debe tener como maximo 255 caracteres",
            "descripcion.unique" => "La descripcion ya existe ",
            "cantidad.required" => "La cantidad es requerida",
            "cantidad.numeric" => "La cantidad debe ser un numero",
            "cantidad.min" => "La cantidad debe ser mayor o igual a 0",
            "precio_unitario.required" => "El precio unitario es requerido",
            "precio_unitario.numeric" => "El precio unitario debe ser un numero",
            "precio_unitario.min" => "El precio unitario debe ser mayor o igual a 0",
        ];
    }
}
