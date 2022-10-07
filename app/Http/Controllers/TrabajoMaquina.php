<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Maquina;
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
}
