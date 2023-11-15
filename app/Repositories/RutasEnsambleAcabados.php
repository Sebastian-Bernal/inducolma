<?php


namespace App\Repositories;

use App\Models\EnsambleAcabado;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;

class RutasEnsambleAcabados
{

    public function crearRutas($rutas)  {
        $pedidoId = $rutas->pedido_id;
        $userId = $rutas->user_id;
        $datos = json_decode(json_encode($rutas->rutas));
        $ensamblesAcabados = collect($datos)->map(function($ruta)use($pedidoId, $userId){
            return [
                'pedido_id' => $pedidoId,
                'maquina_id' => $ruta->maquina_id,
                'cantidad' => $ruta->cantidad,
                'estado' => 'PENDIENTE',
                'observaciones' => $ruta->observaciones ?? '',
                'user_id' => $userId

            ];
        });

        $pedido = Pedido::find($pedidoId);


        $creacionRutas = $pedido->ensambles_acabados()->createMany($ensamblesAcabados);


        return $creacionRutas ? true : false;
    }


    public function updateRuta($ruta, $id)  {

        $ruta = EnsambleAcabado::find($id);
        $ruta->pedido_id = $ruta->pedido_id;
        $ruta->maquina_id = $ruta->maquina_id;
        $ruta->cantidad = $ruta->cantidad;
        $ruta->estado = $ruta->estado ?? 'PENDIENTE';
        $ruta->observaciones = $ruta->observaciones ?? '';
        $ruta->user_id = $ruta->user_id;
        $ruta->updated_at = now();
        $ruta->save();

        return $ruta;
    }


    public function deleteRuta($id) {
        $ruta = EnsambleAcabado::find($id);
        if($ruta == null){
            return 'not found';
        }
        if($ruta->delete()) {
            return true;
        }
        return false;

    }

}
