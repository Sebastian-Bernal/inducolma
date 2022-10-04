@extends('layouts.web')
@section('title', ' Ordenes produccion | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content')
    <div class="div container h-content ">
        <div class="row">
            <div class="col-12 col-sm-10 col-lg-6 mx-auto">


                <h4 class="display-8" >
                    Ordenes de producción para el pedido:
                </h4>
                <h5>
                    @if (count($ordenes) > 0)
                        #:  {{ $ordenes[0]->pedido_id }} <br>
                        Cliente: {{ $ordenes[0]->pedido->cliente->nombre }} <br>
                        Producto: {{ $ordenes[0]->pedido->diseno_producto_final->descripcion }}
                    @else
                        No se encontraron ordenes de producción para el pedido: {{ $pedido }}
                    @endif
                </h5>
                <a href="{{ route('programaciones.show', $pedido) }}" class="btn btn-outline-secondary">volver</a>
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
                        <th>Numero de orden: </th>
                        <th scope="col">Item</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse ($ordenes as $orden)

                            <tr id="">
                                <td>{{ $orden->id }}</td>
                                <td>{{ $orden->item->descripcion }}</td>
                                <td>{{ $orden->cantidad}}</td>
                                <td>{{ $orden->estado }}</td>
                                <td>
                                    <div class="row row-cols-lg-auto g-3 align-items-center">
                                        @if (trim($orden->estado) == 'PENDIENTE')
                                            @if (count($orden->procesos) > 0)
                                            <button  class="btn btn-danger mx-1" title="Eliminar orden produccion" onclick="eliminarOrdenProduccion({{ $orden->id.','.$orden->pedido_id }})">
                                                <i class="fa fa-trash-can text-white"></i>
                                            </button>
                                            @else
                                            <a href="{{ route('rutas-procesos',[$orden->pedido->id, $orden->item->id]) }}"
                                                class="btn btn-primary mx-1"
                                                title="crear ruta de proceos">
                                                <i class="fa-solid fa-route"></i>
                                            </a>

                                            <button  class="btn btn-danger mx-1" title="Eliminar orden produccion" onclick="eliminarOrdenProduccion({{ $orden->id.','.$orden->pedido_id }})">
                                                <i class="fa fa-trash-can text-white"></i>
                                            </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="5" > No se encontraron ordenes de producción creadas para el pedido</td>
                            </tr>
                    @endforelse

            </table>

        </div>
    </div>

@endsection

@section('js')
<script src="/js/modulos/alertas-swift.js"></script>
<script src="/js/modulos/ordenProduccion.js"></script>
@endsection

