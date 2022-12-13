<?php

namespace App\Http\Controllers\Reportes\Administrativos;

use App\Exports\EventosPersonal;
use App\Exports\HorasTrabajadas;
use App\Exports\HorasTrabajadasEmpleado;
use App\Exports\IngresoTerceros;
use App\Exports\TurnoPersonal;
use App\Http\Controllers\Controller;
use App\Models\Contratista;
use App\Models\User;
use App\Repositories\Reportes\ConsultasPersonal;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportePersonalController extends Controller
{
    protected $personal;

    public function __construct( ConsultasPersonal $personal)
    {
        $this->personal = $personal;
    }

    /**
     * muestra la vista de los datos encontrados
     */
    public function reportePersonal(Request  $request)
    {

        $desde = $request->personalDesde;
        $hasta = $request->personalHasta;
        $proveedor = $request->filtroCubiaje2;
        $tipoReporte = $request->tipoReportePersonal;
        $especifico = $request->filtroPersonal;
        $generar = $request->generar;
        $datos = $this->personal->consultaDatos($request);
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
                        return Excel::download(new TurnoPersonal($data), "$encabezado-$desde-$hasta.xlsx");
                        break;
                    case '2':
                        return Excel::download(new EventosPersonal($data), "$encabezado-$desde-$hasta.xlsx");
                        break;
                    case '3':
                        return Excel::download(new HorasTrabajadas($data), "$encabezado-$desde-$hasta.xlsx");
                        break;
                    case '4':
                        return Excel::download(new HorasTrabajadasEmpleado($data), "$encabezado-$desde-$hasta.xlsx");
                        break;
                    case '5':
                        return Excel::download(new IngresoTerceros($data), "$encabezado-$desde-$hasta.xlsx");
                        break;
                }

            }elseif ($generar == '3') {
                switch ($tipoReporte) {
                    case '1':
                        return Excel::download(new TurnoPersonal($data), "$encabezado-$desde-$hasta.csv");
                        break;
                    case '2':
                        return Excel::download(new EventosPersonal($data), "$encabezado-$desde-$hasta.csv");
                        break;
                    case '3':
                        return Excel::download(new HorasTrabajadas($data), "$encabezado-$desde-$hasta.csv");
                        break;
                    case '4':
                        return Excel::download(new HorasTrabajadasEmpleado($data), "$encabezado-$desde-$hasta.csv");
                        break;
                    case '5':
                        return Excel::download(new IngresoTerceros($data), "$encabezado-$desde-$hasta.csv");
                        break;

                }

            }else{
                return view($datos[2],
                compact('data', 'encabezado', 'desde', 'hasta', 'tipoReporte','especifico', 'proveedor'));
            }
        }
    }

    /**
     * retorna JSON con los datos de los empleados. id, nombre
     * @param Request $request [palabra de busqueda]
     * @return Response JSON [datos del proveedor id, nombre]
     *
     */
    public function getEmpleados(Request $request)
    {
        $empleados = User::where('name', 'like', '%'.strtoupper($request->descripcion).'%')
                        ->whereBetween('rol_id', [1,2])
                        ->get(['id','name as text']);
        $empleados->toJson();
        return response()->json($empleados);
    }


    public function getTerceros(Request $request)
    {
        $empleados = Contratista::where('cedula', 'like', '%'.strtoupper($request->descripcion).'%')
                        ->withTrashed()
                        ->get(['id','cedula as text']);
        $empleados->toJson();
        return response()->json($empleados);
    }

}
