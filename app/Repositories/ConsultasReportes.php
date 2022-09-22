<?php

namespace App\Repositories;

use App\Models\EntradaMadera;
use App\Models\Madera;

class ConsultasReportes {

    /**
     * funcion consulta, retorna los datos de la consulta filtrada por los parametros $where, $termino
     * @param $desde [fecha inicial de la busqueda]
     * @param $hasta [fecha final de la busqueda]
     * @param $where [clausula where a consultar]
     * @param $termino [valor del filtro a consultar]
     */
    public function consulta($desde, $hasta, $where, $termino)
    {
        $data = EntradaMadera::join('entradas_madera_maderas','entradas_madera_maderas.entrada_madera_id', '=', 'entrada_maderas.id')
                                ->join('maderas', 'maderas.id', '=', 'entradas_madera_maderas.madera_id')
                                ->join('tipo_maderas', 'tipo_maderas.id' ,'=', 'maderas.tipo_madera_id' )
                                ->join('proveedores', 'proveedores.id', '=', 'entrada_maderas.proveedor_id' )
                                ->where($where, $termino)
                                ->whereBetween('entrada_maderas.created_at',[$desde, $hasta])
                                ->get([
                                    'entrada_maderas.id',
                                    'entrada_maderas.mes',
                                    'entrada_maderas.ano',
                                    'entrada_maderas.acto_administrativo',
                                    'entrada_maderas.fecha',
                                    'entrada_maderas.salvoconducto_remision',
                                    'entrada_maderas.titular_salvoconducto',
                                    'entrada_maderas.procedencia_madera',
                                    'entrada_maderas.entidad_vigilante',
                                    'proveedores.nombre',
                                    'entradas_madera_maderas.m3entrada',
                                    'maderas.nombre_cientifico',
                                    'tipo_maderas.descripcion',

                                ]);

        return $this->agrupar($data);
    }

    public function agrupar($data)
    {
        $result = array();

        foreach ($data as $t) {
            $repetir = false;
            for ($i = 0; $i < count($result); $i++) {
                if($result[$i]['entrada_madera_id'] == $t->id){
                    $madera = array(
                        'nobre_comun' => $t->descripcion,
                        'nombre_cientifico' => $t->nombre_cientifico,
                        'm3entrada' => $t->m3entrada,
                    );
                    array_push($result[$i]['maderas'],$madera);
                    $repetir = true;
                    break;
                }
            }
            if ($repetir == false) {
                $result[] = array(
                    'entrada_madera_id' => $t->id,
                    'mes' => $t->mes,
                    'ano' => $t->ano,
                    'acto_administrativo' => $t->acto_administrativo,
                    'fecha' => $t->fecha,
                    'salvoconducto_remision' => $t->salvoconducto_remision,
                    'titular_salvoconducto' => $t->titular_salvoconducto,
                    'procedencia_madera' => $t->procedencia_madera,
                    'entidad_vigilante' => $t->entidad_vigilante,
                    'proveedor' => $t->nombre,
                    'maderas' => array(
                        array(
                            'nobre_comun' => $t->descripcion,
                            'nombre_cientifico' => $t->nombre_cientifico,
                            'm3entrada' => $t->m3entrada,
                        )
                    )
                );

            }
        }
        return $result;
    }

}
