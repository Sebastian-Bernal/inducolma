<?php

namespace App\Http\Controllers\Reportes\Costos;

use App\Http\Controllers\Controller;
use App\Repositories\Reportes\ConsultaCostos;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReporteCostosController extends Controller
{
    protected $costos;

    public function __construct( ConsultaCostos $costos)
    {
        $this->costos = $costos;
    }

    public function reporteCostos(Request $request)
    {
        //return $request->all();

        $desde = $request->costoDesde;
        $hasta = $request->costoHasta;
        $tipoReporte = $request->tipoReporteCosotos;
        $maquina = $request->maquina;
        $pedido = $request->pedido;
        $usuario = $request->usuario;
        $item = $request->item;

        $generar = $request->generar;
        $datos = $this->costos->consultaDatos($request);
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
                   /*  case '6':
                        return Excel::download(new ProcesosPedido($data), "$encabezado-$desde-$hasta.xlsx");
                        break;
                    case '7':
                        return Excel::download(new UsuariosPedido($data), "$encabezado-$desde-$hasta.xlsx");
                        break;
                    default :
                        return Excel::download(new PedidosCliente($data), "$encabezado-$desde-$hasta.xlsx");
                        break; */
                }

            }elseif ($generar == '3') {
                switch ($tipoReporte) {
                   /*  case '6':
                        return Excel::download(new ProcesosPedido($data), "$encabezado-$desde-$hasta.csv");
                        break;
                    case '7':
                        return Excel::download(new UsuariosPedido($data), "$encabezado-$desde-$hasta.csv");
                        break;
                    default :
                        return Excel::download(new PedidosCliente($data), "$encabezado-$desde-$hasta.csv");
                        break; */
                }

            }
            else{
                return view($datos[2],
                compact('data',
                'encabezado',
                'desde',
                'hasta',
                'tipoReporte',
                'maquina',
                'usuario',
                'item',
                'pedido'

            ));
            }
        }
    }
}
