@extends('layout')
@section('title', ' Usuarios | inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content') 
<div class="div container h-content ">        
    <div class="row">            
        <div class="col-12 col-sm-10 col-lg-6 mx-auto">
            
           
            <h1 class="display-6" >Usuarios</h1>
            <hr>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#creaUsuario">
                Crear usuario
            </button>
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        - {{ $error }} <br>
                    @endforeach
                </div>
                
            @endif
            <!-- Modal Crea maquina-->
            <form action="{{ route('usuarios.store') }}" method="POST">
                @csrf
                <div class="modal fade" id="creaUsuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Crea Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                             
                            <div class="card-body">                                                
                                                   
                                <div class="row mb-3">
                                    <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Identificacion') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="identificacionUsuario" type="text" class="form-control @error('identificacionUsuario') is-invalid @enderror" name="identificacionUsuario" value="{{ old('identificacionUsuario') }}" required autocomplete="identificacionUsuario" autofocus>
        
                                        @error('name')
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
                                                value="{{ old('primer_nombre') }}" 
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
                                                value="{{ old('segundo_nombre') }}" 
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
                                                value="{{ old('primer_apellido') }}" 
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
                                                value="{{ old('segundo_apellido') }}" 
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
                                    <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
        
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div> 

                                <div class="row mb-3">
                                    <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Rol') }}</label>
                                    <div class="col-md-6">
                                        <select class="form-select" name="rolUsuario" required >
                                            @foreach ($roles as $rol)
                                                <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
                                            @endforeach                     
                                                                                       
                                        </select>  
                                    </div>                                                                  
                                    
                                </div>                
                        </div>
                                
                            
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar usuario</button>
                        </div>
                    </div>
                    </div>
                </div>   
            </form>               
        </div>
        <!-- Tabla -->

        <table id="listaUsuarios" class="table table-bordered table-striped dt-responsive">
            <thead>
                <tr>
                    <th>Identificacion</th>
                    <th>Nombres</th>   
                     <th>{{ __('Email') }}</th>   
                     <th>Rol</th>      
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->identificacion }}</td>
                        <td>{{ $usuario->name }}</td>                      
                        <td>{{ $usuario->email }}</td>
                        <td>{{ $usuario->roll->nombre }}</td>
                        <td>
                            <div class="d-flex align-items-center ">
                                
                                <button class="btn btn-sm btn-danger" onclick="eliminarUsuario({{ $usuario->id }})">
                                    <i class="fa-regular fa-trash-can fa-lg" style="color: black"></i>
                                </button>
                                <a href="{{ route('usuarios.show',$usuario) }}" class="btn btn-sm btn-warning">
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
<script src="/js/modulos/usuarios.js"></script>
@endsection