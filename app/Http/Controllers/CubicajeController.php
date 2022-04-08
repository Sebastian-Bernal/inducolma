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
        $cubicajes = Cubicaje::all();
        return view('modulos.operaciones.cubicaje.index', compact('cubicajes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
    
        $entrada = EntradaMadera::find($request->entrada);
        if($entrada == ''){            
            return redirect()->route('cubicaje.index')->with('status', ' no se encontro ninguna entrada con ese id');
        } else {
           $entrada = EntradaMadera::find($request->entrada)->load('entradas_madera_maderas');
            return view('modulos.operaciones.cubicaje.create', compact('entrada'));
        }

        //return $entrada;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
