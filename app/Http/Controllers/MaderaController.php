<?php

namespace App\Http\Controllers;

use App\Models\Madera;
use App\Http\Requests\StoreMaderaRequest;
use App\Http\Requests\UpdateMaderaRequest;
use Illuminate\Http\Request;

class MaderaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('admin');
        $maderas = Madera::all();
        return view('modulos.administrativo.maderas.index', compact('maderas'));
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
     * @param  \App\Http\Requests\StoreMaderaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMaderaRequest $request)
    {
        $this->authorize('admin');
        $madera = Madera::create($request->all());
        return redirect()->route('maderas.index')->with('status', 'Madera creada con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Madera  $madera
     * @return \Illuminate\Http\Response
     */
    public function show(Madera $madera)
    {
        $this->authorize('admin');
        return view('modulos.administrativo.maderas.show', compact('madera'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Madera  $madera
     * @return \Illuminate\Http\Response
     */
    public function edit(Madera $madera)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMaderaRequest  $request
     * @param  \App\Models\Madera  $madera
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Madera $madera)
    {
        //return request()->all();
        $this->authorize('admin');
        $madera->nombre = $request->nombre;
        $madera->nombre_cientifico = $request->nombre_cientifico;
        $madera->densidad = $request->densidad;
        $madera->save();
        return redirect()->route('maderas.index')->with('status', 'Madera actualizada con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Madera  $madera
     * @return \Illuminate\Http\Response
     */
    public function destroy(Madera $madera)
    {
        $this->authorize('admin');
        $madera->delete();
        return response()->json(['success'=>'Madera eliminada correctamente']);
    }
}
