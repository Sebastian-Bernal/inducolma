@extends('layouts.web')
@section('title', ' Pedidos | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content') 
    <div class="div container h-content ">        
        <div class="row">            
            <div class="col-12 col-sm-10 col-lg-6 mx-auto">
                
            
                <h1 class="display-6" >Pedidos </h1>
                <hr>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#creapedido">
                    Crear pedido
                </button>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            - {{ $error }} <br>
                        @endforeach
                    </div>
                    
                @endif
                <!-- Modal Crea pedido-->
                <form action="{{ route('pedidos.store') }}" method="POST">
                    @csrf
                    <div class="modal fade" id="creapedido" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Crea pedido</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                
                                <div class="card-body">                                                
                                                    
                                    <div class="row mb-3">
                                        <label for="descripcion" class="col-md-4 col-form-label text-md-end">{{ __('Descripción') }}</label>
                                        <div class="col-md-6">
                                            <input id="descripcion" 
                                                    type="text" 
                                                    class="form-control @error('descripcion') is-invalid @enderror text-uppercase"
                                                    name="descripcion" value="{{ old('descripcion') }}" 
                                                    required autocomplete="descripcion" autofocus
                                                    onkeyup="mayusculas()">
            
                                            @error('descripcion')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="cantidad" class="col-md-4 col-form-label text-md-end">{{ __('Cantidad') }}</label>
                                        <div class="col-md-6">
                                            <input id="cantidad" 
                                                    type="number" 
                                                    class="form-control @error('cantidad') is-invalid @enderror text-uppercase"
                                                    name="cantidad" value="{{ old('cantidad') }}" 
                                                    required autocomplete="cantidad" autofocus
                                                    onkeyup="mayusculas()">
            
                                            @error('cantidad')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="fecha_entrega" class="col-md-4 col-form-label text-md-end">{{ __('Fecha de entrega') }}</label>
                                        <div class="col-md-6">
                                            <input id="fecha_entrega" 
                                                    type="date" 
                                                    class="form-control @error('fecha_entrega') is-invalid @enderror text-uppercase"
                                                    min="{{ date('Y-m-j', strtotime('10 weekdays')) }}" 
                                                    name="fecha_entrega" value="{{ old('fecha_entrega') }}" 
                                                    required autocomplete="fecha_entrega" autofocus
                                                    onkeyup="mayusculas()">
            
                                            @error('fecha_entrega')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                   
                                    <div class="row mb-3">
                                        <label for="cliente" class="col-md-4 col-form-label text-md-end">{{ __('Cliente') }}</label>
                                        <div class="col-md-6">
                                            <select id="cliente" 
                                                    type="number" 
                                                    class="form-control @error('cliente') is-invalid @enderror"                                                    
                                                    name="cliente" 
                                                    required 
                                                    >
                                                <option selected>Seleccione una opción</option>
                                                @foreach ($clientes as $cliente)
                                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                                @endforeach
                                            </select>
            
                                            @error('cliente')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    


                            </div>
                                    
                                
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar pedido</button>
                            </div>
                        </div>
                        </div>
                    </div>   
                </form>               
            </div>
            <!-- Tabla -->

            <table id="listapedidos" class="table table-bordered table-striped dt-responsive">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Descripci&oacute;n</th>
                        <th>Cantidad</th> 
                        <th>Solicitud</th>  
                        <th>Fecha de entrega</th>
                        <th>Estado</th>
                       
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($pedidos as $pedido)
                        <tr>
                            <td>{{ $pedido->cliente->nombre }}</td>
                            <td>{{ $pedido->descripcion }}</td>
                            <td>{{ $pedido->cantidad }}</td>
                            <td>{{ $pedido->created_at->diffForHumans() }}</td>
                            <td>{{ $pedido->fecha_entrega}}</td> 
                            <td>{{ $pedido->estado }}</td> 
                                            
                            
                            
                            <td>
                                <div class="d-flex align-pedidos-center ">
                                    
                                    <button class="btn btn-sm btn-danger" onclick="eliminarItem({{ $pedido }})">
                                        <i class="fa-regular fa-trash-can fa-lg" style="color: black"></i>
                                    </button>
                                    <a href="{{ route('pedidos.edit',$pedido) }}" class="btn btn-sm btn-warning">
                                        <i class="fa-solid fa-pen-to-square fa-lg"></i>
                                    </a>
                                
                                </div>
                            </td>
                        </tr> 
                    @endforeach
                    
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('js')
<script src="/js/modulos/pedidos.js"></script>
@endsection