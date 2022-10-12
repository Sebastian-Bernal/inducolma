<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\EventoProceso;
use App\Models\Maquina;
use App\Models\TurnoUsuario;
use App\Models\User;
use App\Repositories\RegistroAsistencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrabajoMaquina extends Controller
{
    protected $registroAsistencia;

    public function __construct(RegistroAsistencia $registroAsitencia)
    {
        $this->registroAsistencia = $registroAsitencia;
    }

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
        if (!empty($turno)) {
            $turno_usuarios = $this->registroAsistencia->usuariosDia($turno);
            $maquinas = Maquina::get(['id', 'maquina']);
            $eventos = Evento::get(['id', 'descripcion']);
            $usuarios = User::where('rol_id', 2)->get(['id', 'name']);
            return view('modulos.operaciones.trabajo-maquina.index',
                    compact('usuario', 'turno_usuarios', 'maquinas','eventos', 'turno', 'usuarios'));
        } else {
            return redirect()->back()->with('status', "El usuario no tiene turno asignado para la fecha: ". date('Y-m-d'));
        }

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
     *
     * @param  Request $request [el request debe contener user_id, maquina_id, turno_id]
     * @return Response JSON
     */

    public function guardaAsistencia(Request $request)
    {

        return $this->registroAsistencia->guardar($request);
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

    /**
     * guarda una nueva asignacion de turno a un usuario retorna el array de turnos dia
     *
     * @param Request $request [maquina_id, turno_id, user_id( usuario nuevo)]
     *
     * @return Response JSON
     */

    public function nuevoAuxiliar(Request $request)
    {
        return $this->registroAsistencia->nuevoAuxiliar($request);
    }


}
