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
use Illuminate\Contracts\View\View;

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
        $usuario = Auth::user();
        $turno = $this->obternerTurnoUsuario($usuario);

        if(!$turno){
            return redirect()->back()->with('status', "El usuario no tiene turno asignado para la fecha: " . now()->toDateString());
        }

        $asistenciaTurnosUsuarios = $this->registroAsistencia->usuariosDia();

        if($asistenciaTurnosUsuarios->count() > 0){
            return $this->mostrarIndexTurnos($asistenciaTurnosUsuarios, $turno, $usuario);
        }

        $maquina = $turno->maquina;

        if ($maquina->corte == 'ASERRIO'){
            return $this->mostrarTrozaIndex();
        }

        if ($maquina->corte == 'REASERRIO'){
            return $this->mostrarReaserrioIndex();
        }

        if (!in_array($maquina->corte, ['ENSAMBLE', 'ACABADO_ENSAMBLE'])) {
            return $this->mostrarTrabajoMaquina($maquina);
        }

        return redirect()->route('trabajo-maquina.create');

    }


    public function mostrarIndexTurnos($turno_usuarios, $turno, $usuario){
        $maquinas = Maquina::get(['id', 'maquina']);
        $eventos = Evento::get(['id', 'descripcion']);
        $usuarios = User::where('rol_id', 2)->get(['id', 'name']);
        return view(
            'modulos.operaciones.trabajo-maquina.index',
            compact('usuario', 'turno_usuarios', 'maquinas', 'eventos', 'turno', 'usuarios')
        );
    }

    public function trabajoReaserrio( int $entradaId )
    {
        $trozasReaserrio = Cubicaje::where('entrada_madera_id', $entradaId)
                            ->where('estado_troza', 1)
                            ->orderBy('bloque', 'asc')
                            ->orderBy('id', 'asc')
                            ->get([
                                'id',
                                'entrada_madera_id',
                                'bloque',
                                'paqueta',
                                'largo',
                            ]);

        return view('modulos.operaciones.trabajo-maquina.reaserrio-trabajo', compact('trozasReaserrio', 'entradaId'));
    }

    /**
     * This is a private method that is used to display the index page for the "Reaserrio" section of the "TrabajoMaquina" class.
     * It retrieves the "entradasReaserrio" data by calling the "obtenerTrozasReaserrio" method and passes it to the view.
     * The view used is "modulos.operaciones.trabajo-maquina.reaserrio-index".
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    private function mostrarReaserrioIndex() : View
    {
        $entradasReaserrio = $this->obtenerTrozasReaserrio();
        return view('modulos.operaciones.trabajo-maquina.reaserrio-index', compact('entradasReaserrio'));
    }

    /**
     * This is a private method that retrieves the trozas reaserrio from the EntradaMadera model.
     * It performs a join with the entradas_madera_maderas table and filters the results based on the condition_madera column.
     * It also checks if the related cubicajes have a estado_troza value of 1.
     * The method returns the retrieved trozas reaserrio.
     */
    private function obtenerTrozasReaserrio()
    {

        return EntradaMadera::join('entradas_madera_maderas', 'entradas_madera_maderas.entrada_madera_id', '=', 'entrada_maderas.id')
            ->where('entradas_madera_maderas.condicion_madera', 'TROZA')
            ->whereHas('cubicajes', function ($query) {
                $query->where('cubicajes.estado_troza', 1);
            })
            ->get();
    }

    /**
     * This is a private method that retrieves the turnoUsuario (shift user) for a given user.
     *
     * @param $usuario The user for which to retrieve the turnoUsuario.
     * @return The turnoUsuario object for the given user and current date, or null if not found.
     */
    private function obternerTurnoUsuario($usuario)
    {
        return TurnoUsuario::where('user_id', $usuario->id)
            ->whereDate('fecha', now()->toDateString())
            ->first();
    }


    /**
     * This is a private method that retrieves the turno (shift) of a given user for the current date.
     *
     * @param $usuario The user object for which to retrieve the turno.
     * @return The turno object for the user and current date, or null if no turno is found.
     */
    private function mostrarTrozaIndex()
    {
        $entradas = $this->obtenerEntradasTroza();
        return view('modulos.operaciones.trabajo-maquina.troza-index', compact('entradas'));
    }


    /**
    * This is a private method that retrieves the entries for the "TROZA" condition from the "EntradaMadera" model.
    * It performs a join operation with the "entradas_madera_maderas" table and filters the entries based on the "condicion_madera" column value.
    * Additionally, it checks if the entries have associated "cubicajes" with the "TROZA" state.
    * The method returns a collection of the retrieved entries.
    */
    private function obtenerEntradasTroza()
    {
        return EntradaMadera::join('entradas_madera_maderas', 'entradas_madera_maderas.entrada_madera_id', '=', 'entrada_maderas.id')
            ->where('entradas_madera_maderas.condicion_madera', 'TROZA')
            ->whereHas('cubicajes', function ($query) {
                $query->where('cubicajes.estado', 'TROZA');
            })
            ->get();
    }

    /**
     * This method is responsible for displaying the work machine.
     *
     * @param $maquina The machine object.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View The view for displaying the work machine.
     */
    private function mostrarTrabajoMaquina($maquina)
    {
        $procesos = Proceso::where('maquina_id', $maquina->id)
            ->where('estado', 'PENDIENTE')
            ->oldest()
            ->get();

        $tipos_evento = TipoEvento::all();
        $eventos = Evento::all();
        $estados = Estado::all();
        $estado_actual = EstadoMaquina::where('maquina_id', $maquina->id)->latest('id')->first('estado_id');

        if (!$estado_actual) {
            $estado_actual = EstadoMaquina::create([
                'maquina_id' => $maquina->id,
                'estado_id' => 2,
                'fecha' => now(),
            ]);
        }

        return view('modulos.operaciones.trabajo-maquina.show', compact(
            'procesos',
            'tipos_evento',
            'eventos',
            'estados',
            'maquina',
            'estado_actual'
        ));
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
            ->orderBy('bloque', 'asc')
            ->orderBy('id', 'asc')
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
