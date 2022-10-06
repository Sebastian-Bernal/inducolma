@extends('layouts.web')
@section('title', ' Turnos | Inducolma')

@section('submenu')
@include('modulos.sidebars.costos-side')
@endsection
@section('content')
<div class="div container h-content ">
    <div class="row">
        <div class="col-12 col-sm-10 col-lg-6 mx-auto">


            <h1 class="display-6">Asignar turnos a empleado</h1>
            <hr>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#creaUsuario">
                Asignar turno a un empleado
            </button>
            @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                - {{ $error }} <br>
                @endforeach
            </div>

            @endif
            <!-- Modal Crea tipo de madera-->
            <form action="{{ route('asignar-turnos.store') }}" method="POST" id="formAsignarTurno">
                @csrf
                <div class="modal fade" id="creaUsuario" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Asignar turno</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <label for="usuario" class="col-md-4 col-form-label text-md-end">{{ __('Empleado') }}</label>
                                        <div class="col-md-6">
                                            <select name="usuario" id="usuario" class="form-control ">
                                                @forelse ($usuarios as $usuario)
                                                    <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                                @empty
                                                    <option value="">No se encontro empleados con rol operario o de recepcion</option>
                                                @endforelse
                                            </select>
                                            @error('usuario')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="turno" class="col-md-4 col-form-label text-md-end">{{ __('Turno') }}</label>
                                        <div class="col-md-6">
                                            <select name="turno" id="turno" class="form-control ">
                                                @forelse ($turnos as $turno)
                                                    <option value="{{ $turno->id }}">{{ $turno->turno }}</option>
                                                @empty
                                                    <option value="">No se encontraron turnos</option>
                                                @endforelse
                                            </select>
                                            @error('turno')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="maquina" class="col-md-4 col-form-label text-md-end">{{ __('Maquina') }}</label>
                                        <div class="col-md-6">
                                            <select name="maquina" id="maquina" class="form-control ">
                                                @forelse ($maquinas as $maquina)
                                                    <option value="{{ $maquina->id }}">{{ $maquina->maquina }}</option>
                                                @empty
                                                    <option value="">No se encontraron maquinas</option>
                                                @endforelse
                                            </select>
                                            @error('maquina')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="desde" class="col-md-4 col-form-label text-md-end">{{ __('Desde') }}</label>
                                        <div class="col-md-6">
                                            <input id="desde" type="date"
                                                class="form-control @error('desde') is-invalid @enderror text-uppercase"
                                                name="desde" value="{{ old('desde') }}"
                                                min='{{ date('Y-m-d', strtotime('1 days')) }}'
                                                required
                                                autocomplete="desde" autofocus onkeyup="mayusculas()">
                                            @error('desde')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="hasta" class="col-md-4 col-form-label text-md-end">{{ __('hasta') }}</label>
                                        <div class="col-md-6">
                                            <input id="hasta" type="date"
                                                class="form-control @error('hasta') is-invalid @enderror text-uppercase"
                                                name="hasta" value="{{ old('hasta') }}"
                                                required
                                                min='{{ date('Y-m-d', strtotime('1 days')) }}'
                                                autocomplete="hasta" autofocus onkeyup="mayusculas()">
                                            @error('hasta')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-primary" onclick="comprobarTurno()">Asignar turno</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- Tabla -->

        <table id="listaTurnosAsignados" class="table table-bordered table-striped dt-responsive">
            <thead>
                <tr>

                    <th>Usuario</th>
                    <th>Turno</th>
                    <th>Maquina</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($turnos_usuarios as $turno_usuario)
                <tr>
                    <td>{{ $turno_usuario->user->name }}</td>
                    <td>{{ $turno_usuario->turno->turno }}</td>
                    <td>{{ $turno_usuario->maquina->maquina }}</td>
                    <td>{{ $turno_usuario->fecha }}</td>

                    <td>

                        <div class="d-flex align-items-center ">

                            <button class="btn btn-sm btn-danger" onclick="eliminarTurnoAsignado({{ $turno_usuario }})">
                                <i class="fa-regular fa-trash-can fa-lg" style="color: black"></i>
                            </button>
                            <a href="{{ route('asignar-turnos.edit', $turno_usuario) }}" class="btn btn-sm btn-warning">
                                <i class="fa-solid fa-pen-to-square fa-lg"></i>
                            </a>

                        </div>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>
    <hr>

</div>

@endsection

@section('js')
<script src="/js/modulos/turnos-asignados.js"></script>
@endsection
