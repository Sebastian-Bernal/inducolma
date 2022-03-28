<?php

namespace App\Http\Controllers;

use App\Models\CostosInfraestructura;
use App\Http\Requests\StoreCostosInfraestructuraRequest;
use App\Http\Requests\UpdateCostosInfraestructuraRequest;
use App\Models\Maquina;

class CostosInfraestructuraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $costosIinfraestructura = CostosInfraestructura::all();
        $maquinas = Maquina::all();
        return view('modulos.administrativo.costos-infraestructura', compact('costosIinfraestructura', 'maquinas'));
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
     * @param  \App\Http\Requests\StoreCostosInfraestructuraRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCostosInfraestructuraRequest $request)
    {
        $costosInfraestructura = new CostosInfraestructura();
        $costosInfraestructura->valor_operativo = $request->valorOperativo;
        $costosInfraestructura->tipo_material = $request->tipoMaterial;
        $costosInfraestructura->tipo_madera = $request->tipoMadera;
        $costosInfraestructura->proceso_madera = $request->procesoMadera;
        $costosInfraestructura->promedio_piezas = $request->promedioPiezas;
        $costosInfraestructura->minimo_piezas = $request->minimoPiezas;
        $costosInfraestructura->maximo_piezas = $request->maximoPiezas;
        $costosInfraestructura->maquina_id = $request->idMaquina;
        $costosInfraestructura->save();
        
        return back()->with('status', 'Costo de Infraestructura creado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CostosInfraestructura  $costosInfraestructura
     * @return \Illuminate\Http\Response
     */
    public function show(CostosInfraestructura $costosInfraestructura)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CostosInfraestructura  $costosInfraestructura
     * @return \Illuminate\Http\Response
     */
    public function edit(CostosInfraestructura $costosInfraestructura)
    {
       
        $maquinas = Maquina::all();
        return view('modulos.administrativo.costos-infraestructura-edit', compact('costosInfraestructura', 'maquinas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCostosInfraestructuraRequest  $request
     * @param  \App\Models\CostosInfraestructura  $costosInfraestructura
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCostosInfraestructuraRequest $request, CostosInfraestructura $costosInfraestructura)
    {
        $costosInfraestructura->valor_operativo = $request->valorOperativo;
        $costosInfraestructura->tipo_material = $request->tipoMaterial;
        $costosInfraestructura->tipo_madera = $request->tipoMadera;
        $costosInfraestructura->proceso_madera = $request->procesoMadera;
        $costosInfraestructura->promedio_piezas = $request->promedioPiezas;
        $costosInfraestructura->minimo_piezas = $request->minimoPiezas;
        $costosInfraestructura->maximo_piezas = $request->maximoPiezas;
        $costosInfraestructura->maquina_id = $request->idMaquina;
        $costosInfraestructura->save();
        
        return redirect()->route('costos-de-infraestructura.index')->with('status', 'Costo de Infraestructura actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CostosInfraestructura  $costosInfraestructura
     * @return \Illuminate\Http\Response
     */
    public function destroy(CostosInfraestructura $costosInfraestructura)
    {
        $costosInfraestructura->delete();
        return back()->with('status', 'Costo de Infraestructura eliminado con éxito');
    }
}
