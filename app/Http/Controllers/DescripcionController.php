<?php

namespace App\Http\Controllers;

use App\Models\Operacion;
use App\Models\Descripcion;
use Illuminate\Http\Response;
use App\Http\Requests\StoreDescripcionRequest;
use App\Http\Requests\UpdateDescripcionRequest;

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
        $descripcion->descripcion = strtoupper($request->descripcion);
        $descripcion->operacion_id = $request->idOperacion;
        $descripcion->save();
        return redirect()->route('descripciones.index')->with('status', 'Descripción creada con éxito');
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
        $descripcion->descripcion = strtoupper($request->descripcion);
        $descripcion->operacion_id = $request->idOperacion;
        $descripcion->save();
        return redirect()->route('descripciones.index')->with('status',"La descripción $descripcion->descripcion ha sido actualizada");
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

        if ($descripcion->hasAnyRelatedData(['costos_operacion'])) {
            return back()->withErrors("No se pudo eliminar el recurso porque tiene datos asociados");
        }

        $descripcion->delete();
        return redirect()->route('descripciones.index');
    }
}
