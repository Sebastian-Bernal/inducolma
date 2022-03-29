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
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#creaUsuario">
                Crear rol
            </button>
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        - {{ $error }} <br>
                    @endforeach
                </div>
                
            @endif
            <!-- Modal Crea maquina-->
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="modal fade" id="creaUsuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Crea rol</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                             
                            <div class="card-body">                                                
                                                   
                                <div class="row mb-3">
                                    <label for="nombre" class="col-md-4 col-form-label text-md-end">{{ __('nombre') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="nombre" type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" value="{{ old('nombre') }}" required autocomplete="nombre" autofocus>
        
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
                                        <textarea name="descripcion" id="descripcion" cols="30" rows="4" class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion') }}</textarea>
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
                                            <option selected>Seleccione...</option>
                                            <option value="1">1 - recepcion de maderas</option>
                                            <option value="2">2 - operario de maquinas, cubicaje</option>
                                            <option value="3">3 - Auxiliar administrativo</option>                                            
                                        </select>  
                                    </div>                                                                  
                                    
                                </div>                
                        </div>
                                
                            
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar rol</button>
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
                    <th>Rol id</th>
                    <th>Nombre rol</th>   
                    <th>Descripcion</th> 
                    <th>Nivel</th>       
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($roles as $rol)
                    <tr>
                        <td>{{ $rol->id }}</td>
                        <td>{{ $rol->nombre }}</td>
                        <th>{{ $rol->descripcion }}</th>
                        <th>{{ $rol->nivel }}</th>
                        <td>
                            {{-- <div class="d-flex align-items-center ">
                                
                                <button class="btn btn-sm btn-danger" onclick="eliminarUsuario({{ $rol}})">
                                    <i class="fa-regular fa-trash-can fa-lg" style="color: black"></i>
                                </button>
                                <a href="{{ route('usuarios.show',$rol) }}" class="btn btn-sm btn-warning">
                                    <i class="fa-solid fa-pen-to-square fa-lg"></i>
                                </a>
                               
                            </div> --}}
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