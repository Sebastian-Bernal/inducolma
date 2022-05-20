<?php

namespace App\Http\Controllers;

use App\Models\CalificacionMadera;
use Illuminate\Http\Request;

class CalificacionMaderaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $calificaciones = CalificacionMadera::where('created_at', '>=', date('Y-m-d'))
                                                ->where('user_id', auth()->user()->id)
                                                ->get();
        return view('modulos.operaciones.calificaciones.index', compact('calificaciones'));
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
        //return $request->all();
        $calificacion = new CalificacionMadera();
        $calificacion->longitud_madera = $request->longitud_madera;
        $calificacion->cantonera = $request->cantonera;
        $calificacion->hongos = $request->hongos;
        $calificacion->rajadura = $request->rajadura;
        $calificacion->bichos = $request->bichos;
        $calificacion->organizacion = $request->organizacion;
        $calificacion->areas_transversal_max_min = $request->areas_transversal_max_min;
        $calificacion->areas_no_conveniente = $request->areas_no_convenientes;
        $calificacion->total = $request->total;
        $calificacion->updated_at = null;
        $calificacion->entrada_madera_id = $request->entrada_madera_id;
        $calificacion->paqueta = $request->paqueta;        
        $calificacion->user_id = auth()->user()->id;
       
        
        if ( $request->total > 60   && $request->rajadura > 1.25 && $request->bichos > 1.25 ) {
            $calificacion->aprobado = true;        
        } else {
            $calificacion->aprobado = false;
        }
       
        if ( $calificacion->save()) {
           return response()->json(['success' => true, 'message' => 'Calificación guardada correctamente']);
        } else {
              return response()->json(['success' => false, 'message' => 'Error al guardar la calificación']);
        }
        
        

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CalificacionMadera  $calificacionMadera
     * @return \Illuminate\Http\Response
     */
    public function show(CalificacionMadera $calificacionMadera)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CalificacionMadera  $calificacionMadera
     * @return \Illuminate\Http\Response
     */
    public function edit(CalificacionMadera $calificacion)
    {
        return view('modulos.operaciones.calificaciones.edit', compact('calificacion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CalificacionMadera  $calificacionMadera
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CalificacionMadera $calificacion)
    {
        $calificacion->longitud_madera = $request->longitudMadera;
        $calificacion->cantonera = $request->cantonera;
        $calificacion->hongos = $request->hongos;
        $calificacion->rajadura = $request->rajadura;
        $calificacion->bichos = $request->bichos;
        $calificacion->organizacion = $request->organizacion;
        $calificacion->areas_transversal_max_min = $request->rangoMaxMin;
        $calificacion->areas_no_conveniente = $request->areas;
        $calificacion->total = $request->puntos;
        $calificacion->user_id = auth()->user()->id;

        if ( $request->puntos > 60   && $request->rajadura > 1.25 && $request->bichos > 1.25 ) {
            $calificacion->aprobado = true;        
        } else {
            $calificacion->aprobado = false;
        }
        $calificacion->update();

        return redirect()->route('calificaciones.index')->with('status',
                             " Se modifico la calificación de la entrada: $calificacion->entrada_madera_id, paqueta: $calificacion->paqueta");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CalificacionMadera  $calificacionMadera
     * @return \Illuminate\Http\Response
     */
    public function destroy(CalificacionMadera $calificacionMadera)
    {
        //
    }
}
