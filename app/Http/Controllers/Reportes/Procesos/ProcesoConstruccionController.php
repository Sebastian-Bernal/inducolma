<?php

namespace App\Http\Controllers\Reportes\Procesos;

use App\Exports\EstadosProceso;
use App\Exports\EventosProceso;
use App\Exports\ItemsEnsamblados;
use App\Exports\MaquinasEnsambleProductos;
use App\Exports\OrdenesFecha;
use App\Exports\ProcesoItemFecha;
use App\Exports\ProcesosFecha;
use App\Http\Controllers\Controller;
use App\Models\DisenoProductoFinal;
use App\Models\Item;
use App\Models\Maquina;
use App\Repositories\Reportes\ConsultaProcesos;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ProcesoConstruccionController extends Controller
{
    protected $procesos;
    function __construct( ConsultaProcesos $procesos){
        $this->procesos = $procesos;
    }


    /**
     * obtiene las maquinas de acuerdo al valor enviado por request
     * de tipo diferente a corte Ensamble
     *
     * @param Request $request
     *
     * @return Response
     */

    public function getMaquinas(Request $request)
    {
        $empleados = Maquina::where('corte', '!=', 'ENSAMBLE')
                        ->where('maquina', 'like', '%'.strtoupper($request->descripcion).'%')
                        ->get(['id','maquina as text']);
        $empleados->toJson();
        return response()->json($empleados);
    }

    /**
     * obtiene las maquinas de acuerdo al valor enviado por request
     * de tipo diferente a corte Ensamble
     *
     * @param Request $request
     *
     * @return Response
     */

    public function getMaquinasEnsamble(Request $request)
    {
        $empleados = Maquina::where('corte', 'ENSAMBLE')
                        ->where('maquina', 'like', '%'.strtoupper($request->descripcion).'%')
                        ->get(['id','maquina as text']);
        $empleados->toJson();
        return response()->json($empleados);
    }

    /**
     * obtiene las maquinas de acuerdo al valor enviado por request
     * de tipo diferente a corte Ensamble
     *
     * @param Request $request
     *
     * @return Response
     */

    public function getProductos(Request $request)
    {
        $empleados = DisenoProductoFinal::where('descripcion', 'like', '%'.strtoupper($request->descripcion).'%')
                                        ->get(['id','descripcion as text']);
        $empleados->toJson();
        return response()->json($empleados);
    }

    /**
     * obtiene los items de acuerdo al valor enviado por request
     *
     * @param Request $request
     *
     * @return Response
     */

    public function getItems(Request $request)
    {
        $empleados = Item::where('descripcion', 'like', '%'.strtoupper($request->descripcion).'%')
                        ->orWhere('id', 'like', '%'.$request->descripcion.'%')
                        //->withTrashed()
                        ->get(['id','descripcion as text']);
        $empleados->toJson();
        return response()->json($empleados);
    }

    /**
     * Muestra vista de los datos encontrados, o genera los reportes de pdf, excel, csv
     *
     *
     */

    public function reportesProcesos(Request $request)
    {
        //return $request->all();
        $desde = $request->procesoDesde;
        $hasta = $request->procesoHasta;
        $tipoReporte = $request->tipoReporteConstruccion;
        $maquina = $request->maquina;
        $item = $request->item;
        $generar = $request->generar;
        $datos = $this->procesos->consultaDatos($request);
        $encabezado = $datos[1];
        $data = json_decode(json_encode($datos[0]));
        if (count($data) == 0 ) {
            return redirect()
                    ->back()
                    ->with('status','No se encontraron datos en los filtros seleccionados.');
        } else {
            if ($generar == '1') {

                $pdf = Pdf::loadView($datos[3], compact('data', 'encabezado'));
                $pdf->setPaper('a4');
                return $pdf->stream($encabezado.'pdf');

            } elseif ($generar == '2') {
                switch ($tipoReporte) {
                    case '1':
                        return Excel::download(new ProcesosFecha($data), "$encabezado-$desde-$hasta.xlsx");
                        break;
                    case '2':
                        return Excel::download(new ProcesoItemFecha($data), "$encabezado-$desde-$hasta.xlsx");
                        break;
                    case '3':
                        return Excel::download(new EventosProceso($data), "$encabezado-$desde-$hasta.xlsx");
                        break;
                    case '4':
                        return Excel::download(new EstadosProceso($data), "$encabezado-$desde-$hasta.xlsx");
                        break;
                    case '5':
                        return Excel::download(new OrdenesFecha($data), "$encabezado-$desde-$hasta.xlsx");
                        break;
                    case '6' :
                        return Excel::download(new OrdenesFecha($data), "$encabezado-$desde-$hasta.xlsx");
                        break;
                    case '7' :
                        return Excel::download(new ItemsEnsamblados($data), "$encabezado-$desde-$hasta.xlsx");
                        break;
                    case '8' :
                        return Excel::download(new MaquinasEnsambleProductos($data), "$encabezado-$desde-$hasta.xlsx");
                        break;
                }

            }elseif ($generar == '3') {
                switch ($tipoReporte) {
                    case '1':
                        return Excel::download(new ProcesosFecha($data), "$encabezado-$desde-$hasta.csv");
                        break;
                    case '2':
                        return Excel::download(new ProcesoItemFecha($data), "$encabezado-$desde-$hasta.csv");
                        break;
                    case '3':
                        return Excel::download(new EventosProceso($data), "$encabezado-$desde-$hasta.csv");
                        break;
                    case '4':
                        return Excel::download(new EstadosProceso($data), "$encabezado-$desde-$hasta.csv");
                        break;
                    case '5':
                        return Excel::download(new OrdenesFecha($data), "$encabezado-$desde-$hasta.csv");
                        break;
                    case '6' :
                        return Excel::download(new OrdenesFecha($data), "$encabezado-$desde-$hasta.csv");
                        break;
                    case '7' :
                        return Excel::download(new ItemsEnsamblados($data), "$encabezado-$desde-$hasta.csv");
                        break;
                    case '8' :
                        return Excel::download(new MaquinasEnsambleProductos($data), "$encabezado-$desde-$hasta.csv");
                        break;
                }

            }else{
                return view($datos[2],
                compact('data', 'encabezado', 'desde', 'hasta', 'tipoReporte','maquina', 'item'));
            }
        }
    }
}
