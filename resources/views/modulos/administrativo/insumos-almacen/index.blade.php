@extends('layouts.web')
@section('title', ' Insumos almacen | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content')
    <div class="div container h-content ">
        <div class="row">
            <div class="col-12 col-sm-10 col-lg-6 mx-auto">


                <h1 class="display-6" >Insumos almacen</h1>
                <hr>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#creacliente">
                    Crear insumo
                </button>
                <a href="{{ route('disenos.index') }}" class="btn btn-outline-primary mb-3">Ir a diseños</a>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            - {{ $error }} <br>
                        @endforeach
                    </div>

                @endif
                <!-- Modal Crea maquina-->
                <form action="{{ route('insumos-almacen.store') }}" method="POST">
                    @csrf
                    <div class="modal fade" id="creacliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Crea insumo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">

                                <div class="card-body">

                                    <div class="row mb-3">
                                        <label for="descripcion" class="col-md-4 col-form-label text-md-end">{{ __('Descripción') }}</label>
                                        <div class="col-md-6">
                                            <input id="descripcion"
                                                    type="text"
                                                    class="form-control @error('descripcion') is-invalid @enderror text-uppercase"
                                                    style="text-transform: uppercase;"
                                                    name="descripcion" value="{{ old('descripcion') }}"
                                                    required autocomplete="descripcion" autofocus
                                                    onkeyup="mayusculas()">

                                            @error('descripcion')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="cantidad" class="col-md-4 col-form-label text-md-end">{{ __('Cantidad') }}</label>
                                        <div class="col-md-6">
                                            <input id="cantidad"
                                                    type="number"
                                                    class="form-control @error('cantidad') is-invalid @enderror text-uppercase"
                                                    style="text-transform: uppercase;"
                                                    name="cantidad" value="{{ old('cantidad') }}"
                                                    required autocomplete="cantidad" autofocus
                                                    onkeyup="mayusculas()">

                                            @error('cantidad')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="precio_unitario" class="col-md-4 col-form-label text-md-end">{{ __('Precio unitario') }}</label>
                                        <div class="col-md-6">
                                            <input id="precio_unitario"
                                                    type="number"
                                                    class="form-control @error('precio_unitario') is-invalid @enderror text-uppercase"
                                                    style="text-transform: uppercase;"
                                                    name="precio_unitario" value="{{ old('precio_unitario') }}"
                                                    required autocomplete="precio_unitario" autofocus
                                                    onkeyup="mayusculas()">

                                            @error('precio_unitario')
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
                            <button type="submit" class="btn btn-primary">Guardar insumo</button>
                            </div>
                        </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Tabla -->

            <table id="listainsumos" class="table table-bordered table-striped dt-responsive">
                <thead>
                    <tr>
                        <th>Descripci&oacute;n</th>
                        <th>Cantidad</th>
                        <th>Precio unitario</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($insumos as $insumo)
                        <tr>
                            <td>{{ $insumo->descripcion }}</td>
                            <td>{{ $insumo->cantidad }}</td>
                            <td>{{ $insumo->precio_unitario }}</td>
                            <td>{!! $insumo->deleted_at == '' ? '<span class="badge bg-primary">Activo</span>'  : '<span class="badge bg-secondary">Inactivo</span>' !!}</td>
                            <td>
                                <div class="d-flex align-items-center ">

                                    @if ($insumo->deleted_at == '')
                                        <button class="btn btn-sm btn-danger" onclick="eliminarInsumo({{ $insumo }})">
                                            <i class="fa-regular fa-trash-can fa-lg" style="color: black"></i>
                                        </button>
                                        <a href="{{ route('insumos-almacen.show',$insumo) }}" class="btn btn-sm btn-warning">
                                            <i class="fa-solid fa-pen-to-square fa-lg"></i>
                                        </a>
                                    @else
                                        <button class="btn btn-sm btn-secondary" onclick="restaurarInsumo({{ $insumo }})">
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
<script src="/js/modulos/insumos.js"></script>
@endsection
