@extends('layouts.web')
@section('title', ' Usuarios | inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content')
<div class="div container h-content ">
    <div class="row">
        <div class="col-12 col-sm-10 col-lg-6 mx-auto">


            <h1 class="display-6" >Usuarios</h1>
            <hr>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#creaUsuario">
                Crear usuario
            </button>
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        - {{ $error }} <br>
                    @endforeach
                </div>

            @endif
            <!-- Modal Crea maquina-->
            <form action="{{ route('usuarios.store') }}" method="POST">
                @csrf
                <div class="modal fade" id="creaUsuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Crea Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="card-body">

                                <div class="row mb-3">
                                    <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Identificacion') }}</label>

                                    <div class="col-md-6">
                                        <input id="identificacionUsuario" type="text" class="form-control @error('identificacionUsuario') is-invalid @enderror" name="identificacionUsuario" value="{{ old('identificacionUsuario') }}" required autocomplete="identificacionUsuario" autofocus>

                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="primer_nombre" class="col-md-4 col-form-label text-md-end">{{ __('Primer nombre') }}</label>

                                    <div class="col-md-6">
                                        <input  id="primer_nombre"
                                                type="text"
                                                class="form-control @error('primer_nombre') is-invalid @enderror text-uppercase"
                                                name="primer_nombre"
                                                value="{{ old('primer_nombre') }}"
                                                required
                                                autocomplete="primer_nombre"
                                                autofocus>
                                        @error('primer_nombre')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="segundo_nombre" class="col-md-4 col-form-label text-md-end">{{ __('Segundo nombre') }}</label>

                                    <div class="col-md-6">
                                        <input  id="segundo_nombre"
                                                type="text"
                                                class="form-control @error('segundo_nombre') is-invalid @enderror text-uppercase"
                                                name="segundo_nombre"
                                                value="{{ old('segundo_nombre') }}"

                                                autocomplete="segundo_nombre"
                                                autofocus>
                                        @error('segundo_nombre')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="primer_apellido" class="col-md-4 col-form-label text-md-end">{{ __('Primer apellido') }}</label>
                                    <div class="col-md-6">
                                        <input  id="primer_apellido"
                                                type="text"
                                                class="form-control @error('primer_apellido') is-invalid @enderror text-uppercase"
                                                name="primer_apellido"
                                                value="{{ old('primer_apellido') }}"
                                                required
                                                autocomplete="primer_apellido">

                                        @error('primer_apellido')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="segundo_apellido" class="col-md-4 col-form-label text-md-end">{{ __('Segundo apellido') }}</label>

                                    <div class="col-md-6">
                                        <input  id="segundo_apellido"
                                                type="text"
                                                class="form-control @error('segundo_apellido') is-invalid @enderror text-uppercase"
                                                name="segundo_apellido"
                                                value="{{ old('segundo_apellido') }}"

                                                autocomplete="segundo_apellido">
                                        @error('segundo_apellido')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email') }}</label>
                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="rolUsuario" class="col-md-4 col-form-label text-md-end">{{ __('Rol') }}</label>
                                    <div class="col-md-6">
                                        <select class="form-select" name="rolUsuario" required >
                                            @foreach ($roles as $rol)
                                                <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="pagoMesAnterior" class="col-md-4 col-form-label text-md-end">{{ __('Pago mes anterior') }}</label>
                                    <div class="col-md-6">
                                        <input id="pagoMesAnterior"
                                                type="number"
                                                class="form-control @error('pagoMesAnterior') is-invalid @enderror" name="pagoMesAnterior"
                                                value="{{ old('pagoMesAnterior') }}"
                                                min="0"
                                                max="10000000"

                                                autocomplete="pagoMesAnterior">

                                        @error('pagoMesAnterior')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="pagoEstandar" class="col-md-4 col-form-label text-md-end">{{ __('Pago estandar') }}</label>
                                    <div class="col-md-6">
                                        <input id="pagoEstandar"
                                                type="number"
                                                class="form-control @error('pagoEstandar') is-invalid @enderror" name="pagoEstandar"
                                                value="{{ old('pagoEstandar') }}"
                                                min="100000"
                                                max="10000000"
                                                required
                                                autocomplete="pagoEstandar">

                                        @error('pagoEstandar')
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
                        <button type="submit" class="btn btn-primary">Guardar usuario</button>
                        </div>
                    </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- Tabla -->

        <table id="listaUsuarios" class="table table-bordered table-striped dt-responsive">
            <thead>
                <tr>
                    <th>Identificacion</th>
                    <th>Nombres</th>
                    <th>{{ __('Email') }}</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->identificacion }}</td>
                        <td>{{ $usuario->name }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ isset($usuario->roll->nombre) ? $usuario->roll->nombre : 'sin rol asignado' }}</td>
                        <td>{!! $usuario->deleted_at == '' ? '<span class="badge bg-primary">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>' !!}</td>
                        <td>
                            <div class="d-flex align-items-center ">

                                @if ($usuario->deleted_at == '')
                                    <button class="btn btn-sm btn-danger" onclick="eliminarUsuario({{ $usuario->id }},'{{ $usuario->name }}')">
                                        <i class="fa-regular fa-trash-can fa-lg" style="color: black"></i>
                                    </button>
                                    <a href="{{ route('usuarios.show',$usuario) }}" class="btn btn-sm btn-warning">
                                        <i class="fa-solid fa-pen-to-square fa-lg"></i>
                                    </a>
                                @else
                                    <button class="btn btn-sm btn-secondary" onclick="restaurarUsuario({{ $usuario->id}},'{{ $usuario->name }}')">
                                        <i class="fa-solid fa-trash-can-arrow-up" style="color: black"></i>
                                    </button>
                                @endif


                            </div>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>

@endsection

@section('js')
<script src="/js/modulos/usuarios.js"></script>
@endsection
