@extends('layouts.web')
@section('title', ' Contratista | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content') 
    <div class="div container h-content ">        
        <div class="row">            
            <div class="col-12 col-sm-10 col-lg-6 mx-auto">
                
            
                <h1 class="display-6" >Contratistas</h1>
                <hr>
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            - {{ $error }} <br>
                        @endforeach
                    </div>
                    
                @endif
                <!-- Modal Crea maquina-->
                <form action="{{ route('contratistas.update',$contratista) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Crea contratista</h5>
                            <a href="{{ route('contratistas.index') }}" class="btn-close"></a>
                            </div>
                            <div class="modal-body">
                                
                                <div class="card-body">                                                
                                                    
                                    <div class="row mb-3">
                                        <label for="cedula" class="col-md-4 col-form-label text-md-end">{{ __('Cedula') }}</label>
            
                                        <div class="col-md-6">
                                            <input  id="cedula" 
                                                    type="text" 
                                                    class="form-control @error('cedula') is-invalid @enderror text-uppercase" 
                                                    name="cedula" 
                                                    value="{{ old('cedula',$contratista->cedula) }}" 
                                                    required 
                                                    autocomplete="cedula" 
                                                    autofocus
                                                    >
            
                                            @error('cedula')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="primer_nombre" class="col-md-4 col-form-label text-md-end">{{ __('Primer nombre') }}</label>
            
                                        <div class="col-md-6">
                                            <input  id="primer_nombre" 
                                                    type="text" 
                                                    class="form-control @error('primer_nombre') is-invalid @enderror text-uppercase" 
                                                    name="primer_nombre" 
                                                    value="{{ old('primer_nombre',$contratista->primer_nombre) }}" 
                                                    required 
                                                    autocomplete="primer_nombre" 
                                                    autofocus>
                                            @error('primer_nombre')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="segundo_nombre" class="col-md-4 col-form-label text-md-end">{{ __('Segundo nombre') }}</label>
            
                                        <div class="col-md-6">
                                            <input  id="segundo_nombre" 
                                                    type="text" 
                                                    class="form-control @error('segundo_nombre') is-invalid @enderror text-uppercase" 
                                                    name="segundo_nombre" 
                                                    value="{{ old('segundo_nombre',$contratista->segundo_nombre) }}" 
                                                    required 
                                                    autocomplete="segundo_nombre" 
                                                    autofocus>
                                            @error('segundo_nombre')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
            
                                    <div class="row mb-3">
                                        <label for="primer_apellido" class="col-md-4 col-form-label text-md-end">{{ __('Primer apellido') }}</label>
                                        <div class="col-md-6">
                                            <input  id="primer_apellido" 
                                                    type="text" 
                                                    class="form-control @error('primer_apellido') is-invalid @enderror text-uppercase" 
                                                    name="primer_apellido" 
                                                    value="{{ old('primer_apellido',$contratista->primer_apellido) }}" 
                                                    required 
                                                    autocomplete="primer_apellido">
            
                                            @error('primer_apellido')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div> 

                                    <div class="row mb-3">
                                        <label for="segundo_apellido" class="col-md-4 col-form-label text-md-end">{{ __('Segundo apellido') }}</label>
            
                                        <div class="col-md-6">
                                            <input  id="segundo_apellido" 
                                                    type="text" 
                                                    class="form-control @error('segundo_apellido') is-invalid @enderror text-uppercase" 
                                                    name="segundo_apellido" 
                                                    value="{{ old('segundo_apellido',$contratista->segundo_apellido) }}" 
                                                    required 
                                                    autocomplete="segundo_apellido">
                                            @error('segundo_apellido')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="empresa_contratista" class="col-md-4 col-form-label text-md-end">{{ __('Empresa contratista') }}</label>
            
                                        <div class="col-md-6">
                                            <input  id="empresa_contratista" 
                                                    type="empresa_contratista" 
                                                    class="form-control @error('empresa_contratista') is-invalid @enderror text-uppercase"" 
                                                    name="empresa_contratista" 
                                                    value="{{ old('empresa_contratista',$contratista->empresa_contratista) }}" 
                                                    required 
                                                    autocomplete="empresa_contratista">
                                            @error('empresa_contratista')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="acceso" class="col-md-4 col-form-label text-md-end">{{ __('Acceso a instalaciones') }}</label>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" 
                                                        type="checkbox" 
                                                        role="switch" 
                                                        id="acceso"
                                                        name="acceso" 
                                                        {{ $contratista->acceso == false ? ' ' : 'checked="on"'}}>
                                               
                                            </div>
                                        </div>
                                    </div>

                                                   
                            </div>
                                    
                                
                            </div>
                            <div class="modal-footer">
                            <a href="{{ route('contratistas.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar contratista</button>
                            </div>
                        </div>
                        </div>
                </form>               
            </div>
            <!-- Tabla -->

          
        </div>
    </div>

@endsection

@section('js')
<script src="/js/modulos/contratista.js"></script>
@endsection