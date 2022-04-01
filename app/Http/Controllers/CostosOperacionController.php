<?php

namespace App\Http\Controllers;

//use App\Http\Request;
use Illuminate\Http\Request;
use App\Models\CostosOperacion;
use App\Http\Requests\StoreCostosOperacionRequest;
use App\Http\Requests\UpdateCostosOperacionRequest;
use App\Models\Descripcion;
use App\Models\Maquina;
use App\Models\Operacion;

class CostosOperacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('admin');
        $costosOperacion = CostosOperacion::all();
        $maquinas = Maquina::all();
        $descripciones = Descripcion::all();
        $operaciones = Operacion::all();
        return view('modulos.administrativo.costos-operacion', compact('costosOperacion', 'maquinas', 'descripciones', 'operaciones'));
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
     * @param  \App\Http\Requests\StoreCostosOperacionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCostosOperacionRequest $request)
    {
        $this->authorize('admin');
        $costosOperacion = new CostosOperacion();
        $costosOperacion->cantidad = $request->cantidad;
        $costosOperacion->valor_mes = $request->valorMes;
        $costosOperacion->valor_dia = $request->valorDia;
        $costosOperacion->costo_kwh = $request->costokwh;
        $costosOperacion->maquina_id = $request->idMaquina;
        $costosOperacion->descripcion_id = $request->idDescripcion;
        $costosOperacion->save();
        return redirect()->route('costos-de-operacion.index')->with('status', 'Costo de operación creado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CostosOperacion  $costosOperacion
     * @return \Illuminate\Http\Response
     */
    public function show(CostosOperacion $costosOperacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CostosOperacion  $costosOperacion
     * @return \Illuminate\Http\Response
     */
    public function edit(CostosOperacion $costosOperacion)
    {
        $this->authorize('admin');
        $maquinas = Maquina::all();
        $descripciones = Descripcion::all();
        $operaciones = Operacion::all();
        return view('modulos.administrativo.costos-operacion-edit', compact('costosOperacion', 'maquinas', 'descripciones', 'operaciones'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCostosOperacionRequest  $request
     * @param  \App\Models\CostosOperacion  $costosOperacion
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCostosOperacionRequest $request, CostosOperacion $costosOperacion)
    {
        $this->authorize('admin');
        $costosOperacion = CostosOperacion::findOrFail($costosOperacion->id);
        $costosOperacion->cantidad = $request->cantidad;
        $costosOperacion->valor_mes = $request->valorMes;
        $costosOperacion->valor_dia = $request->valorDia;
        $costosOperacion->costo_kwh = $request->costokwh;
        $costosOperacion->maquina_id = $request->idMaquina;
        $costosOperacion->descripcion_id = $request->idDescripcion;
        $costosOperacion->save();
        return redirect()->route('costos-de-operacion.index')->with('status', 'Costo de operación actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CostosOperacion  $costosOperacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(CostosOperacion $costosOperacion)
    {
        $this->authorize('admin');
        $costosOperacion = CostosOperacion::findOrFail($costosOperacion->id);
        $costosOperacion->delete();
        return redirect()->route('costos-de-operacion.index')->with('status', 'Costo de operación eliminado con éxito');
    }

    public function descripciones(Request $request)
    {
        $this->authorize('admin');
        $operaciones = Descripcion::where('operacion_id', $request->idOperacion)->get();
        return response()->json($operaciones);
    }
    
}
