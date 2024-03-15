<?php

namespace App\Repositories;

use App\Models\Cubicaje;
use Exception;

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
            if ((int)$cubicaje['alto'] > (int)$cubicaje['ancho']) {
                $registro->alto = $cubicaje['ancho'];
                $registro->ancho = $cubicaje['alto'];
            } else {
                $registro->alto = $cubicaje['alto'];
                $registro->ancho = $cubicaje['ancho'];
            }
            $registro->cm3 = $cubicaje['largo']*$cubicaje['ancho']*$cubicaje['alto'];
            $registro->pulgadas_cuadradas = (($cubicaje['alto']/2.54)-$cubicaje['pulgadasAlto'])*(($cubicaje['ancho']/2.54)-$cubicaje['pulgadasAncho']);
            $registro->pulgadas_cuadradas_x3_metros = ($cubicaje['largo']/300)* (((integer)($cubicaje['alto']/2.54)) * ((integer)($cubicaje['ancho']/2.54)));
            $registro->bloque = $cubicaje['bloque'];
            $registro->pulgadas_ancho = $cubicaje['pulgadasAncho'];
            $registro->pulgadas_alto = $cubicaje['pulgadasAlto'];
            $registro->estado = 'DISPONIBLE';
            $registro->entrada_madera_id = $cubicaje['entrada_id'];
            $registro->user_id = $cubicaje['user_id'];
            $registro->troza_id = $cubicaje['troza_id'];
            $registro->save();
            $guardados++;
        }
        if($guardados == count($datos)){
            return ['error' => false, 'message' => 'Cubicajes guardados correctamente'];
        } else {
            return ['error' => true, 'message' => 'Error al guardar cubicajes'];
        }

    }
    /**
     * guarda las trozas ingresadas en cubicaje
     *
     * @param Object $datos
     */
    public function guardarTroza($datos)
    {
        // return $datos[0]->paqueta;
        $guardados = 0;
        foreach ($datos as $cubicaje) {
            $registro = new Cubicaje();
            $registro->paqueta = $cubicaje['paqueta'];
            $registro->largo = $cubicaje['largo'];
            if ((int)$cubicaje['alto'] < (int)$cubicaje['ancho']) {
                $registro->diametro_mayor = $cubicaje['ancho'];
                $registro->diametro_menor = $cubicaje['alto'];
                $registro->cm3 = (3.1417*($cubicaje['alto']/2) * $cubicaje['largo']);
            } else {
                $registro->diametro_mayor = $cubicaje['alto'];
                $registro->diametro_menor = $cubicaje['ancho'];
                $registro->cm3 = (3.1417*($cubicaje['ancho']/2) * $cubicaje['largo']);
            }

            $registro->bloque = $cubicaje['bloque'];
            $registro->estado = 'TROZA';
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

    /**
     * Actualiza los registros de cubicajes que ingresaron como trozas asignando los datos
     * de la transformacion realizada.
     *
     * @param Object $datos
     */

    public function actualizarTrozas($bloques)
    {
        $bloques = json_decode(json_encode($bloques));
        $guardados = 0;
        foreach ($bloques as $bloque) {
            try {
                $actualizar = Cubicaje::find($bloque->id);
                $actualizar->ancho =  $bloque->ancho;
                $actualizar->alto = $bloque->alto;
                $actualizar->pulgadas_cuadradas = (($bloque->alto/2.54))*(($bloque->ancho/2.54));
                $actualizar->pulgadas_cuadradas_x3_metros = ($bloque->largo/300)* (((integer)($bloque->alto/2.54)) * ((integer)($bloque->ancho/2.54)));
                $actualizar->estado = 'DISPONIBLE';
                $actualizar->save();
                $guardados ++;
            } catch (Exception $e) {
                throw new Exception("Error al actualizar los datos del cubicaje: ", $e->getMessage());
                break;
            }
        }

        if ($guardados != count($bloques)) {
            return ['error' => true, 'message' => 'Uno o mas bloques no pudo ser guardado'];
        }

        return  ['error' => false, 'message' => 'Los bloques se guardaron con éxito'];

    }
}
