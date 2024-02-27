<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rol;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUsuariosRequest;
use Illuminate\Http\Response;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('admin');
        $usuarios = User::withTrashed()
                                    ->select('id', 'name', 'email', 'rol_id', 'identificacion', 'deleted_at')
                                    ->with('roll')
                                    ->where('id', '!=', 1)
                                    ->get();
        $roles = Rol::all();

        return view('modulos.administrativo.usuarios.index', compact('usuarios', 'roles'));
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
    public function store(StoreUsuariosRequest  $request)
    {
        //return $request->all();
        $this->authorize('admin');
        $usuario = new User();
        $usuario->identificacion = $request->identificacionUsuario;
        $usuario->name =  strtoupper($request->primer_nombre) . ' ' . strtoupper($request->primer_apellido);
        $usuario->primer_nombre = strtoupper($request->primer_nombre);
        $usuario->segundo_nombre = strtoupper($request->segundo_nombre);
        $usuario->primer_apellido = strtoupper($request->primer_apellido);
        $usuario->segundo_apellido = strtoupper($request->segundo_apellido);
        $usuario->email = $request->email;
        $usuario->rol_id = $request->rolUsuario;
        $usuario->password = bcrypt($request->identificacionUsuario);
        $usuario->pago_mes_anterior = $request->pagoMesAnterior ?? 0;
        $usuario->pago_estandar = $request->pagoEstandar;
        $usuario->save();
        return redirect()->route('usuarios.index')->with('status', "Usuario $usuario->name creado correctamente");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $usuario)
    {
        $this->authorize('admin');
        $roles = Rol::all();
        return view('modulos.administrativo.usuarios.show', compact('usuario', 'roles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('admin');
        $usuario = User::fidOrFail($id);
        return response()->json($usuario);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize('admin');
        $usuario = User::findOrFail($id);
        $usuario->name =  strtoupper($request->primer_nombre) . ' ' . strtoupper($request->primer_apellido);
        $usuario->primer_nombre = strtoupper($request->primer_nombre);
        $usuario->segundo_nombre = strtoupper($request->segundo_nombre);
        $usuario->primer_apellido = strtoupper($request->primer_apellido);
        $usuario->segundo_apellido = strtoupper($request->segundo_apellido);
        $usuario->email = $request->email;
        $usuario->rol_id = $request->rolUsuario;
        $usuario->pago_mes_anterior = $request->pagoMesAnterior ?? 0;
        $usuario->pago_estandar = $request->pagoEstandar;
        $usuario->save();
        return redirect()->route('usuarios.index')->with('status', "Usuario $usuario->name actualizado correctamente");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //return $id;
        $this->authorize('admin');
        $usuario = User::findOrFail($id);
        $usuario->delete();
        return response()->json(['success'=>'Usuario eliminado correctamente']);
        //return redirect()->route('usuarios.index');
    }

    /**
     * restore the specified user
     */
    public function restore($id) : Response {

        $user_delete = User::onlyTrashed()->where('id', $id)->restore();


        return new Response(['success' => 'Usuario restaurado con éxito'], Response::HTTP_OK);
    }

}
