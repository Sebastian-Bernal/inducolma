<?php

namespace App\Http\Controllers;

use App\Models\EntradaMadera;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportePdfController extends Controller
{
    /**
     * funcion ingresoMaderas, genera el reporte de ingreso
     * @param $desde [date]
     * @param $hasta [date]
     * @return Pdf
     */
    public function ingresoMaderas(Request $request)
    {
       // return $request;
        $data = EntradaMadera::all();

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
