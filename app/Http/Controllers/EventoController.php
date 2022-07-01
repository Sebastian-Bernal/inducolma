<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventoRequest;
use App\Models\Evento;
use App\Models\TipoEvento;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;

class EventoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $eventos = Evento::all();
        $tipo_eventos = TipoEvento::all();
        return view('modulos.administrativo.eventos.index', compact('eventos', 'tipo_eventos'));
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
    public function store(StoreEventoRequest $request)
    {
        $this->authorize('admin');
        $evento = new Evento();
        $evento->descripcion = $request->descripcion;
        $evento->tipo_evento_id = $request->tipoEvento;
        $evento->user_id = auth()->user()->id;
        $evento->save();
        return redirect()->route('eventos.index')->with('status', "El evento: $request->descripcion,  se ha creado correctamente");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function show(Evento $evento)
    {
        $tipo_eventos = TipoEvento::all();
        return view('modulos.administrativo.eventos.show', compact('evento', 'tipo_eventos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function edit(Evento $evento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Evento $evento)
    {
        $evento->descripcion = $request->descripcion;
        $evento->tipo_evento_id = $request->tipo_evento;
        $evento->user_id = auth()->user()->id;
        $evento->update();
        return redirect()->route('eventos.index')->with('status', "El evento: $request->descripcion,  se ha actualizado correctamente");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Evento $evento)
    {
        
        $this->authorize('admin');
        $evento->delete();
        return response()->json(['success'=>'Evento eliminado correctamente']);

    }

}
