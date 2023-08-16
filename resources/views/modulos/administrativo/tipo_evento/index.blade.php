@extends('layouts.web')
@section('title', ' Tipo tipo_evento | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content')
    <div class="div container h-content ">
        <div class="row">
            <div class="col-12 col-sm-10 col-lg-6 mx-auto">


                <h1 class="display-6" >Tipo Evento</h1>
                <hr>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#creacliente">
                    Crear Evento
                </button>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            - {{ $error }} <br>
                        @endforeach
                    </div>

                @endif
                <!-- Modal Crea maquina-->
                <form action="{{ route('tipo-eventos.store') }}" method="POST">
                    @csrf
                    <div class="modal fade" id="creacliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Crea tipo evento</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">

                                <div class="card-body">

                                    <div class="row mb-3">
                                        <label for="tipo_evento" class="col-md-4 col-form-label text-md-end">{{ __('Descripción') }}</label>

                                        <div class="col-md-6">
                                            <input id="tipo_evento"
                                                type="text"
                                                class="form-control @error('tipo_evento') is-invalid @enderror text-uppercase"
                                                name="tipo_evento"
                                                value="{{ old('tipo_evento') }}"
                                                required autocomplete="tipo_evento"
                                                autofocus
                                                onkeyup="mayusculas()">

                                            @error('tipo_evento')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar tipo evento</button>
                            </div>
                        </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Tabla -->

            <table id="listatipo_eventos" class="table table-bordered table-striped dt-responsive">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Descripci&oacute;n</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($tipo_eventos as $tipo_evento)
                        <tr>
                            <td>{{ $tipo_evento->id }}</td>
                            <td>{{ $tipo_evento->tipo_evento }}</td>



                            <td>
                                <div class="d-flex align-items-center ">

                                    @if (!in_array($tipo_evento->id, [1,2]))
                                        <button class="btn btn-sm btn-danger" onclick="eliminarTipoEvento({{ $tipo_evento }})">
                                            <i class="fa-regular fa-trash-can fa-lg" style="color: black"></i>
                                        </button>
                                    @endif
                                    <a href="{{ route('tipo-eventos.show',$tipo_evento) }}" class="btn btn-sm btn-warning">
                                        <i class="fa-solid fa-pen-to-square fa-lg"></i>
                                    </a>

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
<script src="/js/modulos/tipo_eventos.js"></script>
@endsection
