<?php

namespace App\Http\Controllers;

use App\Models\DisenoInsumo;
use Illuminate\Http\Request;

class DisenoInsumoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $diseno_insumo = new DisenoInsumo();
        $diseno_insumo->diseno_producto_final_id = $request->insumo['diseno_id'];
        $diseno_insumo->insumo_almacen_id = $request->insumo['insumo_id'];
        $diseno_insumo->cantidad = $request->insumo['cantidad'];
        
        if ($diseno_insumo->save()) {
            return response()->json(['success' => true, 'message' => 'Insumo agregado con éxito', 'insumobd' => $diseno_insumo]);
        } else {
            return response()->json(['success' => false, 'message' => 'Error al agregar el Insumo']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DisenoInsumo  $disenoInsumo
     * @return \Illuminate\Http\Response
     */
    public function show(DisenoInsumo $disenoInsumo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DisenoInsumo  $disenoInsumo
     * @return \Illuminate\Http\Response
     */
    public function edit(DisenoInsumo $disenoInsumo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DisenoInsumo  $disenoInsumo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DisenoInsumo $disenoInsumo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DisenoInsumo  $disenoInsumo
     * @return \Illuminate\Http\Response
     */
    public function destroy(DisenoInsumo $disenoInsumo)
    {
        if ($disenoInsumo->delete()) {
            return response()->json(['success' => true, 'message' => 'Item eliminado con éxito']);
        } else {
            return response()->json(['success' => false, 'message' => 'Error al eliminar el item']);
        }
    }
}
