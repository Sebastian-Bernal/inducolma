<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\TipoMadera;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\StoreItemsRequest;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Item::all();
        $tipos_maderas = TipoMadera::withTrashed()->get(['id','descripcion']);
        return view('modulos.administrativo.items.index', compact('items', 'tipos_maderas'));
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
    public function store(StoreItemsRequest $request)
    {
        //return $request->all();
        $this->authorize('admin');
        $item = new Item();
        $item->descripcion = $request->descripcion;
        $item->alto = $request->alto;
        $item->ancho = $request->ancho;
        $item->largo = $request->largo;
        $item->existencias = $request->existencias;
        $item->madera_id = $request->tipo_madera;
        $item->codigo_cg = $request->codigo_cg;
        $item->preprocesado = $request->preprocesado;
        $item->carretos = $request->carretos;
        $item->user_id = auth()->user()->id;
        $item->save();
        return redirect()->route('items.index')->with('status', "Item: $request->descripcion  creado correctamente");


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        $tipos_maderas = TipoMadera::withTrashed()->get(['id','descripcion']);
        return view('modulos.administrativo.items.show', compact('item', 'tipos_maderas'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {

        $this->authorize('admin');
        $item->descripcion = $request->descripcion;
        $item->alto = $request->alto;
        $item->ancho = $request->ancho;
        $item->largo = $request->largo;
        $item->existencias = $request->existencias;
        $item->madera_id = $request->tipo_madera;
        $item->codigo_cg = $request->codigo_cg;
        $item->preprocesado = $request->preprocesado;
        $item->carretos = $request->carretos;
        $item->user_id = auth()->user()->id;
        $item->save();
        return redirect()->route('items.index')->with('status', "Item: $request->descripcion  actualizado correctamente");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {

        $this->authorize('admin');

        if ($item->hasAnyRelatedData(['costos_infraestructura','orden_produccion'])) {
            return new Response(['errors' => "No se pudo eliminar el recurso porque tiene datos asociados"], Response::HTTP_CONFLICT);
        }

        if($item->delete()){
            return response()->json(['success' => 'Item eliminado correctamente']);
        } else {
            return response()->json(['error' => 'Error al eliminar el item']);
        }
    }
}
