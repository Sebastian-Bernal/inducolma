<?php

namespace App\Http\Controllers;

use App\Models\DisenoProductoFinal;
use App\Models\TipoMadera;
use App\Models\Cliente;
use App\Models\Item;
use App\Models\InsumosAlmacen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DisenoCliente;
use App\Models\DisenoInsumo;
use App\Models\DisenoItem;

class DisenoProductoFinalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $disenos = DisenoProductoFinal::all();
        $tipos_maderas = TipoMadera::all();
        $clientes = Cliente::orderBy('nit')->get();
        return view('modulos.administrativo.disenos.index', compact('disenos', 'tipos_maderas', 'clientes'));
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
        $this->authorize('admin');
        $diseno = new DisenoProductoFinal();
        $diseno->descripcion = strtoupper($request->descripcion);
        $diseno->tipo_madera_id = $request->madera_id;
        $diseno->estado = 'EN USO';
        $diseno->user_id = Auth::user()->id;
        $diseno->save();
        return redirect()->route('disenos.show',$diseno->id)->with('status', 'Diseño creado con éxito, ahora puede agregar los Items e insumos');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DisenoProductoFinal  $disenoProductoFinal
     * @return \Illuminate\Http\Response
     */
    public function show(DisenoProductoFinal $diseno)
    {
        $clientes = Cliente::get(['id','nombre']);
        $diseno_items = DisenoItem::join('items','items.id','=','diseno_items.item_id')
                                   ->where('diseno_producto_final_id', $diseno->id)
                                   ->get(['diseno_items.id','items.descripcion','diseno_items.cantidad']);
        $diseno_insumos = DisenoInsumo::join('insumos_almacen','insumos_almacen.id','=','diseno_insumos.insumo_almacen_id')
                                       ->where('diseno_producto_final_id', $diseno->id)
                                       ->get(['diseno_insumos.id','insumos_almacen.descripcion','diseno_insumos.cantidad']);
        $items = Item::where('madera_id', $diseno->tipo_madera->id)->get(['id', 'descripcion']);
        $insumos = InsumosAlmacen::get(['id','descripcion']);
        return view('modulos.administrativo.disenos.show',
                compact('diseno', 'clientes', 'items', 'insumos', 'diseno_items', 'diseno_insumos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DisenoProductoFinal  $disenoProductoFinal
     * @return \Illuminate\Http\Response
     */
    public function edit(DisenoProductoFinal $diseno)
    {
        $clientes = Cliente::get(['id','nombre']);
        $tipos_maderas = TipoMadera::all();
        return view('modulos.administrativo.disenos.edit', compact('diseno', 'clientes', 'tipos_maderas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DisenoProductoFinal  $disenoProductoFinal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DisenoProductoFinal $diseno)
    {
        
        $this->authorize('admin');
        $diseno->descripcion = strtoupper($request->descripcion);
        $diseno->tipo_madera_id = $request->madera_id;
        $diseno->save();
        return redirect()->route('disenos.show',$diseno->id)->with('status', 'Diseño actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DisenoProductoFinal  $disenoProductoFinal
     * @return \Illuminate\Http\Response
     */
    public function destroy(DisenoProductoFinal $diseno)
    {
        $diseno->delete();
        return response()->json(['success' => 'Diseño eliminado con éxito']);
    }

    /**
     * funcion asignarDisenoCliente, asigna un diseño a un cliente y retorna una respuesta json
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function asignarDisenoCliente(Request $request)
    {
        //return $diseno = DisenoProductoFinal::find($request->diseno_id)->clientes()->attach($request->cliente_id);
        
        $this->authorize('admin'); 
        $existe  = DisenoCliente::where('diseno_producto_final_id', $request->diseno_id)
                                ->where('cliente_id', $request->cliente_id)->first();
        
        if($existe){
            return response()->json(['error' => true, 'message' => 'El diseño ya esta asignado al cliente']);
        } else{
            
            $diseno_cliente = new DisenoCliente();
            $diseno_cliente->diseno_producto_final_id = $request->diseno_id;
            $diseno_cliente->cliente_id = $request->cliente_id;
            $diseno_cliente->user_id = Auth::user()->id;
            if($diseno_cliente->save()){
                return response()->json(['error'=>false, 'message'=>'Diseño asignado con éxito']);
            }else{
                return response()->json(['error'=>true, 'message'=>'Error al asignar diseño']);
            }       
        }
    }

    /**
     * funcion consultarItemsInsumos, consulta los items y insumos de un diseño, retorna una respuesta json
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function consultarItemsInsumos(Request $request)
    {
        $diseno = DisenoProductoFinal::find($request->diseno_id);
        if($diseno->items->count() == 0 || $diseno->insumos->count() == 0){
           return response()->json(['error'=>true, 'message'=>'No hay items o insumos suficientes para el diseño']);
        } else{
            return response()->json(['error'=>false]);
        }
    }
}