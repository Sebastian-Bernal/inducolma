<?php

namespace App\Http\Controllers;

use App\Models\InsumosAlmacen;
use Illuminate\Http\Request;
use App\Http\Requests\StoreInsumosRequest;
use App\Http\Requests\UpdateInsumosRequest;

class InsumosAlmacenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $insumos = InsumosAlmacen::all();
        return view('modulos.administrativo.insumos-almacen.index', compact('insumos'));
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
    public function store( StoreInsumosRequest $request)
    {
        $this->authorize('admin');
        $insumo = new InsumosAlmacen();
        $insumo->descripcion = $request->descripcion;
        $insumo->cantidad = $request->cantidad;
        $insumo->precio_unitario = $request->precio_unitario;
        $insumo->estado = 0;
        $insumo->user_id = auth()->user()->id;
        $insumo->save();
        return redirect()->route('insumos-almacen.index')->with('status', "El insumo: $request->descripcion,  se ha creado correctamente");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InsumosAlmacen  $insumosAlmacen
     * @return \Illuminate\Http\Response
     */
    public function show(InsumosAlmacen $insumo_almacen)
    {
        return view('modulos.administrativo.insumos-almacen.show', compact('insumo_almacen'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InsumosAlmacen  $insumosAlmacen
     * @return \Illuminate\Http\Response
     */
    public function edit(InsumosAlmacen $insumosAlmacen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InsumosAlmacen  $insumosAlmacen
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInsumosRequest $request, InsumosAlmacen $insumo_almacen)
    {
        $this->authorize('admin');
        $insumo_almacen->descripcion = $request->descripcion;
        $insumo_almacen->cantidad = $request->cantidad;
        $insumo_almacen->precio_unitario = $request->precio_unitario;
        $insumo_almacen->estado = 0;
        $insumo_almacen->user_id = auth()->user()->id;
        $insumo_almacen->save();
        return redirect()->route('insumos-almacen.index')->with('status', "El insumo: $request->descripcion,  se ha actualizado correctamente");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InsumosAlmacen  $insumosAlmacen
     * @return \Illuminate\Http\Response
     */
    public function destroy(InsumosAlmacen $insumo_almacen)
    {
        $this->authorize('admin');
        $insumo_almacen->delete();
        return response()->json(['success' => 'Insumo eliminado correctamente']);
    }
}
