<?php

namespace App\Http\Controllers;

use App\Models\Cubicaje;
use App\Models\EntradaMadera;
use Illuminate\Http\Request;
use App\Repositories\RegistroCubicajes;

class CubicajeController extends Controller
{

    protected $registroCubicaje;

    public function __construct(RegistroCubicajes $registroCubicaje)
    {
        $this->registroCubicaje = $registroCubicaje;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('cubicaje');
        $cubicajes = Cubicaje::where('created_at', '>=', date('Y-m-d'))
                                ->where('user_id', auth()->user()->id)
                                ->get();
        return view('modulos.operaciones.cubicaje.index', compact('cubicajes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $this->authorize('cubicaje');
        $entrada = EntradaMadera::where('id', (integer)$request->entrada)
                                ->where('estado', 'PENDIENTE')
                            ->get();
        //return $entrada;
        if(count($entrada)==0){
            return redirect()->route('cubicaje.index')->with('status', 'No se encontró ninguna entrada pendiente con ese número');
        } else {
            $entrada = EntradaMadera::find($request->entrada)->load('entradas_madera_maderas');
            return view('modulos.operaciones.cubicaje.create', compact('entrada'));
        }


    }

    /**
     * Muestra el formulario ingreso trozas con la informacion de la entrada
     */
    public function cubicajeTroza(Request $request)
    {
        $entrada = EntradaMadera::find((integer)$request->entrada);
        if ($entrada == null) {
            return back()->with('status', "No se encontro la entrada de madera  $request->entrada");
        }

        $contiene_troza = $entrada->entradas_madera_maderas->contains('condicion_madera', 'TROZA');
        if ($contiene_troza == false) {
            return back()->with('status',"La entrada de madera $request->entrada, no contiene maderas en troza");
        }

        return  view('modulos.operaciones.cubicaje.cubicaje-troza', compact('entrada'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('cubicaje');
        $datos =  $request->cubicajes;
        return $this->registroCubicaje->guardar($datos);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cubicaje  $cubicaje
     * @return \Illuminate\Http\Response
     */
    public function show(Cubicaje $cubicaje)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cubicaje  $cubicaje
     * @return \Illuminate\Http\Response
     */
    public function edit(Cubicaje $cubicaje)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cubicaje  $cubicaje
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cubicaje $cubicaje)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cubicaje  $cubicaje
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cubicaje $cubicaje)
    {
        //
    }
}
