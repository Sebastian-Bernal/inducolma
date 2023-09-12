@extends('layouts.web')
@section('title', ' Pedidos | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content')
    <div class="div container h-content ">
        <div class="row">
            <div class="col-12 col-sm-10 col-lg-6 mx-auto">


                <h1 class="display-8" >Ordenes de producción </h1>
                <hr>
                <!-- Button trigger modal -->
                <button type="button"
                        class="btn btn-primary mb-3"
                        data-bs-toggle="modal"
                        data-bs-target="#creapedido"
                        id="btnpedido">
                    Agregar a inventario
                </button>

                <button type="button"
                        class="btn btn-outline-primary  mb-3 "
                        data-bs-toggle="modal"
                        data-bs-target="#creadiseno"
                        id="btnAsignar">
                    Ver ordenes en producción
                </button>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            - {{ $error }} <br>
                        @endforeach
                    </div>

                @endif

                <!-- Modal ordenes de produccion-->
                <form id="formAsignar" >
                    <div class="modal fade" id="creadiseno" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-fullscreen">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Ordenes en producción</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">

                                <div class="card-body">
                                    <table class="table table-striped table-bordered table-hover text-center">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Pedido</th>
                                                <th scope="col">Producto</th>
                                                <th scope="col">Item</th>
                                                <th scope="col">Cantidad</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($ordenes as $orden)
                                                <tr>
                                                    <td>{{ $orden->id }}</td>
                                                    <td>{{ $orden->pedido->cliente->nombre }}</td>
                                                    <td>{{ $orden->pedido->diseno_producto_final->descripcion }}</td>
                                                    <td>{{ $orden->item->descripcion }}</td>
                                                    <td>{{ $orden->cantidad }}</td>
                                                    <td>{{ $orden->estado }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5">No hay ordenes en producción</td>
                                                </tr>
                                            @endforelse
                                    </table>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                        </div>
                    </div>
                </form>
                <!-- Modal Crea pedido-->
                <form action="{{ route('pedidos.store') }}" method="POST">
                    @csrf
                    <div class="modal fade" id="creapedido" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Crear pedido</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <label for="cliente" class="col-md-4 col-form-label text-md-end">{{ __('Cliente') }}</label>
                                            <div class="col-md-6">
                                                <select id="cliente"
                                                        type="number"
                                                        class="form-control @error('cliente') is-invalid @enderror"
                                                        name="cliente"
                                                        required
                                                        onchange=""
                                                        >
                                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>

                                                </select>

                                                @error('cliente')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="items" class="col-md-4 col-form-label text-md-end">{{ __('items') }}</label>
                                            <div class="col-md-6">
                                                <div class="d-flex justifu-content-between">
                                                    <select id="items"
                                                        class="form-control @error('items') is-invalid @enderror text-uppercase"
                                                        name="items" value="{{ old('items') }}"
                                                        required autocomplete="items" autofocus
                                                        >
                                                        @forelse ($disenos as $diseno)
                                                            <option value="{{ $diseno->id }}">{{ $diseno->descripcion }}</option>

                                                        @empty
                                                            <option value="">No hay diseños</option>
                                                        @endforelse
                                                    </select>

                                                    <div class="d-flex justify-content-center" id="spitems">
                                                    </div>
                                                </div>
                                                @error('items')
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
                                                        name="cantidad" value="{{ old('cantidad') }}"
                                                        required autocomplete="cantidad" autofocus
                                                        min="0"
                                                        max="10000">

                                                @error('cantidad')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="fecha_entrega" class="col-md-4 col-form-label text-md-end">{{ __('Fecha de entrega') }}</label>
                                            <div class="col-md-6">
                                                <input id="fecha_entrega"
                                                        type="date"
                                                        class="form-control @error('fecha_entrega') is-invalid @enderror text-uppercase"
                                                        min="{{ date('Y-m-j', strtotime('10 weekdays')) }}"
                                                        name="fecha_entrega" value="{{ old('fecha_entrega') }}"
                                                        required autocomplete="fecha_entrega" autofocus
                                                        >

                                                @error('fecha_entrega')
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
                                    <button type="submit" class="btn btn-primary">Guardar pedido</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Lista de pedidos -->
            <h3>Pedidos</h3>
            <div class="row row-cols-1 row-cols-md-3 g-4">


                @forelse ($pedidos as $pedido)

                    <div class="col">
                        <div class="card ">
                        <div class="card-header {{ $pedido->dias < 5 ? 'bg-danger' : 'bg-warning ' }}"><strong>Pedido #{{ $pedido->id }}</strong> </div>
                        <div class="card-body " >
                            <h5 class="card-title">{{ $pedido->razon_social }}</h5>
                            <p class="card-text mb-1">
                                Producto: {{ $pedido->descripcion }} <br>
                                Cantidad: {{ $pedido->cantidad }} <br>
                                Fecha de entrega: {{ $pedido->fecha_entrega }} <br>
                                <h5 class="{{ $pedido->dias < 5 ? 'text-danger' : 'text-warning' }} mt-0">Dias restantes para la entrega: {{ $pedido->dias }}</h5>
                            </p>


                            <a href="{{ route('programaciones.show',$pedido->id) }}" class="btn btn-primary btn-sm">Programar</a>

                        </div>
                        <div class="card-footer text-warning ">
                            <small class="text-muted">{{ $pedido->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                @empty

                @endforelse

            </div>
        </div>
    </div>

@endsection

@section('js')
<script src="/js/modulos/optimas.js"></script>
@endsection
