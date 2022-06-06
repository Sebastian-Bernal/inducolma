@extends('layouts.web')
@section('title', ' Pedidos | Inducolma')

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
                 <!-- Modal asignar producto a cliente-->
                 <form id="formAsignar" >
                    <div class="modal fade" id="creadiseno" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Asignar diseño a un cliente</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                
                                <div class="card-body">                                                
                                    <div class="row mb-3">
                                        <label for="productos" class="col-md-4 col-form-label text-md-end">{{ __('Producto') }}</label>
                                        <div class="col-md-6">
                                            <select  id="productos" 
                                                    type="text" 
                                                    class="form-control @error('productos') is-invalid @enderror text-uppercase" 
                                                    name="productos" 
                                                    value="{{ old('productos') }}" 
                                                    required 
                                                    autocomplete="productos" 
                                                    autofocus>
                                                                                                
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="spAsignar" >
                                    
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="volverAPedido()">Cerrar</button>
                                <button type="button" class="btn btn-primary" onclick="asignarDiseno()">Asignar diseño</button>
                            </div>
                        </div>
                        </div>
                    </div>   
                </form>
                <!-- Formulario modificar pedido-->
                <form action="{{ route('pedidos.update', $pedido) }}" method="POST">
                    @csrf
                    @method('PUT')
                   
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Crea pedido</h5>
                            <a href="{{ route('pedidos.index') }}" class="btn-close" ></a>
                        </div>
                        <div class="modal-body">
                            <div class="d-flex justify-content-end">
                                <button type="button" 
                                        class="btn btn-outline-primary btn-sm mb-3 " 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#creadiseno"
                                        id="btnAsignar">
                                    Asignar diseño a cliente
                                </button>
                            </div>
                            <div class="card-body">   
                                <div class="row mb-3">
                                    <label for="cliente" class="col-md-4 col-form-label text-md-end">{{ __('Cliente') }}</label>
                                    <div class="col-md-6">
                                        <select id="cliente" 
                                                type="number" 
                                                class="form-control @error('cliente') is-invalid @enderror sel-cliente"                                                    
                                                name="cliente" 
                                                required 
                                                onchange="cargarProductos();"
                                                >
                                            <option value="">Seleccione un cliente</option>
                                            @foreach ($clientes as $cliente)
                                                <option value="{{ $cliente->id }}" {{ $cliente->id == $pedido->cliente_id ? 'selected' : '' }}>{{ $cliente->nombre }}</option>
                                            @endforeach
                                        </select>
        
                                        @error('cliente')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>                                             
                                        
                                <div class="row mb-3">
                                    <label for="items" class="col-md-4 col-form-label text-md-end">{{ __('Producto') }}</label>
                                    <div class="col-md-6">
                                        <div class="d-flex justifu-content-between">
                                            <select id="items" 
                                                class="form-control @error('items') is-invalid @enderror text-uppercase items"
                                                name="items" value="{{ old('items') }}" 
                                                required autocomplete="items" autofocus
                                                onkeyup="mayusculas()">
                                                @foreach ($disenos_cliente as $diseno_cliente)
                                                    <option value="{{ $diseno_cliente->id }}" {{ $diseno_cliente->id == $pedido->diseno_producto_final_id ? 'selected' : '' }}>{{ $diseno_cliente->descripcion }}</option>
                                                @endforeach
                                            </select>
                                            <div class="d-flex justify-content-center" id="spProducto">
                                            </div>
                                        </div>
                                        @error('items')
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
                                                name="cantidad" value="{{ old('cantidad',$pedido->cantidad) }}" 
                                                required autocomplete="cantidad" autofocus
                                                min="0"
                                                max="1000">
        
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
                                                name="fecha_entrega" value="{{ old('fecha_entrega',$pedido->fecha_entrega) }}" 
                                                required autocomplete="fecha_entrega" autofocus
                                                onkeyup="mayusculas()">
        
                                        @error('fecha_entrega')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                        </div>
                                
                            
                        </div>
                        <div class="modal-footer">
                            <a href="{{ route('pedidos.index') }}" class="btn btn-secondary" >Cerrar</a>
                            <button type="submit" class="btn btn-primary">Guardar pedido</button>
                        </div>
                    </div>
                </form>               
            </div>
           
           
        </div>
    </div>

@endsection

@section('js')
<script src="/js/modulos/pedidos.js"></script>
@endsection