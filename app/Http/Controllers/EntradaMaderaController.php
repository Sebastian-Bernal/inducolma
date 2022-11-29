<?php

namespace App\Http\Controllers;

use App\Models\EntradaMadera;
use App\Models\Madera;
use App\Models\Proveedor;
use App\Http\Requests\StoreEntraMaderaRequest;
use App\Models\EntradasMaderaMaderas;
use Illuminate\Http\Request;
use App\Repositories\RegistroEntradaMadera;
use Doctrine\DBAL\Schema\View;
use Illuminate\Support\Facades\DB;

class EntradaMaderaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $registroEntradaMadera;

    public function __construct(RegistroEntradaMadera $registroEntradaMadera)
    {
        $this->registroEntradaMadera = $registroEntradaMadera;
    }

    public function index()
    {
        $entradas = EntradaMadera::whereBetween(
            'created_at',
            [date('Y-m-d', strtotime('-1 month')), date('Y-m-d', strtotime('+1 day'))]
        )
            ->where('user_id', auth()->user()->id)
            ->get();

        $proveedores = Proveedor::select('id', 'nombre')->get();
        $maderas = Madera::select('id', 'nombre_cientifico')->get();
        return view('modulos.administrativo.entradas-madera.index', compact('entradas', 'proveedores', 'maderas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request->all();

        if ($request->entrada[2] == 0) {
            return $this->registroEntradaMadera->guardar($request);
        } else {
            return $this->registroEntradaMadera->actualizar($request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EntradaMadera  $entradaMadera
     * @return \Illuminate\Http\Response
     */
    public function show(EntradaMadera $entrada)
    {

        $entrada = EntradaMadera::find($entrada->id)->load('proveedor', 'maderas', 'entradas_madera_maderas');
        //return $entrada;
        $proveedores = Proveedor::select('id', 'nombre')->get();
        $maderas = Madera::select('id', 'nombre')->get();
        return view(
            'modulos.administrativo.entradas-madera.show',
            compact('entrada', 'proveedores', 'maderas')
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EntradaMadera  $entradaMadera
     * @return \Illuminate\Http\Response
     */
    public function edit(EntradaMadera $entradaMadera)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EntradaMadera  $entradaMadera
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EntradaMadera $entradaMadera)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EntradaMadera  $entradaMadera
     * @return \Illuminate\Http\Response
     */
    public function destroy(EntradaMadera $entradaMadera)
    {
        //
    }

    /**
     * verifica si el acto administrativo ya fue registrado
     */
    public function verificarRegistro(Request $request)
    {
        //return $request->all();
        $entrada = EntradaMadera::where(trim('acto_administrativo'), trim($request->acto));
        if ($entrada->count() > 0) {
            return response()->json(['error' => true]);
        } else {
            return response()->json(['error' => false]);
        }
    }

    // retorna un json con los datos de la ultima entrada
    public function ultimaEntrada(Request $request)
    {
        $ultimaEntrada = EntradaMadera::findOrFail($request->id)
            ->load('proveedor');
        $maderas = DB::table('entradas_madera_maderas')
            ->join('maderas', 'entradas_madera_maderas.madera_id', '=', 'maderas.id')
            ->select(
                'entradas_madera_maderas.id',
                'maderas.nombre',
                'entradas_madera_maderas.condicion_madera',
                'entradas_madera_maderas.m3entrada',
                'entradas_madera_maderas.madera_id',
                'entradas_madera_maderas.entrada_madera_id'
            )
            ->where('entrada_madera_id', $request->id)
            ->get();
        return response()->json(compact('ultimaEntrada', 'maderas'));
    }

    // funcion para eliminar una madera de una entrada
    public function eliminarMadera(Request $request)
    {
        $madera = EntradasMaderaMaderas::findOrFail($request->id);
        $madera->delete();
        return response()->json(['error' => false]);
    }

    /**
     * muestra el listado inicial de las maderas con costo cero
     *
     * @return void
     */
    public function indexEntradas()
    {
        $entradas = EntradasMaderaMaderas::where('costo', 0)->with('entrada_madera')->get();

        return view('modulos.administrativo.entradas-madera.index-maderas', compact('entradas'));
    }

    /**
     * muestra el formulario de actualiacion de costo de la madera
     */
    public function editEntrada(EntradasMaderaMaderas $entrada)
    {
        return view('modulos.administrativo.entradas-madera.edit-madera', compact('entrada'));
    }

    public function updateEntrada(Request $request ,EntradasMaderaMaderas $entrada )
    {
        $costo = 0;
        switch ($request->medida) {
            case 'CENTIMETROS CUBICOS':
                $costo = (float)$request->costo;
                break;
            case 'METRO CUBICO':
                $costo = (float)$request->costo / 1000000;
                break;
            case 'PULGADA CUADRADA POR 3 METROS':
                $costo = (float)$request->costo / 1935.48;
                break;
        }

        $entrada->costo = $costo;
        try {
            $entrada->save();
            return redirect()->route('costo-madera')
            ->with('status', "El precio de compra de la entrada $entrada->id se actualizo correctamente");
        } catch (\Throwable $th) {
            return back()->with('status','no se pudo actualizar el precio de compra de la entrada');
        }
    }


    public function showEntradas()
    {
        $entradas = EntradasMaderaMaderas::orderBy('id', 'desc')->take(50)->get();
        return view('modulos.administrativo.entradas-madera.show-entradas', compact('entradas'));
    }

}
