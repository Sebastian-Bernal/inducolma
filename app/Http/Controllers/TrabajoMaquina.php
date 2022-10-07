<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\EventoProceso;
use App\Models\Maquina;
use App\Models\TiepoUsuarioDia;
use App\Models\TurnoUsuario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrabajoMaquina extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuario = User::select(['id','name'])->find(Auth::user()->id);
        $turno = TurnoUsuario::where('user_id',Auth::user()->id)
                                ->where('fecha', date('Y-m-d'))
                                ->first();
        $turno_usuarios = TurnoUsuario::where('turno_id',$turno->turno_id)
                                ->where('fecha', date('Y-m-d'))
                                ->get()
                                ->except($turno->id);
        $maquinas = Maquina::get(['id', 'maquina']);
        $eventos = Evento::get(['id', 'descripcion']);

        return view('modulos.operaciones.trabajo-maquina.index',
                compact('usuario', 'turno_usuarios', 'maquinas','eventos'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    /**
     * guarda el registro de la asistencia de usuario
     */

    public function guardaAsistencia(Request $request)
    {
        $asistencia  = new TiepoUsuarioDia();
        $asistencia->fecha = date('Y-m-d');
        $asistencia->entrada = time();
        $asistencia->usuario_id = $request->usuario_id;
        $asistencia->maquina_id = $request->maquina_id;

        try {
            $asistencia->save();
            return response()->json(array('error' => false, 'mensaje' => "asistencia guardada" ));
        } catch (\Throwable $th) {
            return response()->json(array('error' => true, 'mensaje' => "asistencia no pudo ser guardada" ));
        }

    }

    /**
     * guarda la eventualidad de la maquina
     */

    public function guardaEventualidad(Request $request)
    {
        $evento = new EventoProceso();
        $evento->proceso_id = $request->proceso_id ;
        $evento->evento_id = $request->evento_id ;
        $evento->user_id = $request->usuario_id ;

        try {
            $evento->save();
            return response()->json(array('error' => false, 'mensaje' => "evento guardado" ));
        } catch (\Throwable $th) {
            return response()->json(array('error' => true, 'mensaje' => "evento no pudo ser guardado" ));
        }

    }
}
