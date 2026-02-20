<?php

namespace App\Http\Controllers\Reportes\Administrativos;

use App\Exports\CalificacionesViajeExport;
use App\Exports\CubicajesExport;
use App\Exports\TransformacionesExport;
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
   /* public function reporteCubicajes(Request  $request)
    {

        $desde = $request->cubicajeDesde;
        $hasta = $request->cubicajeHasta;
        $proveedor = $request->filtroCubiaje2;
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

                $pdf = Pdf::loadView($datos[3], compact('data', 'encabezado'));
                $pdf->setPaper('a4');
                return $pdf->stream($encabezado.'pdf');

            } elseif ($generar == '2') {
                switch ($tipoReporte) {
                    case '1':
                        return Excel::download(new CubicajesExport($data), "$encabezado-$desde-$hasta.xlsx");
                        break;
                    case '2':
                        return Excel::download(new TransformacionesExport($data), "$encabezado-$desde-$hasta.xlsx");
                        break;
                    case '3':
                        return Excel::download(new CalificacionesViajeExport($data), "$encabezado-$desde-$hasta.xlsx");
                        break;
                    case '4':
                        return Excel::download(new CalificacionesViajeExport($data), "$encabezado-$desde-$hasta.xlsx");
                        break;
                    default:
                        # code...
                        break;
                }

            }elseif ($generar == '3') {
                switch ($tipoReporte) {
                    case '1':
                        return Excel::download(new CubicajesExport($data), "$encabezado-$desde-$hasta.csv");
                        break;
                    case '2':
                        return Excel::download(new TransformacionesExport($data), "$encabezado-$desde-$hasta.csv");
                        break;
                    case '3':
                        return Excel::download(new CalificacionesViajeExport($data), "$encabezado-$desde-$hasta.csv");
                        break;
                    case '4':
                        return Excel::download(new CalificacionesViajeExport($data), "$encabezado-$desde-$hasta.csv");
                        break;
                    default:
                        # code...
                        break;
                }

            }else{
               // return view($datos[2],
               // compact('data', 'encabezado', 'desde', 'hasta', 'tipoReporte','especifico', 'proveedor'));
               return redirect()->back()
            ->with('reporte_html', view($datos[2], compact('data', 'encabezado'))->render())
            ->with('mostrar_reporte', true);
            }
        }
    }*/
        public function reporteCubicajes(Request $request)
    {
        // Variables recibidas
        $desde       = $request->cubicajeDesde;
        $hasta       = $request->cubicajeHasta;
        $proveedor   = $request->filtroCubiaje2;
        $tipoReporte = $request->tipoReporteCubicaje; // <-- asegurarse de definirlo
        $especifico  = $request->filtroCubiaje1;
        $generar     = $request->generar ?? null;

        // Ejecuta la consulta
        $datos = $this->consultaCubicaje->consultaDatos($request);

        if (count($datos[0]) == 0) {
            return redirect()->back()
                ->with('status','No se encontraron datos...');
        }

        $data = $datos[0];
        $encabezado = $datos[1] ?? 'Reporte';

        // Salidas especiales
        if ($generar == '1') {
            $pdf = Pdf::loadView($datos[3], compact('data', 'encabezado'));
            $pdf->setPaper('a4');
            return $pdf->stream($encabezado.'.pdf');
        } elseif ($generar == '2') {
            return Excel::download(new CubicajesExport($data),
                "$encabezado-$desde-$hasta.xlsx");
        } elseif ($generar == '3') {
            return Excel::download(new CubicajesExport($data),
                "$encabezado-$desde-$hasta.csv");
        }

        // Retornar la vista HTML con todas las variables necesarias
        return view($datos[2], compact(
            'data',
            'encabezado',
            'tipoReporte',
            'desde',
            'hasta',
            'proveedor',
            'especifico'
        ));
    }
}
