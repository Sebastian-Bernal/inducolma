<?php

namespace App\Http\Controllers;

use App\Models\EntradaMadera;
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
        //return $request->all();
        //$data = new Collection();
        $desde = $request->desdeIm;
        $hasta = $request->hastaIm;
        switch ($request->tipoReporte) {
            case '1':
                $data = $this->reporte->densidad($desde, $hasta, 'ALTA DENSIDAD');
                break;
            case '2':
                $data = $this->reporte->densidad($desde, $hasta, 'BAJA DENSIDAD');
                break;
            case '3':
                $data = $this->reporte->proveedor($desde, $hasta);
                break;
            case '4':
                $data = $this->reporte->tipoMadera($desde, $hasta);
                break;
            case '5':
                $data = $this->reporte->entidadVigilante($desde, $hasta, 'ICA');
                break;
            case '6':
                $data = $this->reporte->entidadVigilante($desde, $hasta, 'CVC');
                break;
            default:
                return  redirect()->back('status','ningun tipo de reporte seleccionado');
                break;
        }


        //return $data;
        $pdf = Pdf::loadView('modulos.reportes.administrativos.ingreso-madera-pdf', compact('data'));
        return $pdf->stream('pdf_file.pdf');
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
