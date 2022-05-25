<?php

namespace App\Http\Controllers;

use App\Models\DisenoProductoFinal;
use App\Models\Madera;
use App\Models\Cliente;
use Illuminate\Http\Request;

class DisenoProductoFinalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $disenos = DisenoProductoFinal::all();
        $maderas = Madera::all();
        $clientes = Cliente::orderBy('nit')->get();
        return view('modulos.administrativo.disenos.index', compact('disenos', 'maderas', 'clientes'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DisenoProductoFinal  $disenoProductoFinal
     * @return \Illuminate\Http\Response
     */
    public function show(DisenoProductoFinal $disenoProductoFinal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DisenoProductoFinal  $disenoProductoFinal
     * @return \Illuminate\Http\Response
     */
    public function edit(DisenoProductoFinal $disenoProductoFinal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DisenoProductoFinal  $disenoProductoFinal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DisenoProductoFinal $disenoProductoFinal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DisenoProductoFinal  $disenoProductoFinal
     * @return \Illuminate\Http\Response
     */
    public function destroy(DisenoProductoFinal $disenoProductoFinal)
    {
        //
    }
}
