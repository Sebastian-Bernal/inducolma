@extends('layouts.web')
@section('title', ' Clientes | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content')
    <div class="div container h-content ">
        <div class="row">
            <div class="col-12 col-sm-10 col-lg-6 mx-auto">


                <h1 class="display-6" >clientes</h1>
                <hr>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#creacliente">
                    Crear cliente
                </button>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            - {{ $error }} <br>
                        @endforeach
                    </div>

                @endif
                <!-- Modal Crea maquina-->
                <form action="{{ route('clientes.store') }}" method="POST">
                    @csrf
                    <div class="modal fade" id="creacliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Crea cliente</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">

                                <div class="card-body">

                                    <div class="row mb-3">
                                        <label for="nit" class="col-md-4 col-form-label text-md-end">{{ __('Nit o cedula') }}</label>

                                        <div class="col-md-6">
                                            <input  id="nit"
                                                    type="text"
                                                    class="form-control @error('nit') is-invalid @enderror text-uppercase"
                                                    name="nit"
                                                    value="{{ old('nit') }}"
                                                    required
                                                    autocomplete="nit"
                                                    autofocus
                                                    >

                                            @error('nit')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="nombre" class="col-md-4 col-form-label text-md-end">{{ __('Representante legal') }}</label>

                                        <div class="col-md-6">
                                            <input  id="nombre"
                                                    type="text"
                                                    class="form-control @error('nombre') is-invalid @enderror text-uppercase"
                                                    name="nombre"
                                                    value="{{ old('nombre') }}"
                                                    required
                                                    autocomplete="nombre"
                                                    autofocus>
                                            @error('nombre')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="razon_social" class="col-md-4 col-form-label text-md-end">{{ __('Razon social') }}</label>

                                        <div class="col-md-6">
                                            <input  id="razon_social"
                                                    type="text"
                                                    class="form-control @error('razon_social') is-invalid @enderror text-uppercase"
                                                    name="razon_social"
                                                    value="{{ old('razon_social') }}"
                                                    required
                                                    autocomplete="razon_social"
                                                    autofocus>
                                            @error('razon_social')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="direccion" class="col-md-4 col-form-label text-md-end">{{ __('Dirección') }}</label>
                                        <div class="col-md-6">
                                            <input  id="direccion"
                                                    type="text"
                                                    class="form-control @error('direccion') is-invalid @enderror text-uppercase"
                                                    name="direccion"
                                                    value="{{ old('direccion') }}"
                                                    required
                                                    autocomplete="direccion">

                                            @error('direccion')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="telefono" class="col-md-4 col-form-label text-md-end">{{ __('Teléfono') }}</label>

                                        <div class="col-md-6">
                                            <input  id="telefono"
                                                    type="number"
                                                    class="form-control @error('telefono') is-invalid @enderror text-uppercase"
                                                    name="telefono"
                                                    value="{{ old('telefono') }}"
                                                    required
                                                    autocomplete="telefono">
                                            @error('telefono')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Correo electrónico') }}</label>

                                        <div class="col-md-6">
                                            <input  id="email"
                                                    type="email"
                                                    class="form-control @error('email') is-invalid @enderror "
                                                    name="email"
                                                    value="{{ old('email') }}"
                                                    required
                                                    autocomplete="email">
                                            @error('email')
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
                            <button type="submit" class="btn btn-primary">Guardar cliente</button>
                            </div>
                        </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Tabla -->

            <table id="listaclientes" class="table table-bordered table-striped dt-responsive">
                <thead>
                    <tr>
                        <th>Nit</th>
                        <th>Nombre</th>
                        <th>Razón social</th>
                        <th>Direcci&oacute;n</th>
                        <th>Tel&eacute;fono</th>
                        <th>direccion</th>
                        <th>Correo electr&oacute;nico</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($clientes as $cliente)
                        <tr>
                            <td>{{ $cliente->nit }}</td>
                            <td>{{ $cliente->nombre }}</td>
                            <td>{{ $cliente->razon_social }}</td>
                            <td>{{ $cliente->direccion }}</td>
                            <td>{{ $cliente->telefono }}</td>
                            <td>{{ $cliente->direccion }}</td>
                            <td>{{ $cliente->email }}</td>
                            <td>{!! $cliente->deleted_at == '' ? '<span class="badge bg-primary">Activo</span>'  : '<span class="badge bg-secondary">Inactivo</span>' !!}</td>
                            <td>
                                <div class="d-flex align-items-center ">

                                    @if ($cliente->deleted_at == '')
                                        <a  href="{{ route('clientes.show',$cliente->id) }}"
                                                class="btn btn-primary btn-sm m-1"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Ver cliente">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        <button class="btn btn-sm btn-danger" onclick="eliminarCliente({{ $cliente }})">
                                            <i class="fa-regular fa-trash-can fa-lg" style="color: black"></i>
                                        </button>

                                        <a href="{{ route('clientes.edit',$cliente) }}" class="btn btn-sm btn-warning">
                                            <i class="fa-solid fa-pen-to-square fa-lg"></i>
                                        </a>
                                    @else
                                        <button class="btn btn-sm btn-secondary" onclick="restaurarCliente({{ $cliente }})">
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

<script src="/js/modulos/clientes.js"></script>
@endsection
