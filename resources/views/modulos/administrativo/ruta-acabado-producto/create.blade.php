@extends('layouts.web')
@section('title', ' Pedidos | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content')
    <div class="div container h-content ">
        <div class="row">
            <div class="col-12 col-sm-10 col-lg-6 mx-auto">


                <h4 class="display-8" >
                    Crear ruta de acabados para el producto : {{ $pedido->diseno_producto_final->descripcion }} <br>
                    pedido: {{ $pedido->id }} <br>
                    cliente: {{ $pedido->cliente->razon_social }} <br>
                    cantidad : {{ $pedido->cantidad }}
                </h4>

        </div>
    </div>

@endsection

@section('js')
<script src="/js/modulos/programaciones.js"></script>
@endsection
