<?php

namespace App\Http\Controllers;

use App\Models\TipoMadera;
use Illuminate\Http\Response;
use App\Http\Requests\StoreTipoMaderaRequest;
use App\Http\Requests\UpdateTipoMaderaRequest;

class TipoMaderaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tiposMadera = TipoMadera::withTrashed()->get();
        return view('modulos.administrativo.tipo_madera.index', compact('tiposMadera'));
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
     * @param  \App\Http\Requests\StoreTipoMaderaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTipoMaderaRequest $request)
    {
        $tipoMadera = new TipoMadera();
        $tipoMadera->descripcion = strtoupper($request->descripcion);
        $tipoMadera->user_id = auth()->user()->id;
        $tipoMadera->save();
        return redirect()->route('tipos-maderas.index')->with('status', "El tipo de madera $tipoMadera->descripcion ha sido creado correctamente");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TipoMadera  $tipoMadera
     * @return \Illuminate\Http\Response
     */
    public function show(TipoMadera $tipoMadera)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TipoMadera  $tipoMadera
     * @return \Illuminate\Http\Response
     */
    public function edit(TipoMadera $tipoMadera)
    {
        return view('modulos.administrativo.tipo_madera.edit', compact('tipoMadera'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTipoMaderaRequest  $request
     * @param  \App\Models\TipoMadera  $tipoMadera
     * @return \Illuminate\Http\Response
     */
    public function update(StoreTipoMaderaRequest $request, TipoMadera $tipoMadera)
    {
        $tipoMadera->descripcion = strtoupper($request->descripcion);
        $tipoMadera->user_id = auth()->user()->id;
        $tipoMadera->save();
        return redirect()->route('tipos-maderas.index')->with('status', "El tipo de madera $tipoMadera->descripcion ha sido actualizado correctamente");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TipoMadera  $tipoMadera
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipoMadera $tipoMadera)
    {
        //$tipoMadera->maderas()->delete();
        //$tipoMadera->items()->delete();
        $tipoMadera->delete();
        return response()->json(['success' => 'Tipo de madera eliminado correctamente']);
    }


    /**
     * Restore resource from BD
     * @param int id
     * @return Response
     */
    public function restore($id) :Response {

        try {
            $resourceDelete = TipoMadera::onlyTrashed()->where('id', $id)->restore();
            return new Response(['success' => 'El tipo de madera fue restaurado con éxito'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new Response(['errors' => "El tipo de madera no pudo ser restaurado"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
