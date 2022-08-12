<?php

namespace App\Http\Controllers;

use App\Models\Cubicaje;
use App\Models\DisenoProductoFinal;
use App\Models\OrdenProduccion;
use App\Models\Pedido;
use App\Models\Item;
use App\Models\Cliente;
use App\Models\DisenoItem;
use App\Repositories\MaderasOptimas;
use Illuminate\Http\Request;

class OrdenProduccionController extends Controller
{

    protected $maderas;

    public function __construct( MaderasOptimas $maderas)
    {
        $this->maderas = $maderas;

    }
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
        $ordenes = OrdenProduccion::where('estado','!=','FINALIZADA')->get();
        $cliente = Cliente::where('nombre','like','%INDUCOLMA%')->first();
        $disenos = DisenoProductoFinal::get(['id','descripcion']);
        return view('modulos.administrativo.programacion.index', compact('pedidos','ordenes','cliente','disenos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Pedido $ordenProduccion )
    {
        return $ordenProduccion;

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
        $pedido =  $ordenProduccion->datos();

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
        $pedido = Pedido::find($request->id_pedido);
        $item = Item::find($request->id_item);
        $optimas =  $this->maderas->Optimas($request);

        //return $optimas ;
        if (isset($optimas['maderas_usar'], $optimas['sobrantes_usar'])) {
            if (count($optimas['maderas_usar'])>0 || count($optimas['sobrantes_usar'])>0) {
                return view('modulos.administrativo.programacion.maderas-optimas', compact('optimas','pedido'));
            } else {
                $status= 'no hay maderas disponibles...';
                return redirect()->back()->with('status', $status);
            }
        }else{
            $status= 'no hay maderas disponibles...';
            return redirect()->back()->with('status', $status);
        }

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

     /**
      * verPaqueta() - funcion que devuelve los detalles de la paqueta en json
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response json
      */

        public function verPaqueta(Request $request)
        {
            $paqueta = Cubicaje::where('entrada_madera_id', $request->entrada_madera_id)
                                ->where('paqueta', $request->paqueta)
                                ->orderBy('pulgadas_cuadradas', 'desc')
                                ->get(['pulgadas_cuadradas', 'bloque']);
            return response()->json($paqueta);
        }

    /**
     * funcion dividirPaqueta() - funcion que divide la paqueta en dos partes, retorna dos arrays
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response json
     */

    public function dividirPaqueta(Request $request)
    {
        $this->authorize('admin');
        $cubicaje = $this->maderas->cubicaje($request);
        return $cubicaje;
    }
}
