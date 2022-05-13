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
                <!-- Formulario modificar pedido-->
                <form action="{{ route('pedidos.update', $pedido) }}" method="POST">
                    @csrf
                    @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Modificar  pedido</h5>
                            <a href="{{ route('pedidos.index') }}" class="btn-close"></a>
                            </div>
                            <div class="modal-body">
                                
                                <div class="card-body">                                                
                                                    
                                    <div class="row mb-3">
                                        <label for="descripcion" class="col-md-4 col-form-label text-md-end">{{ __('Descripción') }}</label>
                                        <div class="col-md-6">
                                            <textarea id="descripcion" 
                                                     
                                                    class="form-control @error('descripcion') is-invalid @enderror text-uppercase"
                                                    name="descripcion" 
                                                    required autocomplete="descripcion" 
                                                    autofocus
                                                    onkeyup="mayusculas()">

                                                    {{ old('descripcion',$pedido->descripcion) }}
                                            </textarea>

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
                                                    name="cantidad" value="{{ old('cantidad',$pedido->cantidad) }}" 
                                                    required autocomplete="cantidad" autofocus
                                                    >
            
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
                                                    name="fecha_entrega"  
                                                    required 
                                                    autocomplete="fecha_entrega" 
                                                    autofocus
                                                    value="{{ $pedido->fecha_entrega }}">
                                                    
            
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
                                                    <option value="{{ $cliente->id }}" 
                                                            {{ $pedido->cliente_id == $cliente->id ? 'selected' : '' }}
                                                            >{{ $cliente->nombre }}
                                                    </option>
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