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
            $registro->cm3 = $cubicaje['largo']*$cubicaje['ancho']*$cubicaje['alto'];
            $registro->pulgadas_cuadradas = ($cubicaje['alto']/2.54)*($cubicaje['ancho']/2.54);
            $registro->pulgadas_cuadradas_x3_metros = ($cubicaje['largo']/300)* (((integer)($cubicaje['alto']/2.54)) * ((integer)($cubicaje['ancho']/2.54)));
            $registro->bloque = $cubicaje['bloque'];
            $registro->entrada_madera_id = $cubicaje['entrada_id'];
            $registro->user_id = $cubicaje['user_id'];
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
