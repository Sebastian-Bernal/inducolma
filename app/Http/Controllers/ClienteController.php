<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Pedido;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\DisenoProductoFinal;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreClienteRequest;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientes = Cliente::withTrashed()->get();
        return view('modulos.administrativo.clientes.index', compact('clientes'));
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
    public function store(StoreClienteRequest $request)
    {
        //return $request;
        $this->authorize('admin');
        $cliente = new Cliente();
        $cliente->nit =  $request->nit;
        $cliente->nombre = strtoupper($request->nombre);
        $cliente->razon_social = strtoupper($request->razon_social);
        $cliente->direccion = strtoupper($request->direccion);
        $cliente->telefono = $request->telefono;
        $cliente->email = $request->email;
        $cliente->id_usuario = Auth::user()->id;
        $cliente->save();
        return back()->with('status', 'Cliente creado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {

        $pedidos = Pedido::join('diseno_producto_finales','pedidos.diseno_producto_final_id','=','diseno_producto_finales.id')
                                ->where('cliente_id', $cliente->id)
                                ->orderBy('pedidos.created_at', 'desc')
                                ->take(5)
                                ->get([
                                    'pedidos.id',
                                    'pedidos.cantidad',
                                    'pedidos.created_at',
                                    'pedidos.fecha_entrega',
                                    'pedidos.estado',
                                    'diseno_producto_finales.descripcion',
                                ]);

        return view('modulos.administrativo.clientes.show', compact('cliente', 'pedidos'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente)
    {
        return view('modulos.administrativo.clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente)
    {
        $this->authorize('admin');
        $cliente->nit = $request->nit;
        $cliente->nombre = strtoupper($request->nombre);
        $cliente->razon_social = strtoupper($request->razon_social);
        $cliente->direccion = strtoupper($request->direccion);
        $cliente->telefono = $request->telefono;
        $cliente->email = $request->email;
        $cliente->id_usuario = Auth::user()->id;
        $cliente->save();
        return redirect()->route('clientes.index')->with('status', "Cliente $cliente->nombre actualizado con éxito");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        $this->authorize('admin');
        if ($cliente->hasAnyRelatedData(['pedidos', 'disenos'])) {
            return new Response(['errors' => "No se pudo eliminar el recurso porque tiene datos asociados"], Response::HTTP_CONFLICT);
        }
        $cliente->delete();
        return response()->json(['success'=>'Cliente eliminado correctamente']);
    }


    /**
     * Restore cliente from BD
     * @param int id
     * @return Response
     */
    public function restore($id) :Response {

        try {
            $clienteDelete = Cliente::onlyTrashed()->where('id', $id)->restore();
            return new Response(['success' => 'El cliente fue restaurado con éxito'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new Response(['errors' => "El cliente no pudo ser restaurado"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
