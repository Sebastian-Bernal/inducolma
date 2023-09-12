<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\DisenoProductoFinal;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\StorePedidoRequest;
use Illuminate\Support\Facades\Request as FacadesRequest;

class PedidoController extends Controller
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
                        ->get([
                                'pedidos.id',
                                'pedidos.cantidad',
                                'pedidos.created_at',
                                'pedidos.fecha_entrega',
                                'pedidos.estado',
                                'clientes.nombre',
                                'diseno_producto_finales.descripcion',
                            ]);
        $clientes = Cliente::select('id', 'nombre','razon_social')->get();

        return view('modulos.administrativo.pedidos.index', compact('pedidos', 'clientes'));
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
    public function store( StorePedidoRequest $request)
    {

        $pedido = new Pedido();
        $pedido->diseno_producto_final_id = $request->items;
        $pedido->cantidad = $request->cantidad;
        $pedido->fecha_solicitud = date('Y-m-d');
        $pedido->fecha_entrega = $request->fecha_entrega;
        $pedido->estado = 'PENDIENTE';
        $pedido->user_id = auth()->user()->id;
        $pedido->cliente_id = $request->cliente;
        $pedido->save();

        //obtener la url anterior


        if (Route::current()->getName() == 'pedidos.store') {
            return redirect()->route('pedidos.index')->with('status', "El pedido # $pedido->id, para el cliente {$pedido->cliente->nombre} ha sido creado");
        } else{
            return redirect()->route('programaciones.index')->with('status', "El pedido # $pedido->id, para el cliente {$pedido->cliente->nombre} ha sido editado");
        }



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function show(Pedido $pedido)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function edit(Pedido $pedido)
    {
        $clientes = Cliente::select('id', 'nombre')->get();
        $disenos_cliente = Cliente::find($pedido->cliente_id)->disenos()->get(['diseno_producto_final_id as id','descripcion']);
        return view('modulos.administrativo.pedidos.show', compact('pedido', 'clientes', 'disenos_cliente'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function update(StorePedidoRequest $request, Pedido $pedido)
    {
        $pedido->diseno_producto_final_id = $request->items;
        $pedido->cantidad = $request->cantidad;
        $pedido->fecha_solicitud = date('Y-m-d');
        $pedido->fecha_entrega = $request->fecha_entrega;
        $pedido->estado = 'PENDIENTE';
        $pedido->user_id = auth()->user()->id;
        $pedido->cliente_id = $request->cliente;
        $pedido->update();
        return redirect()->route('pedidos.index')->with('status', "El pedido # $pedido->id, para el cliente {$pedido->cliente_id} ha sido actualizado");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pedido $pedido)
    {
        $pedido->delete();
        if ($pedido->hasAnyRelatedData(['ordenes_produccion','pedido_producto'])) {
            return new Response(['errors' => "No se pudo eliminar el recurso porque tiene datos asociados"], Response::HTTP_CONFLICT);
        }
        return response()->json(['success' => 'Pedido eliminado correctamente']);
    }

    /**
     * funcion itemsCliente, busca los diseno_producto_final de un cliente
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function itemsCliente(Request $request)
    {

        $disenos = Cliente::find($request->id)->disenos()->get(['diseno_producto_final_id as id','descripcion']);
        return response()->json($disenos);
    }

    /**
     * funcion disenoBuscar, busca los diseno_producto_finales por coincidencia de descripcion
     * @param  string  $descripcion
     * @return \Illuminate\Http\Response
     */
    public function disenoBuscar(Request $request)
    {
        //return $request->all();
        $disenos = DisenoProductoFinal::where('descripcion', 'like', '%'.strtoupper($request->descripcion).'%')
                                    ->get(['id','descripcion as text']);
        $disenos->toJson();
        return response()->json($disenos);
    }
}
