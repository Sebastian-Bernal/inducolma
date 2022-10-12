@extends('layouts.web')
@section('title', ' Trabajo maquina | Inducolma')

@section('submenu')
@include('modulos.sidebars.costos-side')
@endsection
@section('content')
<div class="container h-content ">

    <button style="display:none" id="users" onclick="listaUser({{ count($turno_usuarios) > 0 ? $turno_usuarios : ''}})"></button>

<div class="d-flex flex-wrap justify-content-center container-fluid">
    {{-- PROCESO --}}
    <div id="maquinas" class="container card  bg-light shadow m-2 rounded-3">
        <div class=" card-header bg-primary text-white">
            Proceso
        </div>
        <div class=" card-body">

            <div class="text-center">
                <label> Maquina a trabajar </label>
            </div>

            <div class="input-group mb-3 mt-3">

                <label class="input-group-text" for="maquina">Maquina</label>
                <select class="form-select" id="maquina">
                    <option value="0">Seleccionar...</option>
                    @forelse ($maquinas as $maquina)
                    <option value="{{ $maquina->id }}" {{ $maquina->id == $turno->maquina_id ? 'selected' : '' }}>{{ $maquina->maquina
                        }}</option>


                    @empty
                    <option>No existen maquinas creadas</option>
                    @endforelse
                </select>

            </div>
            <div>
                <button type="button" class="text-white btn btn-warning container-fluid"
                    onclick="confirmaMaquina({{ $turno }})">Confirma maquina a trabajar</button>
            </div>



        </div>


    </div>
    {{-- AUXILIARES --}}
    <div class="container card  bg-light shadow m-3 rounded-3">
        <div class=" card-header bg-secondary text-white">
            Auxiliares
        </div>
        <div class=" card-body">

            <div class="text-center">
                <label> Usuarios auxiliares en este proceso </label>
            </div>
            <div class="text-center" id="spinnerAuxiliares">

            </div>

            <div class="input-group mb-3 mt-3">

                <table class="table table-striped">
                    <thead>
                        <tr>

                            <th scope="col">Usuario</th>

                            <th scope="col">Acciones</th>

                        </tr>
                    </thead>
                    <tbody id="listarUsers">

                    </tbody>
                </table>

            </div>

            <div>
                <button type="button" class="text-white btn btn-danger container-fluid"
                    data-bs-toggle="modal" data-bs-target="#modalCambioUsuario"
                >Seleccionar otro auxiliar para esta maquina</button>
                <!-- Modal -->
                <div class="modal fade" id="modalCambioUsuario" tabindex="-1" aria-labelledby="modalCambioUsuarioLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="modalCambioUsuarioLabel">Usuarios</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <select class="form-select" id="auxiliar" name="auxiliar">
                                @forelse ($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                @empty
                                <option>No se encontraron usuarios</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" onclick="seleccionarAuxiliar({{ $turno->maquina_id. ',' .$turno->turno_id }})">Seleccionar usuario</button>
                        </div>
                    </div>
                    </div>
                </div>
            </div>



        </div>


    </div>
</div>



</div>

@endsection

@section('js')
<script src="/js/modulos/alertas-swift.js"></script>
<script src="/js/modulos/maquina_inicia.js"></script>
@endsection
