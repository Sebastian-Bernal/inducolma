<?php

namespace App\Http\Controllers;

use App\Models\Contratista;
use App\Http\Requests\StoreContratistaRequest;
use App\Http\Requests\UpdateContratistaRequest;

class ContratistaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('admin');
        $contratistas = Contratista::paginate(1);   
        
        return view('modulos.administrativo.contratistas.index', compact('contratistas'));
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
     * @param  \App\Http\Requests\StoreContratistaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreContratistaRequest $request)
    {
       // return $request->all();
        $this->authorize('admin');
        $contratista = new Contratista();
        $contratista->cedula = $request->cedula;
        $contratista->primer_nombre = strtoupper($request->primer_nombre);
        $contratista->segundo_nombre = strtoupper($request->segundo_nombre);
        $contratista->primer_apellido = strtoupper($request->primer_apellido);
        $contratista->segundo_apellido = strtoupper($request->segundo_apellido);
        if($request->acceso == 'on'){
            $contratista->acceso = true;
        }else{
            $contratista->acceso = false;
        }
        $contratista->empresa_contratista = strtoupper($request->empresa_contratista);
        $contratista->user_id = auth()->user()->id;
        $contratista->save();
        return redirect()->route('contratistas.index')->with('status', 'Contratista creado con éxito');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contratista  $contratista
     * @return \Illuminate\Http\Response
     */
    public function show(Contratista $contratista)
    {
        return view('modulos.administrativo.contratistas.edit', compact('contratista'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contratista  $contratista
     * @return \Illuminate\Http\Response
     */
    public function edit(Contratista $contratista)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateContratistaRequest  $request
     * @param  \App\Models\Contratista  $contratista
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateContratistaRequest $request, Contratista $contratista)
    {
        $this->authorize('admin');
        $contratista->cedula = $request->cedula;
        $contratista->primer_nombre = strtoupper($request->primer_nombre);
        $contratista->segundo_nombre = strtoupper($request->segundo_nombre);
        $contratista->primer_apellido = strtoupper($request->primer_apellido);
        $contratista->segundo_apellido = strtoupper($request->segundo_apellido);
        if($request->acceso == 'on'){
            $contratista->acceso = true;
        }else{
            $contratista->acceso = false;
        }
        $contratista->empresa_contratista = strtoupper($request->empresa_contratista);
        $contratista->user_id = auth()->user()->id;
        $contratista->update();
        return redirect()->route('contratistas.index')->with('status', "Contratista $contratista->primer_nombre $contratista->primer_apellido actualizado");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contratista  $contratista
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contratista $contratista)
    {
        $contratista->delete();
        return response()->json(['success' => "Contratista $contratista->primer_nombre $contratista->primer_apellido eliminado"]);
    }
}
