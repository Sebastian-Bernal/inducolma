<?php

namespace App\Http\Controllers;

use App\Models\DisenoProductoFinal;
use App\Models\Madera;
use App\Models\Cliente;
use App\Models\Item;
use App\Models\InsumosAlmacen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DisenoCliente;

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
        $maderas = Madera::all();
        $clientes = Cliente::orderBy('nit')->get();
        return view('modulos.administrativo.disenos.index', compact('disenos', 'maderas', 'clientes'));
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
        $diseno->madera_id = $request->madera_id;
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
        $items = Item::where('madera_id', $diseno->madera_id)->get(['id', 'descripcion']);
        $insumos = InsumosAlmacen::get(['id','descripcion']);
        return view('modulos.administrativo.disenos.show', compact('diseno', 'clientes', 'items', 'insumos'));
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
       
        return view('modulos.administrativo.disenos.edit', compact('diseno', 'clientes'));
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
        //
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
}