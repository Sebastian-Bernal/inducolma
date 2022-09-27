<?php

namespace App\Http\Controllers;

use App\Models\Cubicaje;
use App\Models\DisenoProductoFinal;
use App\Models\OrdenProduccion;
use App\Models\Pedido;
use App\Models\Item;
use App\Models\Cliente;
use App\Models\DisenoItem;
use App\Models\Maquina;
use App\Models\Transformacion;
use App\Repositories\MaderasOptimas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdenProduccionController extends Controller
{

    protected $maderas;

    public function __construct(MaderasOptimas $maderas)
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
        $pedidos = Pedido::join('clientes', 'pedidos.cliente_id', '=', 'clientes.id')
            ->join('diseno_producto_finales', 'pedidos.diseno_producto_final_id', '=', 'diseno_producto_finales.id')
            ->orderBy('pedidos.fecha_entrega', 'asc')
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
        $ordenes = OrdenProduccion::where('estado', '!=', 'FINALIZADA')->get();
        $cliente = Cliente::where('nombre', 'like', '%INDUCOLMA%')->first();
        $disenos = DisenoProductoFinal::get(['id', 'descripcion']);
        return view('modulos.administrativo.programacion.index', compact('pedidos', 'ordenes', 'cliente', 'disenos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Pedido $ordenProduccion)
    {
        return $ordenProduccion;

        return $diseno_items = DisenoItem::join('items', 'items.id', '=', 'diseno_items.item_id')
            ->where('diseno_producto_final_id', 6)
            ->get(['diseno_items.id', 'items.descripcion', 'diseno_items.cantidad']);
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
        return response()->json(['success' => 'Orden de Producción creada con éxito.']);
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
        /* $datos = [];
        foreach ($pedido->items_pedido as $item) {
            $datos[] =  (object)array(
                        'item_id' => $item->item_id,
                        'pedido'  => $pedido->id
                        );
        }*/


        return view('modulos.administrativo.programacion.show', compact('pedido'));
    }

    public function showMaderas($pedido, $item_id)
    {
        $request = (object)[];
        $request->id_pedido = $pedido;
        $request->id_item = $item_id;
        $item = $request->id_item;
        $pedido = Pedido::find($request->id_pedido);
        $optimas =  $this->maderas->Optimas($request);

        //return $optimas['maderas_usar'] ;

        if (isset($optimas['maderas_usar'], $optimas['sobrantes_usar'])) {

            if ($optimas['producir'] <= 0) {
                $maquinas = Maquina::get(['id','maquina','corte'])->groupBy('corte');
                $orden = OrdenProduccion::where('pedido_id', $pedido->id)
                                        ->where('item_id',$item)
                                        ->first();

                return view('modulos.administrativo.programacion.seleccion-procesos')
                    ->with(compact('maquinas', 'orden'));

            } else {
                if (count($optimas['maderas_usar']) > 0 || count($optimas['sobrantes_usar']) > 0) {
                    return view('modulos.administrativo.programacion.maderas-optimas', compact('optimas', 'pedido', 'item'));
                } else {
                    $status = 'no hay maderas disponibles...';
                    return redirect()->back()->with('status', $status);
                }
            }
        } else {
            $status = 'no hay maderas disponibles...';
            return redirect()->back()->with('status', $status);
        }

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
        $item = $request->id_item;
        $optimas =  $this->maderas->Optimas($request);

        //return $optimas['maderas_usar'] ;

        if (isset($optimas['maderas_usar'], $optimas['sobrantes_usar'])) {
            if (count($optimas['maderas_usar']) > 0 || count($optimas['sobrantes_usar']) > 0) {
                return view('modulos.administrativo.programacion.maderas-optimas', compact('optimas', 'pedido', 'item'));
            } else {
                $status = 'no hay maderas disponibles...';
                return redirect()->back()->with('status', $status);
            }
        } else {
            $status = 'no hay maderas disponibles...';
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
        return response()->json(['success' => 'Orden de Producción creada con éxito.']);
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
            ->where('estado', 'DISPONIBLE')
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
        $ver = 1;
        $cubicaje = $this->maderas->cubicaje($request, $ver);
        return $cubicaje;
    }


    /**
     * Selecciona el viaje y la paqueta.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response json
     */
    public function seleccionar(Request $request)
    {

        $this->authorize('admin');
        $guardar = 2;
        $orden = $this->crearOrden($request);
        $seleccion = $this->maderas->seleccionaPaqueta($request, $guardar, $orden);
        $error =  $seleccion[0]['error'];

        // si seleccion error = false crear la orden de produccion, si es true se elimina todo lo creado en
        // transformacione y se vuelve al estado Disponible en la tabla cubicajes
        if ($error) {
            Cubicaje::where('entrada_madera_id', $request->entrada_madera_id)
                ->where('paqueta', $request->paqueta)
                ->update(['estado' => 'DISPONIBLE']);
            Transformacion::join('cubicajes.id', '=', 'transformaciones.cubicaje_id')
                ->where('cubicajes.entrada_madera_id', $request->entrada_madera_id)
                ->delete();

            $orden->delete();
            return response()->json(['error' => true, 'datos_error' => $seleccion]);

        } else {

            return response()->json(['error' => false]);
        }
    }

    /**
     * crearOrden()
     * @return object
     */

    public function crearOrden( $request)
    {
        $orden = new OrdenProduccion();
        $orden->cantidad = $request->cantidad;
        $orden->estado = '';
        $orden->user_id = Auth::user()->id;
        $orden->pedido_id = $request->id_pedido;
        $orden->item_id = $request->id_item;
        $orden->save();

        return $orden;
    }
}
