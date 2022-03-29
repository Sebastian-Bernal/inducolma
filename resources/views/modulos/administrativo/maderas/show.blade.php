@extends('layout')
@section('title', ' Maderas | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content') 
<div class="div container h-content ">        
    <div class="row">            
        <div class="col-12 col-sm-10 col-lg-6 mx-auto">
            
           
            
            <form action="{{ route('maderas.update',$madera) }}" method="POST">
                @csrf
                @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modificar madera</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                             
                            <div class="card-body">                                                
                                                   
                                <div class="row mb-3">
                                    <label for="nombre" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="nombre" type="text" class="form-control @error('nombre') is-invalid @enderror text-uppercase" name="nombre" value="{{ old('nombre',$madera->nombre) }}" required autocomplete="nombre" autofocus>
        
                                        @error('nombre')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="nombre_cientifico" class="col-md-4 col-form-label text-md-end">{{ __('Nombre cientifico') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="nombre_cientifico" type="text" class="form-control @error('nombre_cientifico') is-invalid @enderror text-uppercase" name="nombre_cientifico" value="{{ old('nombre_cientifico',$madera->nombre_cientifico) }}" required autocomplete="nombre_cientifico" autofocus>
        
                                        @error('nombre_cientifico')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                           

                                <div class="row mb-3">
                                    <label for="densidad" class="col-md-4 col-form-label text-md-end">{{ __('Densidad') }}</label>
                                    <div class="col-md-6">
                                        <select class="form-select" name="densidad" required >
                                            <option value="{{ $madera->densidad }}" selected>{{ $madera->densidad }}</option>
                                            <option >___________________</option>
                                            <option value="ALTA DENSIDAD">ALTA DENSIDAD</option>
                                            <option value="BAJA DENSIDAD">BAJA DENSIDAD</option>
                                        </select>  
                                    </div>                                                                  
                                    
                                </div>                
                        </div>
                                
                            
                        </div>
                        <div class="modal-footer">
                        <a href="{{ route('maderas.index') }}" type="button" class="btn btn-secondary" >Volver</a>
                        <button type="submit" class="btn btn-primary">Modificar madera</button>
                        </div>
                    </div>
                 
            </form>               
        </div>
        <!-- Tabla -->

      
    </div>
</div>

@endsection

@section('js')
<script src="/js/modulos/maderas.js"></script>
@endsection