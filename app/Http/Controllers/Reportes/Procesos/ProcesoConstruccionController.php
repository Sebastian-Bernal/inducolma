<?php

namespace App\Http\Controllers\Reportes\Procesos;

use App\Http\Controllers\Controller;
use App\Models\Maquina;
use Illuminate\Http\Request;

class ProcesoConstruccionController extends Controller
{
    /**
     * obtiene las maquinas de acuerdo al valor enviado por request
     *
     * @param Request $request
     *
     * @return Response
     */

    public function getMaquinas(Request $request)
    {
        $empleados = Maquina::where('maquina', 'like', '%'.strtoupper($request->descripcion).'%')
                        ->orWhere('id', 'like', '%'.$request->descripcion.'%')
                        //->withTrashed()
                        ->get(['id','maquina as text']);
        $empleados->toJson();
        return response()->json($empleados);
    }
}
