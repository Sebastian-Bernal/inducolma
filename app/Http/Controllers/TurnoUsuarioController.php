<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTurnoUserRequest;
use App\Http\Requests\UpdateTurnoUsuarioRequest;
use App\Models\Maquina;
use App\Models\Turno;
use App\Models\TurnoUsuario;
use App\Models\User;
use App\Repositories\AsignarTurno;
use Illuminate\Http\Request;

class TurnoUsuarioController extends Controller
{

    protected $asignarTurno;

    public function __construct(AsignarTurno $asignarTurno){
        $this->asignarTurno = $asignarTurno;
    }

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

        $turnos_usuarios = TurnoUsuario::latest('fecha')->take(100)->get();

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
        $this->authorize('admin');
        $asignar = $this->asignarTurno->crearTurnos($request);
        return redirect()->route('asignar-turnos.index')->with($asignar);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TurnoUsuario  $asignar_turno
     * @return \Illuminate\Http\Response
     */
    public function show(Request $asignar_turno)
    {
        return $asignar_turno;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TurnoUsuario  $asignar_turno
     * @return \Illuminate\Http\Response
     */
    public function edit(TurnoUsuario $asignar_turno)
    {

        $turnos = Turno::get(['id', 'turno']);
        $usuarios = User::whereBetween('rol_id',[1, 2])->get(['id', 'name']);
        $maquinas = Maquina::get(['id', 'maquina']);
        return view('modulos.administrativo.turnos-usuarios.show',
            compact('asignar_turno', 'maquinas', 'usuarios', 'turnos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TurnoUsuario  $asignar_turno
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTurnoUsuarioRequest $request, TurnoUsuario $asignar_turno)
    {
        $this->authorize('admin');
        $asignar_turno->update([
            'user_id' => $request->usuario,
            'turno_id' => $request->turno,
            'maquina_id' => $request->maquina,
            'fecha' => $request->desde,
        ]);
        return redirect()->route('asignar-turnos.index')
                ->with('status', "El turno para el usuario: {$asignar_turno->user->name} fue actualizado");
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

    /**
     * Retorna los turnos asignados si se encuentran en los rangos de fechas
     *
     * @param Request $request
     * @return Response Json
     */

    public function TurnosUsuario(Request $request)
    {
        $this->authorize('admin');
        return $this->asignarTurno->consultaTurno($request);
    }
}
