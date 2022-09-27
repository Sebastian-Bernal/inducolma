<?php

namespace App\Http\Controllers;

use App\Exports\EntradaMaderaExport;
use App\Models\EntradaMadera;
use App\Models\Proveedor;
use App\Models\TipoMadera;
use App\Repositories\ConsultasReportes;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReporteController extends Controller
{
    protected $reporte;
    public function __construct(ConsultasReportes $reporte)
    {
        $this->reporte = $reporte;
    }
    /**
     * funcion ingresoMaderas, genera el reporte de ingreso
     * @param $desde [date]
     * @param $hasta [date]
     * @return Pdf
     */
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
                return $pdf->download($encabezado.'-'.$desde.'-'.$hasta.'.pdf');

            } elseif ($generar == '2') {
                return Excel::download(new EntradaMaderaExport($data), "$encabezado-$desde-$hasta.xlsx");

            }elseif ($generar == '3') {
                return Excel::download(new EntradaMaderaExport($data), "$encabezado-$desde-$hasta.csv");

            }else{
                return view('modulos.reportes.administrativos.ingreso-madera',
                compact('data', 'encabezado', 'desde', 'hasta', 'tipoReporte','especifico'));
            }
        }

    }

    /**
     * retorna JSON con los datos de los proveedores. id, nombre
     * @param Request $request [palabra de busqueda]
     * @return Response JSON [datos del proveedor id, nombre]
     *
     */
    public function getProveedores(Request $request)
    {
        $disenos = Proveedor::where('nombre', 'like', '%'.strtoupper($request->descripcion).'%')
        ->get(['id','nombre as text']);
        $disenos->toJson();
        return response()->json($disenos);
    }

    /**
     * busca tipo de madera y retorna JSON con los datos id, descripcion
     * @param Request $request [palabra de busqueda]
     * @response Response JSON [datos del tipo de madera id, descripcion]
     */
    public function getTipoMadera(Request $request)
    {
        $disenos = TipoMadera::where('descripcion', 'like', '%'.strtoupper($request->descripcion).'%')
        ->get(['id','descripcion as text']);
        $disenos->toJson();
        return response()->json($disenos);
    }

    /**
     * funcion ingresoCubicaje
     * @param $desde [date]
     * @param $hasta [date]
     * @return Pdf
     */
    public function ingresoCubicajes()
    {
        $data = EntradaMadera::all();

        $pdf = Pdf::loadView('modulos.reportes.administrativos.ingreso-madera-pdf', compact('data'));
        return $pdf->stream('pdf_file.pdf');
    }

    /**
     * funcion ingresoPersonal
     * @param $desde [date]
     * @param $hasta [date]
     * @return Pdf
     */

    public function ingresoPersonal()
    {
        $data = EntradaMadera::all();

        $pdf = Pdf::loadView('modulos.reportes.administrativos.ingreso-madera-pdf', compact('data'));
        return $pdf->stream('pdf_file.pdf');
    }

    /**
     * funcion horasTrabajoPersonal
     * @param $desde [date]
     * @param $hasta [date]
     * @return Pdf
     */

    public function horasTrabajoPersonal()
    {
        $data = EntradaMadera::all();

        $pdf = Pdf::loadView('modulos.reportes.administrativos.ingreso-madera-pdf', compact('data'));
        return $pdf->stream('pdf_file.pdf');
    }

    /**
     * funcion inventarioMaderasAlmacen
     * @param $desde [date]
     * @param $hasta [date]
     * @return Pdf
     */

    public function inventarioMaderasAlmacen()
    {
        $data = EntradaMadera::all();

        $pdf = Pdf::loadView('modulos.reportes.administrativos.ingreso-madera-pdf', compact('data'));
        return $pdf->stream('pdf_file.pdf');
    }

    /**
     * funcion pedidosMes
     * @param $desde [date]
     * @param $hasta [date]
     * @return Pdf
     */

    public function pedidosMes()
    {
        $data = EntradaMadera::all();

        $pdf = Pdf::loadView('modulos.reportes.administrativos.ingreso-madera-pdf', compact('data'));
        return $pdf->stream('pdf_file.pdf');
    }


}
