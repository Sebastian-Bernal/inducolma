<?php

namespace App\Http\Controllers;

use App\Models\Operacion;
use App\Http\Requests\StoreOperacionRequest;
use App\Http\Requests\UpdateOperacionRequest;

class OperacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('admin');
        return view('modulos.administrativo.costos.operaciones', [
            'operaciones' => Operacion::all()
        ]);
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
     * @param  \App\Http\Requests\StoreOperacionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOperacionRequest $request)
    {
        $this->authorize('admin');
        $operacion = new Operacion();
        $operacion->operacion = strtoupper($request->operacion);
        $operacion->save();
        return redirect()->route('operaciones.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Operacion  $operacion
     * @return \Illuminate\Http\Response
     */
    public function show(Operacion $operacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Operacion  $operacion
     * @return \Illuminate\Http\Response
     */
    public function edit(Operacion $operacion)
    {
        $this->authorize('admin');
        $operacion = Operacion::findOrFail($operacion->id);
        return view('modulos.administrativo.costos.operaciones-edit',[
            'operacion'   => $operacion,
                      
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOperacionRequest  $request
     * @param  \App\Models\Operacion  $operacion
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOperacionRequest $request, Operacion $operacion)
    {
        $this->authorize('admin');
        $operacion = Operacion::findOrFail($operacion->id);
        $operacion->operacion = strtoupper($request->operacion);
        $operacion->save();
        return redirect()->route('operaciones.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Operacion  $operacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Operacion $operacion)
    {
        $this->authorize('admin');
        $operacion->delete();
        return redirect()->route('operaciones.index');
    }
}
