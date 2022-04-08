<?php 

namespace App\Repositories;

use App\Models\Cubicaje;

class RegistroCubicajes 
{
    public function guardar($datos)
    {

       // return $datos[0]->paqueta;
        $guardados = 0;
        foreach ($datos as $cubicaje) {
            $registro = new Cubicaje();
            $registro->paqueta = $cubicaje['paqueta'];
            $registro->largo = $cubicaje['largo'];
            $registro->ancho = $cubicaje['ancho'];
            $registro->alto = $cubicaje['alto'];
            $registro->bloque = $cubicaje['bloque'];
            $registro->entrada_madera_id = $cubicaje['entrada_id'];
            $registro->user_id = auth()->user()->id;
            $registro->save();
            $guardados++;
        }      
        if($guardados == count($datos)){
            return ['error' => false, 'message' => 'Cubicajes guardados correctamente'];
        } else {
            return ['error' => true, 'message' => 'Error al guardar cubicajes'];
        }      
    }
}
