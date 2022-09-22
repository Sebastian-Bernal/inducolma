<?php

namespace App\Http\Controllers;

use App\Models\EntradaMadera;
use App\Models\Proveedor;
use App\Models\TipoMadera;
use App\Repositories\ConsultasReportes;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;


class ReportePdfController extends Controller
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
        switch ($request->tipoReporte) {
            case '1':
                $data = $this->reporte->consulta($desde, $hasta,'maderas.densidad' ,'ALTA DENSIDAD');
                $encabezado = 'REPORTE MADERA DE ALTA DENSIDAD';
                break;
            case '2':
                $data = $this->reporte->consulta($desde, $hasta, 'maderas.densidad' , 'BAJA DENSIDAD');
                $encabezado = 'REPORTE MADERA DE BAJA DENSIDAD';
                break;
            case '3':
                $data = $this->reporte->consulta($desde, $hasta,'proveedores.id',$request->especifico);
                $encabezado = 'REPORTE MADERAS POR PROVEEDOR';
                break;
            case '4':
                $data = $this->reporte->consulta($desde, $hasta, 'tipo_maderas.id', $request->especifico);
                $encabezado = 'REPORTE MADERA POR TIPO DE MADERA';
                break;
            case '5':
                $data = $this->reporte->consulta($desde, $hasta, 'entidad_vigilante', 'ICA');
                $encabezado = 'REPORTE MADERA POR ENTIDAD VIGILANTE ICA';
                break;
            case '6':
                $data = $this->reporte->consulta($desde, $hasta, 'entidad_vigilante', 'CVC');
                $encabezado = 'REPORTE MADERA POR ENTIDAD VIGILANTE CVC';
                break;
            default:
                return redirect()->back()->with('status','No se encontraron datos para la consulta.');
                break;
        }

        $data = json_decode(json_encode($data));
        if (count($data) == 0 ) {
            return redirect()->back()->with('status','No se encontraron datos para la consulta.');
        } else {
            //return $data;
            $pdf = Pdf::loadView('modulos.reportes.administrativos.ingreso-madera-pdf', compact('data', 'encabezado'));
            $pdf->setPaper('a4', 'landscape');
            return $pdf->stream($encabezado.'-'.$desde.'-'.$hasta.'.pdf');
        }

    }

    /**
     * retorna JSON con los datos de los proveedores. id, nombre
     *
     */
    public function getProveedores(Request $request)
    {
        $disenos = Proveedor::where('nombre', 'like', '%'.strtoupper($request->descripcion).'%')
        ->get(['id','nombre as text']);
        $disenos->toJson();
        return response()->json($disenos);
    }

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
