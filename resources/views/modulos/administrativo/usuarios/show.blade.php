@extends('layout')
@section('title', ' Usuarios | inducolma')

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
            <!-- Modal Crea maquina-->
            <form action="{{ route('usuarios.update',$usuario) }}" method="POST">
                @csrf
                @method('PATCH')
                
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modificar Usuario</h5>
                        
                        </div>
                        <div class="modal-body">
                             
                            <div class="card-body">                                                
                                                   
                                <div class="row mb-3">
                                    <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Identificacion') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="identificacionUsuario" type="text" class="form-control @error('identificacionUsuario') is-invalid @enderror" name="identificacionUsuario" value="{{ old('identificacionUsuario',$usuario->identificacion) }}" required autocomplete="identificacionUsuario" autofocus>
        
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name',$usuario->name) }}" required autocomplete="name" autofocus>
        
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
        
                                <div class="row mb-3">
                                    <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email',$usuario->email) }}" required autocomplete="email">
        
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div> 

                                <div class="row mb-3">
                                    <label for="rol" class="col-md-4 col-form-label text-md-end">{{ __('Rol') }}</label>
                                    <div class="col-md-6">
                                        <select class="form-select" name="rolUsuario" required >
                                            @foreach ($roles as $rol)
                                                <option value="{{ $rol->nivel }}">{{ $rol->nombre }}</option>
                                            @endforeach                    
                                                                                       
                                        </select>  
                                    </div>                                                                  
                                    
                                </div>                
                        </div>                                
                            
                        </div>
                        <div class="modal-footer">
                        <a href="{{ route('usuarios.index') }}" type="button" class="btn btn-secondary">Volver</a>
                        <button type="submit" class="btn btn-primary">Actualizar usuario</button>
                        </div>
                    </div>
                    </div>
                
            </form>               
        </div>
       
    </div>
</div>

@endsection

@section('js')
<script src="/js/modulos/usuarios.js"></script>
@endsection