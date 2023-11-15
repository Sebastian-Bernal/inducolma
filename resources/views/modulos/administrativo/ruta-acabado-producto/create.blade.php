@extends('layouts.web')
@section('title', ' Pedidos | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content')
    <div class="div container h-content ">
        <div class="row">
            <div class="col-12 col-sm-10 col-lg-6 mx-auto">

                <span> Id del ususario para enviar en json: User: {{ Auth::user()->id }}</span>

                <h4 class="display-8" >
                    Crear ruta de acabados para el producto : {{ $pedido->diseno_producto_final->descripcion }} <br>
                    pedido: {{ $pedido->id }} <br>
                    cliente: {{ $pedido->cliente->razon_social }} <br>
                    cantidad : {{ $pedido->cantidad }}
                </h4>
                <span>Maquinas ensamble</span>
                <select name="" id="">
                    @foreach ($maquinas['ENSAMBLE'] as $ensamble)
                        <option value="{{ $ensamble['id'] }}">{{ $ensamble['maquina'] }}</option>
                    @endforeach
                </select>

                <span>Maquinas acabado ensamble</span>
                <select name="" id="">
                    @foreach ($maquinas['ACABADO_ENSAMBLE'] as $acabado)
                        <option value="{{ $acabado['id'] }}">{{ $acabado['maquina'] }}</option>
                    @endforeach
                </select>
        </div>
    </div>

@endsection

@section('js')
<script src="/js/modulos/programaciones.js"></script>
@endsection
