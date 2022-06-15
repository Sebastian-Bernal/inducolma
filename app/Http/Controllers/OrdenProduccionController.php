<?php

namespace App\Http\Controllers;

use App\Models\DisenoProductoFinal;
use App\Models\OrdenProduccion;
use App\Models\Pedido;
use App\Models\Item;
use App\Models\DisenoItem;
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
                                    'diseno_producto_finales.id as diseno_id',                                  
                                ]);

        //$pedidos = Pedido::all();
        return view('modulos.administrativo.programacion.index', compact('pedidos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Pedido $ordenProduccion )
    {
        return $ordenProduccion;
        // $pedido = Pedido::find($request->id)->select('id',
        //                                             'diseno_producto_final_id',
        //                                     )->first();
        //return $pedido;
        return $diseno_items = DisenoItem::join('items','items.id','=','diseno_items.item_id')
                            ->where('diseno_producto_final_id', 6)
                            ->get(['diseno_items.id','items.descripcion','diseno_items.cantidad']);
                                        

        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('admin');
        $ordenProduccion = new OrdenProduccion();
        $ordenProduccion->pedido_id = $request->pedido_id;
        $ordenProduccion->item_id = $request->item_id;
        $ordenProduccion->cantidad = $request->cantidad;
        $ordenProduccion->user_id = auth()->user()->id;
        $ordenProduccion->estado = $request->estado;
        $ordenProduccion->save();
        return response()->json(['success'=>'Orden de Producción creada con éxito.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OrdenProduccion  $ordenProduccion
     * @return \Illuminate\Http\Response
     */
    public function show(Pedido $ordenProduccion)
    {
        
        $pedido = Pedido::join('clientes','pedidos.cliente_id','=','clientes.id')
                            ->join('diseno_producto_finales','pedidos.diseno_producto_final_id','=','diseno_producto_finales.id')
                            ->where('pedidos.id', $ordenProduccion->id)
                            ->orderBy('pedidos.fecha_entrega','asc')
                            ->get([ 
                                    'pedidos.id',
                                    'pedidos.cantidad',
                                    'pedidos.created_at',
                                    'pedidos.fecha_entrega',
                                    'pedidos.estado',
                                    'clientes.nombre',
                                    'diseno_producto_finales.descripcion',  
                                    'diseno_producto_finales.id as diseno_id',                                  
                                ]);
        $pedido = $pedido[0];
        
        return view('modulos.administrativo.programacion.show', compact('pedido'));

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

    /**
     * Devuelve las maderas optimas para la orden de produccion
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function maderasOptimas(Request $request)
     {
       return $request->all();
     }

     /**
      * Crea una nueva orden de produccion, de items existentes en inventario, recibida por peticion ajax
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
     */

     public function crearOrdenItemsInventario(Request $request)
     {
        //crea la orden de produccion
        $this->authorize('admin');
        $ordenProduccion = new OrdenProduccion();
        $ordenProduccion->pedido_id = $request->pedido_id;
        $ordenProduccion->item_id = $request->item_id;
        $ordenProduccion->cantidad = $request->cantidad;
        $ordenProduccion->user_id = auth()->user()->id;
        $ordenProduccion->estado = $request->estado;
        $ordenProduccion->save();
        // actualizar existencias de items 
        $item = Item::find($request->item_id);
        $item->existencias = $item->existencias - (int)$request->cantidad;
        $item->save();
        return response()->json(['success'=>'Orden de Producción creada con éxito.']);
     }






}
