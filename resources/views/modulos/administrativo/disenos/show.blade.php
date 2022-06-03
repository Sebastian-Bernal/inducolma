@extends('layouts.web')
@section('title', ' Clientes | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content') 
    <div class="div container h-content ">        
        <div class="row">            
            <div class="col-12 col-sm-10 col-lg-6 mx-auto">
                
               
                <h4>Diseño: {{ $diseno->descripcion }}</h4>
                <h4>Madera: {{ $diseno->tipo_madera->descripcion }}</h4>
                <hr>
               
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            - {{ $error }} <br>
                        @endforeach
                    </div>
                    
                @endif
                             
            </div>
           
             <div class="d-flex justify-content-between">
                 <!-- Button trigger modal asignar diseño a cliente -->
                <button type="button" class="btn btn-outline-primary btn-sm mb-3 " data-bs-toggle="modal" data-bs-target="#creadiseno">
                    Asignar diseño a cliente
                </button>
                <div>
                     <!-- Button trigger modal agregar item -->
                    <button class="btn btn-primary mb-3" title="Agregar Item" data-bs-toggle="modal" data-bs-target="#agregarItem">
                        Agregar item
                        <i class="fa-solid fa-plus"></i>
                        <i class="fa-solid fa-pallet pb-1" ></i>
                    </button>
                     <!-- Button trigger modal agregar insumo -->
                    <button class="btn btn-primary mb-3" title="Agregar insumo" data-bs-toggle="modal" data-bs-target="#agregarInsumo">
                        Agregar insumo
                        <i class="fa-solid fa-plus"></i>
                        <i class="fa-solid fa-screwdriver-wrench"></i>
                    </button>
                </div>
                   
            </div>    
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
                            <div id="spAsignar" >
                                
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" onclick="validarDatosDiseno({{ $diseno }})">Asignar diseño</button>
                        </div>
                    </div>
                    </div>
                </div>   
            </form> 
            <!-- Modal agregar item-->
            <form id="formAgregarItem" >
                <div class="modal fade" id="agregarItem" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Agregar item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('items.index') }}" class="btn btn-outline-primary btn-sm">crear nuevo item</a>
                            </div> 
                            <input type="hidden" name="diseno_id" id="diseno_id" value="{{ $diseno->id }}">
                            <div class="card-body">  
                                                                             
                                <div class="row mb-3">
                                    <label for="item" class="col-md-4 col-form-label text-md-end">{{ __('Item') }}</label>
                                    <div class="col-md-6">
                                        <select  id="item" 
                                                type="text" 
                                                class="form-control @error('item') is-invalid @enderror text-uppercase" 
                                                name="item" 
                                                value="{{ old('item') }}" 
                                                required 
                                                autocomplete="item" 
                                                autofocus>
                                            <option value="" selected>Seleccione...</option>
                                            @foreach ($items as $item)
                                                <option value="{{ $item->id }}">{{ $item->descripcion }}</option>
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
                                    <label for="cantidad" class="col-md-4 col-form-label text-md-end">{{ __('Cantidad') }}</label>
                                    <div class="col-md-6">
                                        <input  id="cantidad" 
                                                type="number" 
                                                class="form-control @error('cantidad') is-invalid @enderror text-uppercase" 
                                                name="cantidad" 
                                                value="{{ old('cantidad') }}" 
                                                min="1"
                                                max="20"
                                                required 
                                                autocomplete="cantidad" 
                                                autofocus>
                                            
                                       
                                        @error('cliente')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                            </div>
                            <div id="spItem" >
                                
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" onclick="verificarItems({{ $diseno }})">Agregar</button>
                        </div>
                    </div>
                    </div>
                </div>   
            </form> 
            <!-- Modal agregar insumo-->
            <form id="formAgregarInsumo" >
                <div class="modal fade" id="agregarInsumo" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Agregar insumo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('insumos-almacen.index') }}" class="btn btn-outline-primary btn-sm">crear nuevo insumo</a>
                            </div> 
                            <input type="hidden" name="diseno_id" id="diseno_id" value="{{ $diseno->id }}">
                            <div class="card-body">                                                
                                <div class="row mb-3">
                                    <label for="insumo" class="col-md-4 col-form-label text-md-end">{{ __('Insumo') }}</label>
                                    <div class="col-md-6">
                                        <select  id="insumo" 
                                                type="text" 
                                                class="form-control @error('insumo') is-invalid @enderror text-uppercase" 
                                                name="insumo" 
                                                value="{{ old('insumo') }}" 
                                                required 
                                                autocomplete="insumo" 
                                                autofocus>
                                            <option value="" selected>Seleccione...</option>
                                            @foreach ($insumos as $insumo)
                                                <option value="{{ $insumo->id }}">{{ $insumo->descripcion }}</option>
                                            @endforeach
                                        </select>
                                        @error('insumo')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="cantidad_insumo" class="col-md-4 col-form-label text-md-end">{{ __('Cantidad') }}</label>
                                    <div class="col-md-6">
                                        <input  id="cantidad_insumo" 
                                                type="number" 
                                                class="form-control @error('cantidad_insumo') is-invalid @enderror text-uppercase" 
                                                name="cantidad_insumo" 
                                                value="{{ old('cantidad_insumo') }}" 
                                                min="1"
                                                max="20"
                                                required 
                                                autocomplete="cantidad_insumo" 
                                                autofocus>
                                            
                                       
                                        @error('cantidad_insumo')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div id="spInsumo" >
                                
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" onclick="verificarInsumos({{ $diseno }})">Agregar</button>
                        </div>
                    </div>
                    
                    </div>
                </div>   
            </form> 
            <!-- Listado de items - insumos -->
            
            <div class="d-flex justify-content-between">
               
                <div class="col-6 mx-2" >
                    <strong>Items</strong>
                    <ul class="list-group" id="listaItems">
                        @foreach ($diseno_items as $diseno_item)
                        <li class="list-group-item d-flex justify-content-between align-items-center" id="{{ $diseno_item->id }}">
                            {{ $diseno_item->descripcion }}
                            <div class=" justify-content-center py-1">
                                <span class="badge bg-primary square-pill  "> 
                                    <h5 class=" m-0 p-0 ">{{ $diseno_item->cantidad }}</h5>
                                </span>
                                <button class="btn btn-danger btn-xs" onclick="eliminarItem({{ $diseno_item->id }})">
                                    <i class="fa-solid fa-trash-can"></i> 
                                </button>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="d-flex justify-content-center" id="spEliminar">
                    
                </div>
                <div class="col-6 mx-2" >
                    <strong>Insumos</strong>
                    <ul class="list-group" id="listaInsumo">
                        @foreach ($diseno_insumos as $diseno_insumo)
                        <li class="list-group-item d-flex justify-content-between align-items-center" id="{{ $diseno_insumo->id }}">
                            {{ $diseno_insumo->descripcion }}
                            <div class=" justify-content-center py-1">
                                <span class="badge bg-primary square-pill  "> 
                                    <h5 class=" m-0 p-0 ">{{ $diseno_insumo->cantidad }}</h5>
                                </span>
                                <button class="btn btn-danger btn-xs" onclick="eliminarInsumo({{ $diseno_insumo->id }})">
                                    <i class="fa-solid fa-trash-can"></i> 
                                </button>
                            </div>
                        </li>
                        @endforeach
                        
                    </ul>
                </div>
            </div>
        </div>
        <hr>
      
        <a href="{{ route('disenos.index') }}" class="btn btn-outline-secondary btn-sm">Volver</a>
        <a href="{{ route('disenos.edit',$diseno) }}" class="btn  btn-outline-warning btn-sm" id="editardiseno">
            <i class="fa-solid fa-pen-to-square fa-lg"></i>
            Editar
        </a>
    </div>

@endsection

@section('js')
<script src="/js/modulos/disenos.js"></script>
<script src="/js/modulos/diseno-item.js"></script>
<script src="/js/modulos/diseno-insumo.js"></script>
@endsection