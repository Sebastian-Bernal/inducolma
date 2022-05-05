@extends('layout')
@section('title', ' Usuarios | inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content') 
<div class="div container h-content ">        
    <div class="row">            
        <div class="col-12 col-sm-10 col-lg-6 mx-auto">
            
           
            <h1 class="display-6" >Roles</h1>
            <hr>
            
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        - {{ $error }} <br>
                    @endforeach
                </div>
                
            @endif
            <!-- Modal Crea maquina-->
            <form action="{{ route('roles.update',$rol) }}" method="POST">
                @csrf
                @method('PATCH')
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Actualizar rol</h5>
                        <a href="{{ route('roles.index') }}" class="btn-close"></a>
                        </div>
                        <div class="modal-body">
                             
                            <div class="card-body">                                                
                                                   
                                <div class="row mb-3">
                                    <label for="nombre" class="col-md-4 col-form-label text-md-end">{{ __('nombre') }}</label>
                                    
                                    <div class="col-md-6">
                                        <input id="nombre" 
                                                type="text" 
                                                class="form-control @error('nombre') is-invalid @enderror" 
                                                name="nombre" 
                                                value="{{ old('nombre',$rol->nombre) }}"
                                                required autocomplete="nombre" autofocus>
        
                                        @error('nombre')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="descripcion" class="col-md-4 col-form-label text-md-end">{{ __('Descripcion') }}</label>
        
                                    <div class="col-md-6">
                                        <textarea name="descripcion" 
                                        id="descripcion" 
                                        cols="30" 
                                        rows="4" 
                                        class="form-control @error('descripcion') is-invalid @enderror">
                                            {{ old('descripcion',trim($rol->descripcion)) }}
                                        </textarea>
                                        @error('descripcion')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
        

                                <div class="row mb-3">
                                    <label for="nivel" class="col-md-4 col-form-label text-md-end">{{ __('Nivel') }}</label>
                                    <div class="col-md-6">
                                        <select class="form-select" name="nivel" required >
                                            
                                            <option value="1" @if ($rol->nivel == 1) selected @endif>1 - recepcion de maderas</option>
                                            <option value="2"  @if ($rol->nivel == 2) selected @endif>2 - operario de maquinas, cubicaje</option>
                                            <option value="3"  @if ($rol->nivel == 3) selected @endif>3 - Auxiliar administrativo</option>                                            
                                        </select>  
                                    </div>                                                                  
                                    
                                </div>                
                        </div>
                                
                            
                        </div>
                        <div class="modal-footer">
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary" >Volver</a>
                        <button type="submit" class="btn btn-primary">Actualizar rol</button>
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