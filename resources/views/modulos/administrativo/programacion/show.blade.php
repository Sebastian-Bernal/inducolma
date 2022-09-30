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
                    Items del pedido:
                </h4>
                <h5>
                    #{{ $pedido->id }} - {{ $pedido->nombre }} - {{ $pedido->descripcion }} - {{ $pedido->cantidad }}
                </h5>
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

                    @forelse ($pedido->items_pedido as $items)

                            <tr id="{{ $items->item_id }}">
                                <td>{{ $items->descripcion }}</td>
                                <td>{{ $items->cantidad}}</td>
                                <td id="{{ 'cantidad'.$items->item_id }}">{{ $items->total > 0 ? $items->total : 0 }}</td>
                                <td id="{{ 'existencias'.$items->item_id }}">{{ $items->existencias}}</td>
                                <td>

                                    <div class="row row-cols-lg-auto g-3 align-items-center">

                                        @if ($items->total > 0)
                                        <a href="{{ route('getMaderas',[$pedido->id, $items->item_id]) }}"
                                            class="btn btn-primary"
                                            title="Procesar item">
                                            <i class="fa-solid fa-hammer"></i>
                                        </a>
                                        @else

                                        @endif

                                        <a href="{{ route('odenes-produccion',[$pedido->id, $items->item_id]) }}"
                                            class="btn btn-primary mx-1"
                                            title="programar ruta de proceos">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        @if ($items->existencias > 0)
                                        <input type="hidden" name="{{ 'cantidad_atual_'.$items->item_id }}"
                                                            id="{{ 'cantidad_atual_'.$items->item_id }}"
                                                            value="{{ $items->total }}">
                                        <input type="hidden" name="{{ 'existencia_atual_'.$items->item_id }}"
                                                            id="{{ 'existencia_atual_'.$items->item_id }}"
                                                            value="{{ $items->existencias }}">
                                        <button class="btn btn-warning"
                                                title="Usar items de inventario"
                                                onclick="usarInventario({{ $items->item_id.','.$pedido }})">
                                                <i class="fa-solid fa-dolly"></i>
                                        </button>
                                        @else

                                        @endif
                                    </div>

                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="5" > El dise{o no tiene items asignados, por favor edite el producto}</td>
                            </tr>
                    @endforelse

            </table>

        </div>
    </div>

@endsection

@section('js')
<script src="/js/modulos/programaciones.js"></script>
@endsection
