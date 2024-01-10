<?php

namespace App\Http\Controllers;

use App\Models\CostosInfraestructura;
use App\Http\Requests\StoreCostosInfraestructuraRequest;
use App\Http\Requests\UpdateCostosInfraestructuraRequest;
use App\Models\Maquina;
use App\Models\Madera;
use App\Models\Item;
use App\Models\TipoMadera;

class CostosInfraestructuraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('admin');
        $estadaresUnidadesMinuto = CostosInfraestructura::all();
        $maquinas = Maquina::all();
        $maderas = TipoMadera::all();

        return view('modulos.administrativo.costos.costos-infraestructura',
                    compact('estadaresUnidadesMinuto', 'maquinas', 'maderas'));
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
        $this->authorize('admin');

        $estandarUnidadesMinuto = CostosInfraestructura::create([
                'maquina_id' => $request->maquina,
                'tipo_material' => $request->tipo_material,
                'tipo_madera' => $request->tipo_madera,
                'estandar_u_minuto' => $request->unidades_minuto,
        ]);

        if($estandarUnidadesMinuto->wasRecentlyCreated){
            return back()->with('status', 'Estandar de unidades por minuto creado con éxito');
        }

        return back()->with('status', 'Algo salio mal');
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
        $this->authorize('admin');
        $maquinas = Maquina::all();
        $maderas = TipoMadera::all();
        $estandarUnidadesMinuto = $costosInfraestructura;
        return view('modulos.administrativo.costos.costos-infraestructura-edit',
                    compact('estandarUnidadesMinuto', 'maquinas', 'maderas'));
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
        $this->authorize('admin');
        $costosInfraestructura->update([
            'maquina_id' => $request->maquina,
            'tipo_material' => $request->tipo_material,
            'tipo_madera' => $request->tipo_madera,
            'estandar_u_minuto' => $request->unidades_minuto,
        ]);

        return redirect()->route('costos-de-infraestructura.index')->with('status', 'Estandar de unidades por minuto actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CostosInfraestructura  $costosInfraestructura
     * @return \Illuminate\Http\Response
     */
    public function destroy(CostosInfraestructura $costosInfraestructura)
    {
        $this->authorize('admin');
        $costosInfraestructura->delete();
        return back()->with('status', 'Estandar de unidades por minuto eliminado con éxito');
    }
}
