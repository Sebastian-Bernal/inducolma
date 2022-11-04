@extends('layouts.web')
@section('title', ' Trabajo proceso | Inducolma')

@section('submenu')
@include('modulos.sidebars.costos-side')
@endsection
@section('content')
<div class="div container h-content m-auto">
    <h1 class="text-primary text-center m-auto">Ensamble del pedido Nro. {{ $pedido->id }} / Cliente: {{ $pedido->cliente->nombre }}</h1>
    <h3 class="text-secondary text-center">Producto: {{ $pedido->diseno_producto_final->descripcion }} / cantidad: {{ $pedido->cantidad }}</h3>


    <div class="d-flex flex-wrap row  m-auto align-items-center container-fluid ">
        <div class="container col-xl-6 col-lg-6 col-md-6 col-sm-12 border border-4 border-warning shadow rounded-3 rounded pt-3 mb-2 mt-2">
            <span class="text-warning fw-bold">OPERARIOS:</span><br>
            @forelse ($turno_usuarios as $turno)
                <span> * {{ $turno->user->name }}</span><br>
            @empty
                <span>No se encontraron operarios</span>
            @endforelse

        </div>
        <div class="self-end col-md-6 col-sm-12 col-12">
            @include('modulos.partials.eventos')
        </div>
    </div>
    <div class="col-sm-12 bg-ligth border border-4 border-secondary shadow p-3 m-auto rounded round-3">
        <p>
            <button class="btn btn-secondary rounded rounded-pill "
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseItemsProductos"
                    aria-expanded="false"
                    aria-controls="collapseItemsProductos">
                Ver items e insumos para la fabricacion del producto
            </button>
        </p>
        <div class="collapse" id="collapseItemsProductos">
        <div class="card card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <table id="listaItems" class="table table-bordered table-striped dt-responsive"
                        style="box-sizing: border-box">
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
                <div class="col-md-6">
                    <table id="listainsumos" class="table table-bordered table-striped dt-responsive"
                        style="box-sizing: border-box">
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
        </div>

        <form class="row g-3" id="formProducto"  action="{{ route('trabajo-maquina.store') }}" method="POST">
            @csrf
            {{-- inputs hidden --}}
            <input type="hidden" name="pedido" value="{{ $pedido->id }}">
            <input type="hidden" name="diseno" value="{{ $pedido->diseno_producto_final_id }}">
            <input type="hidden" name="terminar" value="" id="terminar">



            {{-- <div class="col-md-6">
                <label for="tarjetaEntrada" class="form-label">Tarjeta entrada: </label>
                <input type="text"
                        class="form-control"
                        id="tarjetaEntrada"
                        name="tarjetaEntrada">
            </div>
            <div class="col-md-6">
                <label for="tarjetaSalida" class="form-label">Tarjeta salida: </label>
                <input type="text"
                        class="form-control"
                        id="tarjetaSalida"
                        name="tarjetaSalida">
            </div> --}}


            {{-- <div class="form-floating">
                <textarea class="form-control text-uppercase"
                            placeholder="Leave a comment here"
                            id="observacionSubpaqueta"
                            name="observacionSubpaqueta"
                            style="height: 100px"></textarea>
                <label for="floatingTextarea2">Observaciones de producto</label>
            </div> --}}

            <div class="col-sm-12 p-2 m-auto d-flex flex-wrap container-fluid">
                <button type="button"
                        class="btn text-light rounded rounded-pill btn-warning w-100 col-sm-12"
                        onclick="guardarProducto()">Guardar producto</button>
            </div>
        </form>


    </div>
    <hr>
    <button class="btn text-light rounded rounded-pill btn-primary w-100 col-sm-12 mb-4"
                onclick="terminarPedido()">Terminar pedido</button>

</div>

@endsection

@section('js')
<script src="/js/modulos/alertas-swift.js"></script>
<script src="/js/modulos/ensamble.js"></script>
<script src="/js/modulos/partials/eventos.js"></script>
@endsection
