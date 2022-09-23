<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReporteXlsController extends Controller
{
    /*
    public function ingresoMaderas(Request $request)
    {
        $desde = $request->desdeIm;
        $hasta = $request->hastaIm;
        $tipoReporte = $request->tipoReporte;
        $especifico = $request->especifico;
        $generar = $request->generar;
        $datos = $this->reporte->seleccionarReporte($request);
        $encabezado = $datos[1];
        $data = json_decode(json_encode($datos[0]));

        if (count($data) == 0 ) {
            return redirect()
                    ->back()
                    ->with('status','No se encontraron datos para el reporte ingreso de madera en los filtros seleccionados.');
        } else {
            if ($generar == '1') {
                $pdf = Pdf::loadView('modulos.reportes.administrativos.ingreso-madera-pdf', compact('data', 'encabezado'));
                $pdf->setPaper('a4', 'landscape');
                return $pdf->stream($encabezado.'-'.$desde.'-'.$hasta.'.pdf');
            }else{
                return view('modulos.reportes.administrativos.ingreso-madera',
                compact('data', 'encabezado', 'desde', 'hasta', 'tipoReporte','especifico'));
            }
        }*/
}
