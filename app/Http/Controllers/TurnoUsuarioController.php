<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTurnoUserRequest;
use App\Models\Maquina;
use App\Models\Turno;
use App\Models\TurnoUsuario;
use App\Models\User;
use Illuminate\Http\Request;

class TurnoUsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $turnos = Turno::get(['id', 'turno']);
        $usuarios = User::whereBetween('rol_id',[1, 2])->get(['id', 'name']);
        $maquinas = Maquina::get(['id', 'maquina']);

        $turnos_usuarios = TurnoUsuario::orderBy('id', 'desc')->take(100)->get();

        return view('modulos.administrativo.turnos-usuarios.index',
            compact('turnos', 'usuarios', 'maquinas', 'turnos_usuarios'));
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
    public function store(StoreTurnoUserRequest $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TurnoUsuario  $asignar_turno
     * @return \Illuminate\Http\Response
     */
    public function show(TurnoUsuario $asignar_turno)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TurnoUsuario  $asignar_turno
     * @return \Illuminate\Http\Response
     */
    public function edit(TurnoUsuario $asignar_turno)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TurnoUsuario  $asignar_turno
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TurnoUsuario $asignar_turno)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TurnoUsuario  $asignar_turno
     * @return \Illuminate\Http\Response
     */
    public function destroy(TurnoUsuario $asignar_turno)
    {

        $this->authorize('admin');
        $asignar_turno->delete();
        return response()->json(array('success' => "El turno asignado fue eliminado"));
    }
}
