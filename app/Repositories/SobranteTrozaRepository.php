<?php
namespace App\Repositories;

use App\Models\Cubicaje;
use App\Models\SobranteTrozas;
use Exception;
use Illuminate\Support\Facades\DB;

class SobranteTrozaRepository
{
    public function procesarSobrantesTrozas($transformacionesSobrantes)
    {
        try {
            DB::beginTransaction();

            collect($transformacionesSobrantes)->each(function ($transformacionSobrante) {
                $this->guardarSobranteTroza(json_decode(json_encode($transformacionSobrante)));
            });


            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    private function guardarSobranteTroza($transformacionSobrante)
    {
        $maderaId = $this->obtenerMaderaId($transformacionSobrante->id);

        SobranteTrozas::create([
            'ancho' => $transformacionSobrante->ancho,
            'largo' => $transformacionSobrante->largo,
            'alto' => $transformacionSobrante->alto,
            'estado' => 'DISPONIBLE',
            'cubicaje_id' => $transformacionSobrante->id,
            'madera_id' => $maderaId,
        ]);

        $this->actualizarCubicaje($transformacionSobrante->id);
    }

    private function obtenerMaderaId($transformacionId)
    {
        $cubicaje = Cubicaje::with('EntradaMadera.entradas_madera_maderas')->find($transformacionId);

        if (!$cubicaje) {
            throw new Exception('No se encontró la madera');
        }

        return $cubicaje->EntradaMadera->entradas_madera_maderas->first()->madera_id;
    }

    private function actualizarCubicaje($transformacionId)
    {
        $cubicaje = Cubicaje::find($transformacionId);

        if (!$cubicaje) {
            throw new Exception('No se encontró el cubicaje');
        }

        $cubicaje->estado_troza = 2; // estado indica paso por reaserrio
        $cubicaje->save();
    }

}
