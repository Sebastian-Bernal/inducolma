<?php

namespace App\Repositories\Reportes;

use App\Models\Cubicaje;

class ConsultasCubicajes {

    public function consultaDatos($request)
    {
        $desde = $request->cubicajeDesde;
        $hasta = $request->cubicajeHasta;
        $tipoReporte = $request->tipoReporteCubicaje;

        switch ($tipoReporte) {
            case '1':
                $data = $this->consultaCubicajeViaje($request->filtroCubiaje1);
                if (count($data) > 0) {
                    $encabezado = "CUBICAJE DEL VIAJE No. {$data[0]->entrada_madera_id}" ;
                }else{
                    $encabezado = 'algo';
                }
                break;
            /* case '2':
                $data = $this->consulta($desde, $hasta, 'maderas.densidad' , 'BAJA DENSIDAD', $request->generar);
                $encabezado = 'TRANSFORMACION DE LA MADERA POR VIAJE';
                break;
            case '3':
                $data = $this->consulta($desde, $hasta,'proveedores.id',$request->especifico,  $request->generar);
                $encabezado = 'CALIFICACION DE LAS PAQUETAS POR VIAJE';
                break;
            case '4':
                $data = $this->consulta($desde, $hasta, 'tipo_maderas.id', $request->especifico, $request->generar);
                $encabezado = 'CALIFICACION DE PAQUETAS POR PPOVEEDOR POR VIAJE';
                break; */

            default:
                return redirect()->back()->with('status','No se encontraron datos para la consulta.');
                break;
        }
        return [$data, $encabezado];
    }


    public function consultaCubicajeViaje($viaje)
    {
        $cubicajes = Cubicaje::join('entradas_madera_maderas', 'cubicajes.entrada_madera_id', '=', 'entradas_madera_maderas.id')
                            ->join('entrada_maderas', 'entrada_maderas.id', '=','entradas_madera_maderas.entrada_madera_id')
                            ->join('proveedores', 'proveedores.id', '=','entrada_maderas.proveedor_id')
                            ->join('maderas', 'maderas.id', '=', 'entradas_madera_maderas.madera_id')
                            ->join('tipo_maderas', 'tipo_maderas.id' , '=', 'maderas.tipo_madera_id')
                            ->where('entradas_madera_maderas.id', (integer)$viaje)
                            ->orderBy('paqueta', 'asc')
                            ->orderBy('bloque', 'asc')
                            ->get([
                                'proveedores.nombre',
                                'cubicajes.paqueta',
                                'cubicajes.bloque',
                                'cubicajes.largo',
                                'cubicajes.alto',
                                'cubicajes.ancho',
                                'cubicajes.created_at',
                                'cubicajes.pulgadas_cuadradas',
                                'entradas_madera_maderas.entrada_madera_id',
                                'tipo_maderas.descripcion',

                            ]);
        return $cubicajes;

    }

}

