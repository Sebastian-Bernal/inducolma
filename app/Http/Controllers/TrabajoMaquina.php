<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\EventoProceso;
use App\Models\Maquina;
use App\Models\Proceso;
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
            if (count($turno_usuarios->toArray()) > 0) {
                $maquinas = Maquina::get(['id', 'maquina']);
                $eventos = Evento::get(['id', 'descripcion']);
                $usuarios = User::where('rol_id', 2)->get(['id', 'name']);
                return view('modulos.operaciones.trabajo-maquina.index',
                        compact('usuario', 'turno_usuarios', 'maquinas','eventos', 'turno', 'usuarios'));
            } else {
                $procesos = Proceso::where('maquina_id', $turno->maquina_id)
                                    ->where('estado', 'PENDIENTE')
                                    ->oldest()
                                    ->get();
                return view('modulos.operaciones.trabajo-maquina.show', compact('procesos'));


            }
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
    public function show(Proceso $trabajo_maquina)
    {
        return view('modulos.operaciones.trabajo-maquina.trabajo-proceso', compact('trabajo_maquina'));
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
        $evento->maquina_id = $request->proceso_id ;
        $evento->evento_id = $request->evento_id ;
        $evento->user_id = $request->user_id ;
        $evento->observacion = $request->observaciones ;

        try {
            $evento->save();
            return response()->json(array('error' => false, 'mensaje' => "evento guardado" ));
        } catch (\Throwable $th) {
            return response()->json(array('error' => true, 'mensaje' => "evento no pudo ser guardado" ));
        }
    }


}
