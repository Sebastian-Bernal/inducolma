<?php

namespace App\Http\Controllers;

use App\Models\Recepcion;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRecepcionRequest;
use App\Http\Requests\UpdateRecepcionRequest;
use App\Repositories\RegistroUsuarios;

class RecepcionController extends Controller
{

    protected $registroUsuarios;
    
    public function __construct(RegistroUsuarios $registroUsuarios)
    {
        $this->registroUsuarios = $registroUsuarios;
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $recepciones = Recepcion::where('created_at', '=', now())
                                ->orWhere('deleted_at','=', null) ->get();
        return view('modulos.operaciones.recepcion.ingreso', compact('recepciones'));
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
     * @param  \App\Http\Requests\StoreRecepcionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRecepcionRequest $request)
    {
        //return $request->all();
        $this->authorize('entrada-maderas');

        return $this->registroUsuarios->ingresoVisitante($request);

        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Recepcion  $recepcion
     * @return \Illuminate\Http\Response
     */
    public function show(Recepcion $recepcion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Recepcion  $recepcion
     * @return \Illuminate\Http\Response
     */
    public function edit(Recepcion $recepcion)
    {
        return view('modulos.operaciones.recepcion.edit', compact('recepcion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRecepcionRequest  $request
     * @param  \App\Models\Recepcion  $recepcion
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRecepcionRequest $request, Recepcion $recepcion)
    {
        $this->authorize('entrada-maderas');
        $recepcion->visitante = $request->visitante;
        $recepcion->updated_at = null;
        $recepcion->save();
        return redirect()->route('recepcion.index')->with('status', 'Ingreso de personal o visitante, actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Recepcion  $recepcion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recepcion $recepcion)
    {
        $this->authorize('entrada-maderas');
        $recepcion->deleted_at = now();
        $recepcion->save();
        return response()->json(['error' => false]);
    }

    /**
     * Consulta la existencia de un usuario en la base de datos.
     * @param  \App\Models\User  $request->usuario
     * @return \Illuminate\Http\Response
     */
    public function consultaUsuario(Request $request)
    {
        $usuario = User::where('identificacion', $request->usuario)->first();
        if (empty($usuario)) {
            return response()->json(['success' => true, 'usuario' => $request->usuario]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    /**
     * Consulta el reporte de usuarios que ingresaron en un rango de fechas.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function reporteRecepcion(Request $request)
    {
        $this->authorize('entrada-maderas');
        
        $recepciones = Recepcion::whereBetween('created_at', [$request->desde, $request->hasta])
                                 ->withTrashed()
                                 ->get();
        return view('modulos.operaciones.recepcion.reporte', compact('recepciones'));
    }
    
    /**
     * Crea un nuevo ingreso de empleados
     * 
     * @param  \App\Http\Requests\StoreRecepcionRequest  $request
     * @return \Illuminate\Http\Response
     */
    
    public function recepcionEmpleado(Request $request){
        
        $this->authorize('entrada-maderas');
        return  $this->registroUsuarios->ingresoEmpleado($request);
    }

    /**
     * Crea nuevo registro de ingreso de contratista
     * 
     * @param  \App\Http\Requests\StoreRecepcionRequest  $request
     * @return \Illuminate\Http\Response
     */

    public function recepcionContratista(Request $request){
        
        $this->authorize('entrada-maderas');
        return  $this->registroUsuarios->ingresoContratista($request);
    }
}