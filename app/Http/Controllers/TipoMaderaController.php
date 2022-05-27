<?php

namespace App\Http\Controllers;

use App\Models\TipoMadera;
use App\Http\Requests\StoreTipoMaderaRequest;
use App\Http\Requests\UpdateTipoMaderaRequest;

class TipoMaderaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tiposMadera = TipoMadera::all();
        return view('modulos.administrativo.tipo_madera.index', compact('tiposMadera'));
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
     * @param  \App\Http\Requests\StoreTipoMaderaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTipoMaderaRequest $request)
    {
        $tipoMadera = new TipoMadera();
        $tipoMadera->descripcion = strtoupper($request->descripcion);
        $tipoMadera->user_id = auth()->user()->id;
        $tipoMadera->save();
        return redirect()->route('tipos-maderas.index')->with('status', "El tipo de madera $tipoMadera->descripcion ha sido creado correctamente");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TipoMadera  $tipoMadera
     * @return \Illuminate\Http\Response
     */
    public function show(TipoMadera $tipoMadera)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TipoMadera  $tipoMadera
     * @return \Illuminate\Http\Response
     */
    public function edit(TipoMadera $tipoMadera)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTipoMaderaRequest  $request
     * @param  \App\Models\TipoMadera  $tipoMadera
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTipoMaderaRequest $request, TipoMadera $tipoMadera)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TipoMadera  $tipoMadera
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipoMadera $tipoMadera)
    {
        //
    }
}
