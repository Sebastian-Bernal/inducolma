<?php

namespace App\Http\Controllers\Reportes\Administrativos;

use App\Exports\CubicajesExport;
use App\Http\Controllers\Controller;
use App\Models\EntradaMadera;
use App\Repositories\Reportes\ConsultasCubicajes;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReporteCubicajesController extends Controller
{

    protected $consultaCubicaje;

    public function __construct(ConsultasCubicajes $consultasCubicajes){
        $this->consultaCubicaje = $consultasCubicajes;
    }


    /**
     * get entradas maderas devuelve un json de la informacion
     *
     * @return json
     */
    public function getEntradas()
    {
        $entradas = EntradaMadera::orderBy('id')->get(['id', 'id as text']);
        $entradas->toJson();
        return response()->json($entradas);
    }

    /**
     * muestra la vista de los datos encontrados
     */
    public function reporteCubicajes(Request  $request)
    {

        $desde = $request->cubicajeDesde;
        $hasta = $request->cubicajeHasta;
        $tipoReporte = $request->tipoReporteCubicaje;
        $especifico = $request->filtroCubiaje1;
        $generar = $request->generar;
        $datos = $this->consultaCubicaje->consultaDatos($request);
        $encabezado = $datos[1];
        $data = json_decode(json_encode($datos[0]));

        if (count($data) == 0 ) {
            return redirect()
                    ->back()
                    ->with('status','No se encontraron datos de cubicajes en los filtros seleccionados.');
        } else {
            if ($generar == '1') {

                $pdf = Pdf::loadView('modulos.reportes.administrativos.cubicajes.pdf-cubicajes-viaje', compact('data', 'encabezado'));
                $pdf->setPaper('a4');
                return $pdf->stream($encabezado.'pdf');

            } elseif ($generar == '2') {
                return Excel::download(new CubicajesExport($data), "$encabezado-$desde-$hasta.xlsx");

            }elseif ($generar == '3') {
                return Excel::download(new CubicajesExport($data), "$encabezado-$desde-$hasta.csv");

            }else{
                return view('modulos.reportes.administrativos.cubicajes.index-cubicajes',
                compact('data', 'encabezado', 'desde', 'hasta', 'tipoReporte','especifico'));
            }
        }
    }
}
