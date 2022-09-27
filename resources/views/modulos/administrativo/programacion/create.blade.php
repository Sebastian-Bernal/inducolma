@extends('layouts.web')
@section('title', ' Pedidos | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content')
    <div class="div container h-content ">
        <div class="row">
            <div class="col-12 col-sm-10 col-lg-6 mx-auto">

                <h4>
                    <strong>Item a contruir:</strong>{{ $item->descripcion }}
                </h4>
                <p>
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseInfoPedido" aria-expanded="false" aria-controls="collapseInfoPedido">
                    Información del pedido
                    </button>
                </p>
                    <div class="collapse" id="collapseInfoPedido">
                        <div class="card card-body">
                            # pedido: {{ $pedido->id }} <br>
                            Cliente:  {{ $pedido->nombre }} <br>
                            Producto:  {{ $pedido->descripcion }} <br>
                            Cantidad: {{ $pedido->cantidad }}
                        </div>
                    </div>
                <hr>

                <br><br>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            - {{ $error }} <br>
                        @endforeach
                    </div>

                @endif

            </div>

            <!-- Lista de pedidos -->

            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">Descripcion</th>
                        <th scope="col">Cantidad de diseño</th>
                        <th scope="col">Total a producir</th>
                        <th scope="col">Existencias en inventario</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>



            </table>

        </div>
    </div>

@endsection

@section('js')
<script src="/js/modulos/programaciones.js"></script>
@endsection
