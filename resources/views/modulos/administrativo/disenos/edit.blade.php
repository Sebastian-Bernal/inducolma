@extends('layouts.web')
@section('title', ' Clientes | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content') 
    <div class="div container h-content ">        
        <div class="row">            
            <div class="col-12 col-sm-10 col-lg-6 mx-auto">
                
               
                <h1 class="display-8" >Diseño: {{ $diseno->descripcion }} -- Madera: {{ $diseno->madera->nombre }}</h1>
                <hr>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#creadiseno">
                    Asignar diseño a cliente
                </button>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            - {{ $error }} <br>
                        @endforeach
                    </div>
                    
                @endif
                <!-- Modal Crea maquina-->
                <form id="formAsignar" >
                
                    <div class="modal fade" id="creadiseno" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Asignar diseño a un cliente</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="diseno_id" id="diseno_id" value="{{ $diseno->id }}">
                                <div class="card-body">                                                
                                    <div class="row mb-3">
                                        <label for="cliente_id" class="col-md-4 col-form-label text-md-end">{{ __('Cliente') }}</label>
                                        <div class="col-md-6">
                                            <select  id="cliente_id" 
                                                    type="text" 
                                                    class="form-control @error('cliente_id') is-invalid @enderror text-uppercase" 
                                                    name="cliente_id" 
                                                    value="{{ old('cliente_id') }}" 
                                                    required 
                                                    autocomplete="cliente_id" 
                                                    autofocus>
                                                <option value="" selected>Seleccione...</option>
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
                                <button type="button" class="btn btn-primary" onclick="validarDatosDiseno({{ $diseno.','.$diseno->items.','.$diseno->insumos }})">Asignar diseño</button>
                            </div>
                        </div>
                        </div>
                    </div>   
                </form>               
            </div>
                       
            <div class="d-flex justify-content-between">
                <div class="col-12 col-sm-10 col-lg-6 mx-auto">
                    <strong>Listado de items:</strong>
                    <ul class="list-group">
                        @foreach ($diseno->items as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $item->descripcion }}
                            <span class="badge bg-primary rounded-pill">{{ $item->cantidad }}</span>
                        </li>
                        @endforeach
                        
                    </ul>
                </div>
                <div class="col-12 col-sm-10 col-lg-6 mx-auto">
                    <strong>Listado de insumos:</strong>
                    <ul class="list-group">
                        @foreach ($diseno->insumos as $insumo)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $insumo->descripcion }}
                            <span class="badge bg-primary rounded-pill">{{ $insumo->cantidad }}</span>
                        </li>
                        @endforeach
                        
                    </ul>
                </div>
            </div>
        </div>
        <hr>
      
        <a href="{{ route('disenos.show',$diseno) }}" class="btn btn-outline-secondary">Volver</a>
        
    </div>

@endsection

@section('js')
<script src="/js/modulos/disenos.js"></script>
@endsection