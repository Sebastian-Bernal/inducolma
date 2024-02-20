@extends('layouts.web')
@section('title', ' Trabajo proceso | Inducolma')

@section('submenu')
@include('modulos.sidebars.costos-side')
@endsection
@section('content')
<div class="container h-content m-auto">
    <div class="container">
        <div class="card  shadow p-1 mb-3 bg-body rounded">
            <div class="card-body">
                <div class="text-center">
                    <h1 class="h4 text-primary mb-3 display-6 ">{{ strtolower($ensamble->maquina->corte) }} del Pedido Nro. {{ $pedido->id }}</h1>
                    <h3 class="h6 text-secondary mb-3"><strong>Cliente:</strong> {{ $pedido->cliente->razon_social }}</h3>
                    <h3 class="h6 text-secondary mb-3"><strong>Producto:</strong> {{ $pedido->diseno_producto_final->descripcion }}</h3>
                    <h3 class="h6 text-secondary mb-3"><strong>Cantidad:</strong> {{ $ensamble->cantidad }}</h3>
                    <h3 class="h6 text-secondary mb-3"><strong>Observaciones del Ensamble:</strong> {{ $ensamble->observaciones }}</h3>
                </div>
            </div>
        </div>
    </div>


    <div class="row justify-content-center align-items-center">
        @if ($turno_usuarios->isNotEmpty())
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <h3 class="text-warning mb-3">M&aacute;quina: {{ $turno_usuarios->first()->maquina->maquina }}</h3>
                <div class="border border-4 border-warning shadow rounded p-3">
                    <h5 class="text-warning fw-bold mb-3">OPERARIOS:</h5>
                    @forelse ($turno_usuarios as $turno)
                        <p>* {{ $turno->user->name }}</p>
                    @empty
                        <p>No se encontraron operarios</p>
                    @endforelse
                </div>
            </div>
        @else
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <p class="text-center">No hay turnos</p>
            </div>
        @endif

        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mt-3 mt-md-0">
            @include('modulos.partials.eventos')
        </div>
    </div>

    <div class="bg-light border border-4 border-secondary shadow p-3 rounded mt-4">
        <p>
            <button class="btn btn-secondary rounded-pill" type="button" data-bs-toggle="collapse" data-bs-target="#collapseItemsProductos" aria-expanded="false" aria-controls="collapseItemsProductos">
                Ver items e insumos para la fabricaci&oacute;n del producto
            </button>
        </p>
        <div class="collapse" id="collapseItemsProductos">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <h4>Items:</h4>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pedido->items_pedido as $item)
                                <tr>
                                    <td>{{ $item->descripcion }}</td>
                                    <td>{{ $item->cantidad <= 0 ? 0 : $item->cantidad }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6 mb-3">
                    <h4>Insumos:</h4>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Insumo</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pedido->diseno_producto_final->insumos as $insumo)
                                <tr>
                                    <td>{{ $insumo->descripcion }}</td>
                                    <td>{{ $insumo->cantidad <= 0 ? 0 : $insumo->cantidad }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('trabajo-maquina.store') }}" method="POST" id="formProducto">
            @csrf
            <input type="hidden" name="pedido" value="{{ $pedido->id }}">
            <input type="hidden" name="diseno" value="{{ $pedido->diseno_producto_final_id }}">
            <input type="hidden" name="terminar" value="" id="terminar">
            <input type="hidden" name="ensambeAcabadoId" value="{{ $ensamble->id }}">
            <input type="hidden" name="maquinaId" value="{{ $maquina }}">

            <div class="input-group input-group-lg">
                <span class="input-group-text" id="inputGroup-sizing-lg">CANTIDAD: </span>
                <input type="number"
                        class="form-control"
                        aria-label="Sizing example input"
                        aria-describedby="inputGroup-sizing-lg"
                        id="cantidad"
                        name="cantidad"
                        min="1"
                        max="999"
                        value="{{ old('cantidad', 1) }}"
                        >
            </div>
            <div class="row mt-4">
                <div class="col-md-12 mb-3">
                    <button type="button" class="btn btn-warning w-100" onclick="guardarProducto()">Guardar producto</button>
                </div>
            </div>
        </form>
    </div>

    <hr>

    {{-- <button class="btn btn-primary w-100" onclick="terminarPedido()">Terminar Ensamble</button> --}}
</div>


@endsection

@section('js')
<script src="/js/modulos/alertas-swift.js"></script>
<script src="/js/modulos/ensamble.js"></script>
<script src="/js/modulos/partials/eventos.js"></script>
@endsection
