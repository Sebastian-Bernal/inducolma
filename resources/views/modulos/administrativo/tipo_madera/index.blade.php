@extends('layouts.web')
@section('title', ' Maderas | Inducolma')

@section('submenu')
@include('modulos.sidebars.costos-side')
@endsection
@section('content')
<div class="div container h-content ">
    <div class="row">
        <div class="col-12 col-sm-10 col-lg-6 mx-auto">


            <h1 class="display-6">Tipos de madera</h1>
            <hr>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#creaUsuario">
                Crear nuevo tipo de madera
            </button>
            @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                - {{ $error }} <br>
                @endforeach
            </div>

            @endif
            <!-- Modal Crea tipo de madera-->
            <form action="{{ route('tipos-maderas.store') }}" method="POST">
                @csrf
                <div class="modal fade" id="creaUsuario" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Crear tipo madera</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <label for="descripcion" class="col-md-4 col-form-label text-md-end">{{ __('Tipo
                                            de madera') }}</label>
                                        <div class="col-md-6">
                                            <input id="descripcion" type="text"
                                                class="form-control @error('descripcion') is-invalid @enderror text-uppercase"
                                                name="descripcion" value="{{ old('descripcion') }}" required
                                                autocomplete="descripcion" autofocus onkeyup="mayusculas()">
                                            @error('descripcion')
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
                                <button type="submit" class="btn btn-primary">Guardar tipo de madera</button>
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
                    <th>Tipo de madera </th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($tiposMadera as $madera)
                <tr>
                    <td>{{ $madera->id }}</td>
                    <td>{{ $madera->descripcion }}</td>
                    <td>
                        <div class="d-flex align-items-center ">

                            <button class="btn btn-sm btn-danger" onclick="eliminarTipoMadera({{ $madera}})">
                                <i class="fa-regular fa-trash-can fa-lg" style="color: black"></i>
                            </button>
                            <a href="{{ route('tipos-maderas.edit',$madera) }}" class="btn btn-sm btn-warning">
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
    <a href="{{ route('maderas.index') }}" class="btn btn-outline-success btn-sm">Maderas</a>
</div>

@endsection

@section('js')
<script src="/js/modulos/tipo_maderas.js"></script>
@endsection
