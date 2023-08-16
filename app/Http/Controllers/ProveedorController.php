<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\ProveedorRequest;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('admin');
        $proveedores = Proveedor::withTrashed()->get();
        return view('modulos.administrativo.proveedores.index', compact('proveedores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( ProveedorRequest $request)
    {

        //return request()->all();
        $this->authorize('admin');
        $proveedor = new Proveedor();
        $proveedor->identificacion = $request->identificacion;
        $proveedor->nombre =   strtoupper($request->nombre);  ;
        $proveedor->direccion = strtoupper($request->direccion);
        $proveedor->telefono = $request->telefono;
        $proveedor->email = $request->email;
        $proveedor->razon_social = strtoupper($request->razon_social);
        $proveedor->user_id = auth()->user()->id;
        $proveedor->calificacion = 0;
        $proveedor->save();
        return redirect()->route('proveedores.index')->with('status', "Proveedor $proveedor->nombre creado correctamente");

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function show(Proveedor $proveedor)
    {
        $this->authorize('admin');
        return view('modulos.administrativo.proveedores.show', compact('proveedor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function edit(Proveedor $proveedor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Proveedor $proveedor)
    {
        $this->authorize('admin');
        $proveedor->identificacion = $request->identificacion;
        $proveedor->nombre =   strtoupper($request->nombre);  ;
        $proveedor->direccion = strtoupper($request->direccion);
        $proveedor->telefono = $request->telefono;
        $proveedor->email = $request->email;
        $proveedor->razon_social = strtoupper($request->razon_social);
        $proveedor->user_id = auth()->user()->id;
        $proveedor->save();
        return redirect()->route('proveedores.index')->with('status', "Datos del proveedor $proveedor->nombre actualizados correctamente");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proveedor $proveedor)
    {
        $this->authorize('admin');
        if ($proveedor->hasAnyRelatedData(['entradasMadera'])) {
            return new Response(['errors' => "No se pudo eliminar el recurso porque tiene datos asociados"], Response::HTTP_CONFLICT);
        }
        $proveedor->delete();
        return response()->json(['success' => 'Proveedor eliminado correctamente']);
    }

    /**
     * Restore proveedor from BD
     * @param int $id
     * @return Response
     */
    public function restore($id) :Response {

        try {
            $proveedor_deleted = Proveedor::onlyTrashed()->where('id', $id)->restore();
            return new Response(['success' => 'El proveedor fue restaurado con éxito'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new Response(['errors' => "El usuario no pudo ser restaurado"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
