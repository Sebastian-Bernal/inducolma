<?php

namespace App\Http\Controllers;

use App\Models\Cubicaje;
use App\Models\EntradaMadera;
use App\Models\Estado;
use App\Models\EstadoMaquina;
use App\Models\Evento;
use App\Models\EventoProceso;
use App\Models\Maquina;
use App\Models\Pedido;
use App\Models\Proceso;
use App\Models\TipoEvento;
use App\Models\TurnoUsuario;
use App\Models\User;
use App\Repositories\ProductosTerminados;
use App\Repositories\RegistroAsistencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\Return_;

class TrabajoMaquina extends Controller
{
    protected $registroAsistencia, $productosTerminados;

    public function __construct(RegistroAsistencia $registroAsitencia, ProductosTerminados $productosTerminados )
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
        $usuario = User::select(['id','name'])->find(Auth::user()->id);
        $turno = TurnoUsuario::where('user_id',Auth::user()->id)
                                ->where('fecha', date('Y-m-d'))
                                ->first();
        if (!empty($turno)) {
            $turno_usuarios = $this->registroAsistencia->usuariosDia();
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

                if ($turno->maquina->corte == 'ASERRIO'){
                    $entradas = EntradaMadera::join('entradas_madera_maderas','entradas_madera_maderas.entrada_madera_id', '=', 'entrada_maderas.id')
                                        ->where('entradas_madera_maderas.condicion_madera', 'TROZA')
                                        ->get();
                    return view('modulos.operaciones.trabajo-maquina.troza-index', compact('entradas'));
                }

                if ($turno->maquina->corte != 'ENSAMBLE' ) {
                    return view('modulos.operaciones.trabajo-maquina.show',
                    compact('procesos',
                            'tipos_evento',
                            'eventos',
                            'estados',
                            'maquina',
                            'estado_actual'));
                }

                return redirect()->route('trabajo-maquina.create');



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
        $pedidos = Pedido::all()->load('ordenes_produccion');
        $pedidos_ordenes = collect();

        foreach ($pedidos as $pedido) {
            if ($pedido->ordenes_produccion->all() != []) {
                $guardar = true;
                foreach ($pedido->ordenes_produccion as $orden) {
                    if ($orden->estado != 'PENDIENTE') {
                        $guardar = false;
                        break ;
                    }
                }
                if ($guardar) {
                    $pedidos_ordenes->push($pedido);
                }
            }
        }

        return view('modulos.operaciones.trabajo-maquina.ensamble', compact('pedidos_ordenes'));
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
        $turno = TurnoUsuario::where('user_id',Auth::user()->id)
                                ->where('fecha', date('Y-m-d'))
                                ->first();
        $turno_usuarios = TurnoUsuario::where('turno_id', $turno->turno_id)
                                ->where('maquina_id', $turno->maquina_id)
                                ->where('asistencia', true)
                                ->where('fecha',date('Y-m-d'))
                                ->get()
                                ->load('user');
        $maquina = $turno->maquina_id;
        $tipos_evento = TipoEvento::get(['id', 'tipo_evento']);
        $eventos = Evento::get(['id', 'descripcion', 'tipo_evento_id']);

        /*  $i = 0;
        foreach ($pedido->diseno_producto_final->items as $item) {
            if ($item->existencias < $pedido->items_pedido[$i]->cantidad ) {
                return back()->with('status',
                    "El item: $item->descripcion, no tiene existencias suficientes, no puede ensamblar el producto para el pedido No. $pedido->id");
                break;
            }
            $i++;
        }
        */
        return view('modulos.operaciones.trabajo-maquina.trabajo-ensamble',
                compact('pedido',
                        'turno_usuarios',
                        'tipos_evento',
                        'eventos',
                        'maquina'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->productosTerminados->guardar($request);

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
        $turno = TurnoUsuario::where('user_id',Auth::user()->id)
                                ->where('fecha', date('Y-m-d'))
                                ->first();
        $turno_usuarios = TurnoUsuario::where('turno_id', $turno->turno_id)
                                ->where('maquina_id', $turno->maquina_id)
                                ->where('asistencia', true)
                                ->where('fecha',date('Y-m-d'))
                                ->get()
                                ->load('user');

        if ($apagado->estado_id != 1){
            return redirect()->back()->with('status', 'La maquina no ha sido encendida');
        }
        if($trabajo_maquina->estado=='TERMINADO'){
            return redirect()->route('trabajo-maquina.index')->with('status', 'La orden ya fue terminada');
        }
        return view('modulos.operaciones.trabajo-maquina.trabajo-proceso',
        compact('trabajo_maquina',
                'tipos_evento',
                'eventos',
                'maquina',
                'turno_usuarios',
                        ));
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

    /**
     * guarda el estado de la maquina
     *
     * @param json $request
     */

    public function guardaEstado(Request $request)
    {
        $estado = (integer)$request->estado_id;
        $estado_actual = EstadoMaquina::where('maquina_id', (integer)$request->maquina_id)
                                    ->latest('id')
                                    ->first();

        switch ($estado) {
            case 1:
                if ($estado_actual == '' || $estado_actual->estado_id >= 2) {
                    return $this->registroAsistencia->guardaEstado($request);
                } else{
                    return response()->json(array('error' => true, 'mensaje' =>'la maquina ya esta encendida'));
                }
                break;
            case 2:
                if (isset($estado_actual->estado_id) && $estado_actual->estado_id == 1) {
                    return $this->registroAsistencia->guardaEstado($request);
                } else{
                    return response()->json(array('error' => true, 'mensaje' =>'La maquina ya esta apagada'));
                }
                break;
            default:
                if ($estado_actual->estado_id !=1 && $estado_actual->estado_id != $estado) {
                    return $this->registroAsistencia->guardaEstado($request);
                } else{
                    return response()->json(array(
                                            'error' => true,
                                            'mensaje' =>'La maquina debe estar apagada, o el eveto seleccionado ya fue guardado'
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
