<?php

namespace App\Http\Controllers;

use App\Models\Maquina;
use App\Http\Requests\StoreMaquinaRequest;
use App\Http\Requests\UpdateMaquinaRequest;
use GuzzleHttp\Middleware;

class MaquinaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $maquinas = Maquina::latest()->get();

        return view('modulos.administrativo.maquinas', compact('maquinas'));
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
     * @param  \App\Http\Requests\StoreMaquinaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMaquinaRequest $request)
    {
        //dd($request->maquina);
        Maquina::create(
            ['maquina' => $request->maquina]
        );
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Maquina  $maquina
     * @return \Illuminate\Http\Response
     */
    public function show(Maquina $maquina)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Maquina  $maquina
     * @return \Illuminate\Http\Response
     */
    public function edit(Maquina $maquina)
    {
        $maquina = Maquina::findOrFail($maquina->id);
        return view('modulos.administrativo.edit-maquinas',[
            'maquina'   => $maquina,
                      
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMaquinaRequest  $request
     * @param  \App\Models\Maquina  $maquina
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMaquinaRequest $request, Maquina $maquina)
    {
        
        //dd($request->maquina);
        $maquina = Maquina::findOrFail($maquina->id);
        $maquina->update($request->all());
        return redirect()->route('maquinas.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Maquina  $maquina
     * @return \Illuminate\Http\Response
     */
    public function destroy(Maquina $maquina)
    {
        $maquina->delete();
        return back();
    }
}
