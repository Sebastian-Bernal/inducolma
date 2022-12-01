<?php

namespace App\Repositories;

use App\Http\Requests\StoreEntraMaderaRequest;
use App\Models\EntradaMadera;
use App\Models\EntradasMaderaMaderas;

class RegistroEntradaMadera
{
    /**
     * Funcion que valida los datos de entrada del registro de entrada de madera
     */
    public function validarDatosEntrada($datos)
    {
        // valida datos de entrada con el StoreEntraMaderaRequest
        $validar = new StoreEntraMaderaRequest();
        return $validar->validate($datos);
    }

    /**
     * Funcion que guarda los datos de entrada del registro de entrada de madera
     */
    public function guardar($entrada)
    {
        //return $entrada->entrada[1];
        //return $entrada;
        $registroEntrada =  $this->guardarEtrada($entrada);
        //return $registroEntrada;
        if ($registroEntrada['error']) {
            return response()->json(['error' => $registroEntrada['error'], 'message' => $registroEntrada['message']]);
        } else {
            $registroMaderas = $this->guardarMaderas($entrada, $registroEntrada['id']);
            if ($registroMaderas['error']) {
                return response()->json(['error' => $registroMaderas['error'], 'message' => $registroMaderas['message']]);
            } else {
                return response()->json([
                    'error' => false,
                    'message' => 'Registro de entrada de madera guardado correctamente',
                    'id' => $registroMaderas['idRegisto']
                ]);
            }
        }
    }

    /**
     * Funcion que guarda los datos de entrada del registro de entrada de madera
     */
    public function guardarEtrada($entradaMadera)
    {
        //return $entrada->entrada[0]['mes'];
        //return $entrada;
        $entrada = new EntradaMadera();
        $entrada->mes = $entradaMadera->entrada[0]['mes'];
        $entrada->ano = $entradaMadera->entrada[0]['ano'];
        $entrada->hora = $entradaMadera->entrada[0]['hora'];
        $entrada->fecha = $entradaMadera->entrada[0]['fecha'];
        $entrada->acto_administrativo = $entradaMadera->entrada[0]['actoAdministrativo'];
        $entrada->salvoconducto_remision = $entradaMadera->entrada[0]['salvoconducto'];
        $entrada->titular_salvoconducto = $entradaMadera->entrada[0]['titularSalvoconducto'];
        $entrada->procedencia_madera = $entradaMadera->entrada[0]['procedencia'];
        $entrada->entidad_vigilante = $entradaMadera->entrada[0]['entidadVigilante'];
        $entrada->estado = 'PENDIENTE';
        $entrada->proveedor_id = $entradaMadera->entrada[0]['proveedor'];
        $entrada->user_id = auth()->user()->id;
        //$entrada->madera_id = $entradaMadera->entrada[0]['id'];

        //return $entrada->save();

        if ($entrada->save() == 1) {
            $respuesta['error'] = false;
            $respuesta['id'] = $entrada->id;
            //$respuesta ['message'] = 'Registro de entrada de madera guardado correctamente';
            return $respuesta;
        } else {
            return $respuesta = ['error' => true, 'message' => 'Error al guardar la entrada de madera'];
        } //*/
    }

    /**
     * Funcion que guarda los datos de entrada del registro de entrada de madera
     */
    public function guardarMaderas($entradaMadera, $idEntrada)
    {
        //return $entrada->entrada[0]['mes'];
        //return $entrada;
        $maderas = $entradaMadera->entrada[1];
        // $maderas = $entradaMadera->entrada[0]['maderas'];
        $maderasGuardadas = 0;
        foreach ($maderas as $madera) {
            $registroMadera = new EntradasMaderaMaderas();
            $registroMadera->entrada_madera_id = $idEntrada;
            $registroMadera->madera_id = $madera['id'];
            $registroMadera->m3entrada = $madera['metrosCubicos'];
            $registroMadera->condicion_madera = $madera['condicion'];
            $registroMadera->save();
            $maderasGuardadas++;
        }
        if ($maderasGuardadas == count($maderas)) {
            return ['error' => false, 'idRegisto' => $idEntrada];
        } else {
            return ['error' => true, 'message' => 'Error al guardar las maderas'];
        }
    }

    /**
     * Funcion que actualiza los datos de entrada del registro de entrada de madera
     */

    public function actualizar($entradaMadera)
    {
        //return $entrada->entrada[0]['mes'];
        //return $entrada;
        $entrada = EntradaMadera::find($entradaMadera->entrada[0]['id_ultima']);
        $entrada->mes = $entradaMadera->entrada[0]['mes'];
        $entrada->ano = $entradaMadera->entrada[0]['ano'];
        $entrada->hora = $entradaMadera->entrada[0]['hora'];
        $entrada->fecha = $entradaMadera->entrada[0]['fecha'];
        $entrada->acto_administrativo = $entradaMadera->entrada[0]['actoAdministrativo'];
        $entrada->salvoconducto_remision = $entradaMadera->entrada[0]['salvoconducto'];
        $entrada->titular_salvoconducto = $entradaMadera->entrada[0]['titularSalvoconducto'];
        $entrada->procedencia_madera = $entradaMadera->entrada[0]['procedencia'];
        $entrada->entidad_vigilante = $entradaMadera->entrada[0]['entidadVigilante'];
        $entrada->proveedor_id = $entradaMadera->entrada[0]['proveedor'];
        $entrada->user_id = auth()->user()->id;
        //$entrada->madera_id = $entradaMadera->entrada[0]['id'];

        //return $entrada->save();

        if ($entrada->update() == 1) {

            // funcion actualiza maderas
            $respuesta =  $this->actualizarMaderas($entradaMadera, $entradaMadera->entrada[0]['id_ultima']);
            return $respuesta;
            //$entrada->maderas()->sync($entradaMadera->entrada[1]);

        } else {
            return $respuesta = ['error' => true, 'message' => 'Error al actualizar la entrada de madera'];
        }
    }

    /**
     * Funcion que actualiza los datos de las maderas del registro de entrada de madera
     */

    function actualizarMaderas($entradaMadera, $idEntrada)
    {

        //return $idEntrada;
        $maderas = $entradaMadera->entrada[1];
        //$maderas = $entradaMadera->entrada[0]['maderas'];
        $maderasGuardadas = 0;
        foreach ($maderas as $madera) {


            if (isset($madera['entrada_id'])) {
                $registroMadera = EntradasMaderaMaderas::find($madera['entrada_id']);
                $registroMadera->entrada_madera_id = $idEntrada;
                $registroMadera->madera_id = $madera['id'];
                $registroMadera->m3entrada = $madera['metrosCubicos'];
                $registroMadera->condicion_madera = $madera['condicion'];
                $registroMadera->update();
                $maderasGuardadas++;
                # code...
            } else {
                $registroMadera = new EntradasMaderaMaderas();
                $registroMadera->entrada_madera_id = $idEntrada;
                $registroMadera->madera_id = $madera['id'];
                $registroMadera->m3entrada = $madera['metrosCubicos'];
                $registroMadera->condicion_madera = $madera['condicion'];
                $registroMadera->save();
                $maderasGuardadas++;
            }
        }
        if ($maderasGuardadas == count($maderas)) {
            return ['error' => false, 'id' => $idEntrada, 'message' => 'Registros actualizados correctamente'];
        } else {
            return ['error' => true, 'message' => 'Error al actualizar las maderas'];
        }
    }
}
