<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Estado;
use App\Models\Evento;
use App\Models\Pedido;
use App\Models\Maquina;
use App\Models\Proceso;
use App\Models\Cubicaje;
use App\Models\TipoEvento;
use App\Models\TurnoUsuario;
use Illuminate\Http\Request;
use App\Models\EntradaMadera;
use App\Models\EstadoMaquina;
use App\Models\EventoProceso;
use App\Models\EnsambleAcabado;
use Illuminate\Support\Facades\Auth;
use App\Repositories\RegistroAsistencia;
use App\Repositories\ProductosTerminados;
use App\Http\Requests\StoreTraajoMaquinaRequest;
use App\Http\Requests\StreProductoRequest;
use Exception;

class TrabajoMaquina extends Controller
{
    protected $registroAsistencia, $productosTerminados;

    public function __construct(RegistroAsistencia $registroAsitencia, ProductosTerminados $productosTerminados)
    {
        $this->registroAsistencia = $registroAsitencia;
        $this->productosTerminados = $productosTerminados;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuario = User::select(['id', 'name'])->find(Auth::user()->id);
        $turno = TurnoUsuario::where('user_id', Auth::user()->id)
            ->where('fecha', date('Y-m-d'))
            ->first();
        if (!empty($turno)) {
            $turno_usuarios = $this->registroAsistencia->usuariosDia();
            if (count($turno_usuarios->toArray()) > 0) {
                $maquinas = Maquina::get(['id', 'maquina']);
                $eventos = Evento::get(['id', 'descripcion']);
                $usuarios = User::where('rol_id', 2)->get(['id', 'name']);
                return view(
                    'modulos.operaciones.trabajo-maquina.index',
                    compact('usuario', 'turno_usuarios', 'maquinas', 'eventos', 'turno', 'usuarios')
                );
            } else {
                $procesos = Proceso::where('maquina_id', $turno->maquina_id)
                    ->where('estado', 'PENDIENTE')
                    ->oldest()
                    ->get();
                $tipos_evento = TipoEvento::get(['id', 'tipo_evento']);
                $eventos = Evento::get(['id', 'descripcion', 'tipo_evento_id']);
                $estados = Estado::get(['id', 'descripcion']);
                $maquina = $turno->maquina_id;
                $estado_actual = EstadoMaquina::where('maquina_id', $turno->maquina_id)->latest('id')->first('estado_id');
                if ($estado_actual == '') {
                    $estado_actual = EstadoMaquina::create([
                        'maquina_id' => $turno->maquina_id,
                        'estado_id' => 2,
                        'fecha' => now(),
                    ]);
                }

                if ($turno->maquina->corte == 'ASERRIO') {
                    $entradas = EntradaMadera::join('entradas_madera_maderas', 'entradas_madera_maderas.entrada_madera_id', '=', 'entrada_maderas.id')
                        ->where('entradas_madera_maderas.condicion_madera', 'TROZA')
                        ->get();
                    return view('modulos.operaciones.trabajo-maquina.troza-index', compact('entradas'));
                }

                if ($turno->maquina->corte != 'ENSAMBLE' && $turno->maquina->corte != 'ACABADO_ENSAMBLE') {
                    return view(
                        'modulos.operaciones.trabajo-maquina.show',
                        compact(
                            'procesos',
                            'tipos_evento',
                            'eventos',
                            'estados',
                            'maquina',
                            'estado_actual'
                        )
                    );
                }

                return redirect()->route('trabajo-maquina.create');
            }
        } else {
            return redirect()->back()->with('status', "El usuario no tiene turno asignado para la fecha: " . date('Y-m-d'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       // Obtener la ID de la máquina del usuario en turno
        $maquinaIdTurno = TurnoUsuario::where('user_id', Auth::id())
        ->whereDate('fecha', now())
        ->value('maquina_id');

        // Obtener pedidos pendientes asociados a órdenes terminadas
        $pedidos_trabajo_maquina = Pedido::where('estado', 'PENDIENTE')
        ->whereHas('ordenes_produccion', function ($query) {
            $query->where('estado', 'TERMINADO');
        })
        ->whereIn('id', function ($query) use ($maquinaIdTurno) {
            $query->select('pedido_id')
                ->from('ensambles_acabados')
                ->where('estado', '!=','TERMINADO')
                ->where('maquina_id', $maquinaIdTurno);
        })
        ->pluck('id');

        $acabados_ensamble = EnsambleAcabado::whereIn('pedido_id', $pedidos_trabajo_maquina)
                                            ->where('maquina_id', $maquinaIdTurno)
                                            ->get();

        return view('modulos.operaciones.trabajo-maquina.ensamble', compact( 'acabados_ensamble'));
    }



    public function trabajoTroza(EntradaMadera $entrada)
    {
        $trozas = Cubicaje::where('entrada_madera_id', $entrada->id)
            ->where('estado', 'TROZA')
            ->orderBy('id')
            ->get([
                'id',
                'entrada_madera_id',
                'bloque',
                'paqueta',
                'largo',
            ]);
        return view('modulos.operaciones.trabajo-maquina..transformacion-troza', compact('entrada', 'trozas'));
    }

    /**
     * Show the form for create a new producto
     *
     * @return \Illuminate\Http\Response
     */

    public function trabajoEnsamble(Pedido $pedido)
    {
        $turno = TurnoUsuario::where('user_id', Auth::user()->id)
            ->where('fecha', date('Y-m-d'))
            ->first();
        $turno_usuarios = TurnoUsuario::where('turno_id', $turno->turno_id)
            ->where('maquina_id', $turno->maquina_id)
            ->where('asistencia', true)
            ->where('fecha', date('Y-m-d'))
            ->get()
            ->load('user');
        $maquina = $turno->maquina_id;
        $tipos_evento = TipoEvento::get(['id', 'tipo_evento']);
        $eventos = Evento::get(['id', 'descripcion', 'tipo_evento_id']);

        $ensamble = EnsambleAcabado::where('pedido_id', $pedido->id)
                                    ->where('estado','!=','TERMINADO')
                                    ->where('maquina_id', $maquina)
                                    ->first();

        if (!$ensamble) {
            return redirect()->route('trabajo-maquina.index')->with('status', 'El ensamble finalizo con éxito');
        }

        return view(
            'modulos.operaciones.trabajo-maquina.trabajo-ensamble',
            compact(
                'pedido',
                'turno_usuarios',
                'tipos_evento',
                'eventos',
                'maquina',
                'ensamble'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StreProductoRequest $request)
    {
        try {
            $procesarPedido = $this->productosTerminados->guardar($request);
            if ($procesarPedido) {
                return redirect()->route('trabajo-ensamble', $procesarPedido)->with('status',
                    "<p class='text-success'>
                            Producto procesado con éxito.
                        <i class='fa-solid fa-triangle-exclamation'></i>
                    </p>");
            }
        } catch (Exception $e) {
            return redirect()->route('trabajo-ensamble', $request->pedido)->with('status',
                    "<p class='text-danger'>
                            ".$e->getMessage()."
                        <i class='fa-solid fa-triangle-exclamation'></i>
                    </p>"
                    );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Proceso $trabajo_maquina)
    {
        $apagado = EstadoMaquina::where('maquina_id', $trabajo_maquina->maquina_id)
            ->latest('id')
            ->first('estado_id');
        $tipos_evento = TipoEvento::get(['id', 'tipo_evento']);
        $eventos = Evento::get(['id', 'descripcion', 'tipo_evento_id']);
        $maquina = $trabajo_maquina->maquina_id;
        $turno = TurnoUsuario::where('user_id', Auth::user()->id)
            ->where('fecha', date('Y-m-d'))
            ->first();
        $turno_usuarios = TurnoUsuario::where('turno_id', $turno->turno_id)
            ->where('maquina_id', $turno->maquina_id)
            ->where('asistencia', true)
            ->where('fecha', date('Y-m-d'))
            ->get()
            ->load('user');

        if ($apagado->estado_id != 1) {
            return redirect()->back()->with('status', 'La maquina no ha sido encendida');
        }
        if ($trabajo_maquina->estado == 'TERMINADO') {
            return redirect()->route('trabajo-maquina.index')->with('status', 'La orden ya fue terminada');
        }
        return view(
            'modulos.operaciones.trabajo-maquina.trabajo-proceso',
            compact(
                'trabajo_maquina',
                'tipos_evento',
                'eventos',
                'maquina',
                'turno_usuarios',
            )
        );
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
        $evento->maquina_id = $request->proceso_id;
        $evento->evento_id = $request->evento_id;
        $evento->user_id = $request->user_id;
        $evento->observacion = $request->observaciones;

        try {
            $evento->save();
            return response()->json(array('error' => false, 'mensaje' => "evento guardado"));
        } catch (\Throwable $th) {
            return response()->json(array('error' => true, 'mensaje' => "evento no pudo ser guardado"));
        }
    }

    /**
     * guarda el estado de la maquina
     *
     * @param json $request
     */

    public function guardaEstado(Request $request)
    {
        $estado = (int)$request->estado_id;
        $estado_actual = EstadoMaquina::where('maquina_id', (int)$request->maquina_id)
            ->latest('id')
            ->first();

        switch ($estado) {
            case 1:
                if ($estado_actual == '' || $estado_actual->estado_id >= 2) {
                    return $this->registroAsistencia->guardaEstado($request);
                } else {
                    return response()->json(array('error' => true, 'mensaje' => 'la maquina ya esta encendida'));
                }
                break;
            case 2:
                if (isset($estado_actual->estado_id) && $estado_actual->estado_id == 1) {
                    return $this->registroAsistencia->guardaEstado($request);
                } else {
                    return response()->json(array('error' => true, 'mensaje' => 'La maquina ya esta apagada'));
                }
                break;
            default:
                if ($estado_actual->estado_id != 1 && $estado_actual->estado_id != $estado) {
                    return $this->registroAsistencia->guardaEstado($request);
                } else {
                    return response()->json(array(
                        'error' => true,
                        'mensaje' => 'La maquina debe estar apagada, o el eveto seleccionado ya fue guardado'
                    ));
                }
                break;
        }
    }
    /**
     * guarda el estado apagado de la maquina cuando por error no se apago en el sistema
     *
     * @param Request $request
     */

    public function apagarMaquina(Request $request)
    {
        return $this->registroAsistencia->apagarMaquina($request);
    }
}
