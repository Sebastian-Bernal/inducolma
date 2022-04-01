<?php

namespace App\Http\Controllers;

use App\Models\EntradaMadera;
use App\Models\Madera;
use App\Models\Proveedor;
use App\Http\Requests\StoreEntraMaderaRequest;
use Illuminate\Http\Request;
use App\Repositories\RegistroEntradaMadera;

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
        $entradas = EntradaMadera::all();
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
       // return $request->all();

        return $this->registroEntradaMadera->guardar($request);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EntradaMadera  $entradaMadera
     * @return \Illuminate\Http\Response
     */
    public function show(EntradaMadera $entradaMadera)
    {
        //
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
}
