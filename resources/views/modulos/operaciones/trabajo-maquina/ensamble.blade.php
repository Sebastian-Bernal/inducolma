@extends('layouts.web')
@section('title', ' Trabajo ensamble | Inducolma')

@section('submenu')
@include('modulos.sidebars.costos-side')
@endsection
@section('content')
<div class="div container h-content m-auto">
    <div class="d-flex flex-wrap row  m-auto align-items-center container-fluid ">
        <div
            class="container col-xl-12 col-lg-12 col-md-12 col-sm-12 bg-light shadow rounded-3 min-vh-100 mb-2 mt-2 ">

            <div class="text-primary">
                <h1 class="display-6">Pedidos listos para ensamblar</h1>
                <hr>
            </div>


            <!-- Tabla -->
            <div>
                <table id="listaOrdenes" class="table table-bordered table-striped dt-responsive"
                    style="box-sizing: border-box">
                    <thead>
                        <tr>
                            <th># pedido</th>
                            <th>Cliente</th>
                            <th>Cantidad</th>
                            <th>Producto</th>
                            <th>Fecha entrega</th>

                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($pedidos_ordenes as $pedido)
                        <tr>
                            <td>{{ $pedido->id }}</td>
                            <td>{{ $pedido->cliente->nombre }}</td>
                            <td>{{ $pedido->cantidad }}</td>
                            <td>{{ $pedido->diseno_producto_final->descripcion}}</td>
                            <td>{{ $pedido->fecha_entrega }}</td>

                            <td>
                                <div class="d-flex align-items-center ">
                                    <a href="{{ route('trabajo-maquina.show',$pedido) }}"
                                        class="btn btn-sm btn-primary"
                                        title="Ensamblar pedido {{ $pedido->id }}">
                                        <i class="fa-solid fa-puzzle-piece"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>


    </div>
</div>

@endsection

@section('js')
<script src="/js/modulos/alertas-swift.js"></script>
<script src="/js/modulos/ensamble.js"></script>
@endsection
