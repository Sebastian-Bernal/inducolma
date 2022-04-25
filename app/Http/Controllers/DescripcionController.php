<?php

namespace App\Http\Controllers;

use App\Models\Descripcion;
use App\Http\Requests\StoreDescripcionRequest;
use App\Http\Requests\UpdateDescripcionRequest;
use App\Models\Operacion;

class DescripcionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('admin');
        $descripciones = Descripcion::all();
        $operaciones = Operacion::all();
        return view('modulos.administrativo.costos.descripciones', compact(['descripciones','operaciones']));
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
     * @param  \App\Http\Requests\StoreDescripcionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDescripcionRequest $request)
    {
        $this->authorize('admin');
        $descripcion = new Descripcion();
        $descripcion->descripcion = $request->descripcion;
        $descripcion->operacion_id = $request->idOperacion;
        $descripcion->save();
        return redirect()->route('descripciones.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Descripcion  $descripcion
     * @return \Illuminate\Http\Response
     */
    public function show(Descripcion $descripcion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Descripcion  $descripcion
     * @return \Illuminate\Http\Response
     */
    public function edit(Descripcion $descripcion)
    {
        $this->authorize('admin');
        $descripcion = Descripcion::findOrFail($descripcion->id);
        $operaciones = Operacion::all();
        return view('modulos.administrativo.costos.descripciones-edit',[
            'descripcion'   => $descripcion,
            'operaciones'    => $operaciones
                      
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDescripcionRequest  $request
     * @param  \App\Models\Descripcion  $descripcion
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDescripcionRequest $request, Descripcion $descripcion)
    {
        $this->authorize('admin');
        $descripcion = Descripcion::findOrFail($descripcion->id);
        $descripcion->descripcion = $request->descripcion;
        $descripcion->operacion_id = $request->idOperacion;
        $descripcion->save();
        return redirect()->route('descripciones.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Descripcion  $descripcion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Descripcion $descripcion)
    {
        $this->authorize('admin');
        $descripcion = Descripcion::findOrFail($descripcion->id);
        $descripcion->delete();
        return redirect()->route('descripciones.index');
    }
}
