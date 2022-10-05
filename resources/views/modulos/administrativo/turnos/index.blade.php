@extends('layouts.web')
@section('title', ' Turnos | Inducolma')

@section('submenu')
@include('modulos.sidebars.costos-side')
@endsection
@section('content')
<div class="div container h-content ">
    <div class="row">
        <div class="col-12 col-sm-10 col-lg-6 mx-auto">


            <h1 class="display-6">Turnos</h1>
            <hr>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#creaUsuario">
                Crear nuevo turno
            </button>
            <a class="btn btn-outline-primary mb-3" href="{{ route('asignar-turnos.index') }}">
                Asignar turno a un empleado
            </a>
            @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                - {{ $error }} <br>
                @endforeach
            </div>

            @endif
            <!-- Modal Crea tipo de madera-->
            <form action="{{ route('turnos.store') }}" method="POST">
                @csrf
                <div class="modal fade" id="creaUsuario" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Crear turno</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <label for="turno" class="col-md-4 col-form-label text-md-end">{{ __('Descripción del turno') }}</label>
                                        <div class="col-md-6">
                                            <input id="turno" type="text"
                                                class="form-control @error('turno') is-invalid @enderror text-uppercase"
                                                name="turno" value="{{ old('turno') }}" required
                                                autocomplete="turno" autofocus onkeyup="mayusculas()">
                                            @error('turno')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="hora_inicio" class="col-md-4 col-form-label text-md-end">{{ __('Hora inicio') }}</label>
                                        <div class="col-md-6">
                                            <input id="hora_inicio" type="time"
                                                class="form-control @error('hora_inicio') is-invalid @enderror text-uppercase"
                                                name="hora_inicio" value="{{ old('hora_inicio') }}" required
                                                autocomplete="hora_inicio" autofocus onkeyup="mayusculas()">
                                            @error('hora_inicio')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="hora_fin" class="col-md-4 col-form-label text-md-end">{{ __('Hora fin') }}</label>
                                        <div class="col-md-6">
                                            <input id="hora_fin" type="time"
                                                class="form-control @error('hora_fin') is-invalid @enderror text-uppercase"
                                                name="hora_fin" value="{{ old('hora_fin') }}" required
                                                autocomplete="hora_fin" autofocus onkeyup="mayusculas()">
                                            @error('hora_fin')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="estado" class="col-md-4 col-form-label text-md-end">{{ __('Estado') }}</label>
                                        <div class="col-md-6">
                                            <select name="estado" id="estado" class="form-control ">

                                                <option value="DISPONIBLE">DISPONIBLE</option>
                                                <option value="NO DISPONIBLE">NO DISPONIBLE</option>
                                            </select>
                                            @error('estado')
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
                                <button type="submit" class="btn btn-primary">Guardar turno</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- Tabla -->

        <table id="listaTipoMadera" class="table table-bordered table-striped dt-responsive">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Descripción</th>
                    <th>Hora inicio</th>
                    <th>Hora fin</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($turnos as $turno)
                <tr>
                    <td>{{ $turno->id }}</td>
                    <td>{{ $turno->turno }}</td>
                    <td>{{ $turno->hora_inicio }}</td>
                    <td>{{ $turno->hora_fin }}</td>
                    <td>{{ $turno->estado }}</td>
                    <td>
                        <div class="d-flex align-items-center ">

                            <button class="btn btn-sm btn-danger" onclick="eliminarTurno({{ $turno}})">
                                <i class="fa-regular fa-trash-can fa-lg" style="color: black"></i>
                            </button>
                            <a href="{{ route('turnos.edit',$turno) }}" class="btn btn-sm btn-warning">
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
<script src="/js/modulos/turnos.js"></script>
@endsection
