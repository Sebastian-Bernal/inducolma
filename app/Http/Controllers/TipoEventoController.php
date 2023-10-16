<?php

namespace App\Http\Controllers;

use App\Models\TipoEvento;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\StoreTipoEventoRequest;

class TipoEventoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipo_eventos = TipoEvento::all();
        return view('modulos.administrativo.tipo_evento.index', compact('tipo_eventos'));
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
    public function store(StoreTipoEventoRequest $request)
    {
        $this->authorize('admin');
        $tipo_evento = new TipoEvento();
        $tipo_evento->tipo_evento = strtoupper($request->tipo_evento) ;
        $tipo_evento->save();
        return redirect()->route('tipo-eventos.index')->with('status', 'Tipo de evento creado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TipoEvento  $tipoEvento
     * @return \Illuminate\Http\Response
     */
    public function show(TipoEvento $tipo_evento)
    {
        return view('modulos.administrativo.tipo_evento.show', compact('tipo_evento'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TipoEvento  $tipoEvento
     * @return \Illuminate\Http\Response
     */
    public function edit(TipoEvento $tipoEvento)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TipoEvento  $tipoEvento
     * @return \Illuminate\Http\Response
     */
    public function update(StoreTipoEventoRequest $request, TipoEvento $tipo_evento)
    {
        $this->authorize('admin');
        $tipo_evento->tipo_evento = strtoupper($request->tipo_evento) ;
        $tipo_evento->save();
        return redirect()->route('tipo-eventos.index')->with('status', 'Tipo de evento actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TipoEvento  $tipoEvento
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipoEvento $tipo_evento)
    {
        $this->authorize('admin');
        if ($tipo_evento->hasAnyRelatedData(['eventos'])) {
            return new Response(['errors' => "No se pudo eliminar el recurso porque tiene datos asociados"], Response::HTTP_CONFLICT);
        }
        $tipo_evento->delete();
        return response()->json(['success' => 'Tipo de evento eliminado con éxito']);
    }
}
