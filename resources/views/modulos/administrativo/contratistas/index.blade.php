@extends('layouts.web')
@section('title', ' Contratista | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content')
    <div class="div container h-content ">
        <div class="row">
            <div class="col-12 col-sm-10 col-lg-6 mx-auto">


                <h1 class="display-6" >Contratistas</h1>
                <hr>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#creacontratista">
                    Crear contratista
                </button>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            - {{ $error }} <br>
                        @endforeach
                    </div>

                @endif
                <!-- Modal Crea maquina-->
                <form action="{{ route('contratistas.store') }}" method="POST">
                    @csrf
                    <div class="modal fade" id="creacontratista" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Crea contratista</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">

                                <div class="card-body">

                                    <div class="row mb-3">
                                        <label for="cedula" class="col-md-4 col-form-label text-md-end">{{ __('Cedula') }}</label>

                                        <div class="col-md-6">
                                            <input  id="cedula"
                                                    type="text"
                                                    class="form-control @error('cedula') is-invalid @enderror text-uppercase"
                                                    name="cedula"
                                                    value="{{ old('cedula') }}"
                                                    required
                                                    autocomplete="cedula"
                                                    autofocus
                                                    >

                                            @error('cedula')
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
                                        <label for="empresa_contratista" class="col-md-4 col-form-label text-md-end">{{ __('Empresa contratista') }}</label>

                                        <div class="col-md-6">
                                            <input  id="empresa_contratista"
                                                    type="empresa_contratista"
                                                    class="form-control @error('empresa_contratista') is-invalid @enderror text-uppercase""
                                                    name="empresa_contratista"
                                                    value="{{ old('empresa_contratista') }}"
                                                    required
                                                    autocomplete="empresa_contratista">
                                            @error('empresa_contratista')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="acceso" class="col-md-4 col-form-label text-md-end">{{ __('Acceso a instalaciones') }}</label>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input"
                                                        type="checkbox"
                                                        role="switch"
                                                        id="acceso"
                                                        name="acceso"
                                                        checked="">

                                            </div>
                                        </div>
                                    </div>


                            </div>


                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar contratista</button>
                            </div>
                        </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Tabla -->

            <table id="listacontratistas" class="table table-bordered table-striped dt-responsive">
                <thead>
                    <tr>
                        <th>Cedula</th>
                        <th>Nombres y Apellidos</th>
                        <th>Empresa contratista</th>
                        <th>Acceso</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($contratistas->chunk(50) as $fila)
                        @foreach ($fila as $contratista)
                        <tr>
                            <td>{{ $contratista->cedula }}</td>
                            <td>{{ $contratista->nombre_completo }}</td>
                            <td>{{ $contratista->empresa_contratista }}</td>
                            <td>{{ $contratista->acceso == true ? 'SI' : 'NO' }}</td>
                            <td>{!! $contratista->deleted_at == '' ? '<span class="badge bg-primary">Activo</span>'  : '<span class="badge bg-secondary">Inactivo</span>' !!}</td>
                            <td>
                                <div class="d-flex align-items-center ">



                                    @if ($contratista->deleted_at == '')
                                        <button class="btn btn-sm btn-danger" onclick="eliminarContratista({{ $contratista }})">
                                            <i class="fa-regular fa-trash-can fa-lg" style="color: black"></i>
                                        </button>
                                        <a href="{{ route('contratistas.show',$contratista) }}" class="btn btn-sm btn-warning">
                                            <i class="fa-solid fa-pen-to-square fa-lg"></i>
                                        </a>
                                    @else
                                        <button class="btn btn-sm btn-secondary" onclick="restaurarContratista({{ $contratista }})">
                                            <i class="fa-solid fa-trash-can-arrow-up" style="color: black"></i>
                                        </button>
                                    @endif

                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @endforeach

                </tbody>
            </table>

        </div>
    </div>

@endsection

@section('js')
<script src="/js/modulos/contratista.js"></script>
@endsection
