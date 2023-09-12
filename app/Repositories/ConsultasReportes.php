<?php

namespace App\Repositories;

use App\Models\EntradaMadera;
use App\Models\Madera;

class ConsultasReportes {

    /**
     * selecciona el tipo de consulta que llega por request
     * @param Request $request [request enviado con datos del tipo de reporte a generar]
     * @return $data [data que enuentra la consulta]
     * @return $encabezado [encabezado del tipo de reporte generado]
     */
    public function seleccionarReporte($request)
    {
        $desde = $request->desdeIm;
        $hasta = $request->hastaIm;
        $tipoReporte = $request->tipoReporte;
        switch ($tipoReporte) {
            case '1':
                $data = $this->consulta($desde, $hasta,'maderas.densidad' ,'ALTA DENSIDAD', $request->generar);
                $encabezado = 'REPORTE MADERA DE ALTA DENSIDAD';
                break;
            case '2':
                $data = $this->consulta($desde, $hasta, 'maderas.densidad' , 'BAJA DENSIDAD', $request->generar);
                $encabezado = 'REPORTE MADERA DE BAJA DENSIDAD';
                break;
            case '3':
                $data = $this->consulta($desde, $hasta,'proveedores.id',$request->especifico,  $request->generar);
                $encabezado = 'REPORTE MADERAS POR PROVEEDOR';
                break;
            case '4':
                $data = $this->consulta($desde, $hasta, 'tipo_maderas.id', $request->especifico, $request->generar);
                $encabezado = 'REPORTE MADERA POR TIPO DE MADERA';
                break;
            case '5':
                $data = $this->consulta($desde, $hasta, 'entidad_vigilante', 'ICA', $request->generar);
                $encabezado = 'REPORTE MADERA POR ENTIDAD VIGILANTE ICA';
                break;
            case '6':
                $data = $this->consulta($desde, $hasta, 'entidad_vigilante', 'CVC',  $request->generar);
                $encabezado = 'REPORTE MADERA POR ENTIDAD VIGILANTE CVC';
                break;
            default:
                return redirect()->back()->with('status','No se encontraron datos para la consulta.');
                break;
        }
        return [$data, $encabezado];
    }


    /**
     * funcion consulta, retorna los datos de la consulta filtrada por los parametros $where, $termino
     * @param $desde [fecha inicial de la busqueda]
     * @param $hasta [fecha final de la busqueda]
     * @param $where [clausula where a consultar]
     * @param $termino [valor del filtro a consultar]
     */
    public function consulta($desde, $hasta, $where, $termino, $generar)
    {
        $data = EntradaMadera::join('entradas_madera_maderas','entradas_madera_maderas.entrada_madera_id', '=', 'entrada_maderas.id')
                                ->join('maderas', 'maderas.id', '=', 'entradas_madera_maderas.madera_id')
                                ->join('tipo_maderas', 'tipo_maderas.id' ,'=', 'maderas.tipo_madera_id' )
                                ->join('proveedores', 'proveedores.id', '=', 'entrada_maderas.proveedor_id' )
                                ->where($where, $termino)
                                ->whereBetween('entrada_maderas.created_at',[$desde, $hasta])
                                ->get([

                                    'entrada_maderas.mes',
                                    'entrada_maderas.ano',
                                    'entrada_maderas.acto_administrativo',
                                    'entrada_maderas.fecha',
                                    'entrada_maderas.created_at',
                                    'entrada_maderas.salvoconducto_remision',
                                    'entrada_maderas.titular_salvoconducto',
                                    'entrada_maderas.procedencia_madera',
                                    'entrada_maderas.entidad_vigilante',
                                    'proveedores.nombre',
                                    'proveedores.razon_social',
                                    'entradas_madera_maderas.m3entrada',
                                    'entradas_madera_maderas.condicion_madera',
                                    'entradas_madera_maderas.id',
                                    'maderas.densidad',
                                    'maderas.nombre_cientifico',
                                    'tipo_maderas.descripcion',

                                ]);

        if ($generar == '2' || $generar == '3') {
            return $data;
        }
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
                        'condicion_madera' => $t->condicion_madera
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
                    'razon_social' => $t->razon_social,
                    'maderas' => array(
                        array(
                            'nobre_comun' => $t->descripcion,
                            'nombre_cientifico' => $t->nombre_cientifico,
                            'm3entrada' => $t->m3entrada,
                            'condicion_madera' => $t->condicion_madera
                        )
                    )
                );

            }
        }
        return $result;
    }

}
