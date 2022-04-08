<?php

namespace App\Http\Controllers;

use App\Models\EntradaMadera;
use App\Models\Madera;
use App\Models\Proveedor;
use App\Http\Requests\StoreEntraMaderaRequest;
use Illuminate\Http\Request;
use App\Repositories\RegistroEntradaMadera;
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
        $entradas = EntradaMadera::whereBetween('created_at',
                            [date('Y-m-d', strtotime('-1 month')), date('Y-m-d', strtotime('+1 day'))])
                            ->get();
        
        $proveedores = Proveedor::select('id', 'nombre')->get();
        $maderas = Madera::select('id', 'nombre')->get();
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
       } else{
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
        return view('modulos.administrativo.entradas-madera.show',
                     compact('entrada', 'proveedores', 'maderas'));
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
        $entrada = EntradaMadera::where(trim('acto_administrativo'),trim( $request->acto));
        if($entrada->count() > 0)
        {
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
                    ->select('entradas_madera_maderas.id',
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
}
