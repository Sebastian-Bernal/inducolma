@extends('layouts.web')
@section('title', ' Insumos almacen | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content') 
    <div class="div container h-content ">        
        <div class="row">            
            <div class="col-12 col-sm-10 col-lg-6 mx-auto">
                
            
                <h1 class="display-6" >Insumos almacen</h1>
                <hr>
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            - {{ $error }} <br>
                        @endforeach
                    </div>
                    
                @endif
                <!-- Edita insumo-->
                <form action="{{ route('insumos-almacen.update', $insumo_almacen) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Editar insumo</h5>
                        <a href="{{ route('insumos-almacen.index') }}" class="btn-close"></a>
                        </div>
                        <div class="modal-body">
                            
                            <div class="card-body">                                                
                                                
                                <div class="row mb-3">
                                    <label for="descripcion" class="col-md-4 col-form-label text-md-end">{{ __('Descripción') }}</label>
                                    <div class="col-md-6">
                                        <input id="descripcion" 
                                                type="text" 
                                                class="form-control @error('descripcion') is-invalid @enderror text-uppercase"
                                                style="text-transform: uppercase;"  
                                                name="descripcion"  
                                                required autocomplete="descripcion" autofocus
                                                onkeyup="mayusculas()"
                                                value="{{ old('descripcion',$insumo_almacen->descripcion) }}">
        
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
                                                style="text-transform: uppercase;"  
                                                name="cantidad" 
                                                required autocomplete="cantidad" autofocus
                                                onkeyup="mayusculas()"
                                                value="{{ old('cantidad',$insumo_almacen->cantidad) }}">
        
                                        @error('cantidad')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="precio_unitario" class="col-md-4 col-form-label text-md-end">{{ __('Precio unitario') }}</label>
                                    <div class="col-md-6">
                                        <input id="precio_unitario" 
                                                type="number" 
                                                class="form-control @error('precio_unitario') is-invalid @enderror text-uppercase"
                                                style="text-transform: uppercase;"  
                                                name="precio_unitario" 
                                                required autocomplete="precio_unitario" autofocus
                                                onkeyup="mayusculas()"
                                                value="{{ old('precio_unitario',$insumo_almacen->precio_unitario) }}">
        
                                        @error('precio_unitario')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                        </div>
                                
                            
                        </div>
                        <div class="modal-footer">
                        <a href="{{ route('insumos-almacen.index') }}" class="btn btn-secondary">volver</a>
                        <button type="submit" class="btn btn-primary">Actualizar insumo</button>
                        </div>
                    </div> 
                </form>         
            </div>
        </div>
    </div>

@endsection

@section('js')
<script src="/js/modulos/insumos.js"></script>
@endsection