@extends('layouts.web')
@section('title', ' Clientes | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content') 
    <div class="div container h-content ">        
        <div class="row">            
            <div class="col-12 col-sm-10 col-lg-6 mx-auto">
            
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            - {{ $error }} <br>
                        @endforeach
                    </div>
                    
                @endif
               
                <div class="card">
                    <h5 class="card-header">{{ $cliente->nombre }}</h5>
                    <div class="card-body">
                        <p class=" justify ">
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCliente" aria-expanded="false" aria-controls="collapseCliente">
                                    Datos del cliente
                                </button>
                                
                                <!-- Button trigger modal nuevo pedido-->
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                    <i class="fa-solid fa-cart-plus"></i>
                                      Nuevo pedido
                                </button>
                            </div>
                            
                        </p>
                        <div class="collapse" id="collapseCliente">
                        <div class="card card-body">
                            <p>
                                <strong>Nit:</strong> {{ $cliente->nit }}
                            </p>
                            <p>
                                <strong>Dirección:</strong> {{ $cliente->direccion }}   
                            </p>
                            <p>
                                <strong>Teléfono:</strong> {{ $cliente->telefono }}
                            </p>
                            <p>
                                <strong>Correo electronico:</strong> {{ $cliente->email }}
                            </p>
                        </div>
                        <a href="{{ route('clientes.edit',$cliente) }}" class="btn btn-warning mt-1">Editar</a>
                        </div>
                        
                    </div>
                </div>   
                <!-- Modal nuevo pedido cliente -->
                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Nuevo pedido</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('pedidos.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="cliente_id" value="{{ $cliente->id }}">
                                <div class="form-group">
                                    <label for="descripcion">Descripci&oacute;n</label>
                                    <input type="text" 
                                            name="descripcion" 
                                            class="form-control">
                                </div>
                                <div class="form-group ">
                                    <label for="">Producto</label>
                                    <div class="d-flex justify-content-between">
                                        
                                        <select name="producto_id" 
                                                id="producto_id" 
                                                class="form-control">
                                            @foreach ($productos as $producto)
                                                <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <a href="{{ route('disenos.index') }}" class="btn btn-primary mx-1" title="Crear nuevo diseño">
                                            <i class="fa-solid fa-pen-ruler"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="cantidad">Cantidad</label>
                                    <input type="number" 
                                            name="cantidad" 
                                            class="form-control"
                                            min="1"
                                            max="1000"
                                            step="1" >
                                </div>
                                <div class="form-group">
                                    <label for="fecha_entrega">Fecha entrega</label>
                                    <input type="date" 
                                            name="fecha_entrega" 
                                            class="form-control"
                                            min="{{ date('Y-m-d', strtotime('10 weekdays')) }}" >
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary">Añadir nuevo pedido</button>
                        </div>
                    </div>
                    </div>
                </div>        
            </div>
            <!-- Tabla -->
            <h4>Ultimos pedidos del cliente <span class="badge bg-secondary"></span></h4>
            <table id="listaPedidos" class="table table-bordered table-striped dt-responsive">

                <thead>
                    <tr>                        
                        <th>Descripci&oacute;n</th>
                        <th>Cantidad</th>  
                        <th>Solicitado</th> 
                        <th>Fecha de entrega</th>         
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($pedidos as $pedido)
                        <tr>
                            <td>{{ $pedido->descripcion }}</td>
                            <td>{{ $pedido->cantidad }}</td>
                            <td>{{ $pedido->created_at->diffForHumans() }}</td> 
                            <td>{{ $pedido->fecha_entrega }}</td>
                            <td>{{ $pedido->estado }}</td>
                        </tr>
                        
                    @endforeach
                    
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('js')
<script src="/js/modulos/clientes.js"></script>
@endsection