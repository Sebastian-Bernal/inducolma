<?php

namespace App\Http\Controllers;

use App\Models\OrdenProduccion;
use App\Models\Pedido;
use Illuminate\Http\Request;

class OrdenProduccionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pedidos = Pedido::join('clientes','pedidos.cliente_id','=','clientes.id')
                            ->join('diseno_producto_finales','pedidos.diseno_producto_final_id','=','diseno_producto_finales.id')
                            ->orderBy('pedidos.fecha_entrega','asc')
                            ->get([ 
                                    'pedidos.id',
                                    'pedidos.cantidad',
                                    'pedidos.created_at',
                                    'pedidos.fecha_entrega',
                                    'pedidos.estado',
                                    'clientes.nombre',
                                    'diseno_producto_finales.descripcion',                                    
                                ]);

        //$pedidos = Pedido::all();
        return view('modulos.administrativo.programacion.index', compact('pedidos'));
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OrdenProduccion  $ordenProduccion
     * @return \Illuminate\Http\Response
     */
    public function show(OrdenProduccion $ordenProduccion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OrdenProduccion  $ordenProduccion
     * @return \Illuminate\Http\Response
     */
    public function edit(OrdenProduccion $ordenProduccion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrdenProduccion  $ordenProduccion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrdenProduccion $ordenProduccion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OrdenProduccion  $ordenProduccion
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrdenProduccion $ordenProduccion)
    {
        //
    }
}
