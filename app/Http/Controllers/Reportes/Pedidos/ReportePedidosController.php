<?php

namespace App\Http\Controllers\Reportes\Pedidos;

use App\Exports\PedidosCliente;
use App\Exports\ProcesosPedido;
use App\Exports\UsuariosPedido;
use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Repositories\Reportes\ConsultaPedidos;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportePedidosController extends Controller
{

    protected $pedidos;

    public function __construct( ConsultaPedidos $pedidos)
    {
        $this->pedidos = $pedidos;
    }


    public function getClientes(Request $request)
    {
        $empleados = Cliente::where('nombre', 'like', '%'.strtoupper($request->descripcion).'%')
                        ->orWhere('nit', 'like', '%'.$request->descripcion.'%')
                        ->withTrashed()
                        ->get(['id','nombre as text']);
        $empleados->toJson();
        return response()->json($empleados);
    }

    /**
     * muestra la vista de los datos encontrados
     */
    public function reportePedidos(Request  $request)
    {
        //return $request->all();
        $desde = $request->pedidoDesde;
        $hasta = $request->pedidoHasta;

        $tipoReporte = $request->tipoReportePedidos;
        $especifico = $request->filtroPedido1;
        $numeroP = $request->nPedido;

        $generar = $request->generar;
        $datos = $this->pedidos->consultaDatos($request);
        $encabezado = $datos[1];
        $data = json_decode(json_encode($datos[0]));
        $cliente = $datos[4];
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
                    case '6':
                        return Excel::download(new ProcesosPedido($data), "$encabezado-$desde-$hasta.xlsx");
                        break;
                    case '7':
                        return Excel::download(new UsuariosPedido($data), "$encabezado-$desde-$hasta.xlsx");
                        break;
                    default :
                        return Excel::download(new PedidosCliente($data), "$encabezado-$desde-$hasta.xlsx");
                        break;
                }

            }elseif ($generar == '3') {
                switch ($tipoReporte) {
                    case '6':
                        return Excel::download(new ProcesosPedido($data), "$encabezado-$desde-$hasta.csv");
                        break;
                    case '7':
                        return Excel::download(new UsuariosPedido($data), "$encabezado-$desde-$hasta.csv");
                        break;
                    default :
                        return Excel::download(new PedidosCliente($data), "$encabezado-$desde-$hasta.csv");
                        break;
                }

            }else{
                return view($datos[2],
                compact('data', 'encabezado', 'desde', 'hasta', 'tipoReporte','especifico', 'cliente'));
            }
        }
    }
}
