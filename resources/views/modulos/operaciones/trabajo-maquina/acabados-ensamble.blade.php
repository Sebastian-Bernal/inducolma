@extends('layouts.web')
@section('title', ' Trabajo acabados | Inducolma')

@section('submenu')
@include('modulos.sidebars.costos-side')
@endsection
@section('content')
<div class="div container h-content m-auto">
    <div class="d-flex flex-wrap row  m-auto align-items-center container-fluid ">
        <div
            class="container col-xl-12 col-lg-12 col-md-12 col-sm-12 bg-light shadow rounded-3 min-vh-100 mb-2 mt-2 ">

            <div class="text-primary">
                <h1 class="display-6">Pedidos listos para acabados en ensamble</h1>
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
                            <th>Cantidad producida</th>
                            <th>Cantidad programada</th>
                            <th>Estado del ensamble o acabado</th>
                            <th>Observaciones</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($acabados_ensamble as $ensamble)
                        <tr>
                            
                            <td>{{ $ensamble->pedido_id }}</td>
                            <td>{{ $ensamble->pedido->cliente->razon_social }}</td>
                            <td>{{ $ensamble->pedido->cantidad }}</td>
                            <td>{{ $ensamble->pedido->diseno_producto_final->descripcion}}</td>
                            <td>{{ $ensamble->pedido->fecha_entrega }}</td>
                            <td>{{ $ensamble->cantidad_producida }}</td>
                            <td>{{ $ensamble->cantidad }}</td>
                            <td>{{ $ensamble->estado }}</td>
                            <td>{{ $ensamble->observaciones }}</td>

                            <td>
                                <div class="d-flex align-items-center ">
                                    <a href="{{ route('trabajo-ensamble',$ensamble->pedido_id) }}"
                                        class="btn btn-sm btn-primary"
                                        title="Procesar acabado pedido {{ $ensamble->pedido->id }}">
                                        <i class="fa-solid fa-paint-roller"></i>
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
