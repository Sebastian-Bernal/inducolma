<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAcabadoProductoRequest;
use App\Http\Requests\UpdateAcabadoProductoRequest;
use App\Models\Maquina;
use App\Models\Pedido;
use App\Repositories\RutasEnsambleAcabados;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RutaAcabadoProductoController extends Controller
{


    protected $rutas;

    public function __construct(RutasEnsambleAcabados $rutas) {
        $this->rutas = $rutas;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($pedido_id)
    {
        $pedido = Pedido::find($pedido_id);
        $maquinas = Maquina::whereIn('corte', ['ENSAMBLE', 'ACABADO_ENSAMBLE'])->get()->groupBy('corte')->toArray();
        return view('modulos.administrativo.ruta-acabado-producto.create', compact('pedido', 'maquinas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAcabadoProductoRequest $request)
    {

        $crearRutas = $this->rutas->crearRutas($request);
        if ($crearRutas) {
            return new Response(['success' => true], Response::HTTP_OK);
        }

        return new Response(['success' => false], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAcabadoProductoRequest $request, $id)
    {
        $updateRuta = $this->rutas->updateRuta($request, $id);

        if ($updateRuta) {
            return new Response(['success' => true], Response::HTTP_OK);
        }
        return new Response(['success' => false], Response::HTTP_INTERNAL_SERVER_ERROR);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleteRuta = $this->rutas->deleteRuta($id);
        if($deleteRuta = 'not found'){
            return new Response(['success' => false], Response::HTTP_NOT_FOUND);
        }
        elseif ($deleteRuta) {
            return new Response(['success' => true], Response::HTTP_OK);
        }
        return new Response(['success' => false], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
