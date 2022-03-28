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
                                    <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
        
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
        
                                <div class="row mb-3">
                                    <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('E-Mail Address') }}</label>
        
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
                                        <select class="form-select" name="rolUsuario" >
                                            <option selected>Seleccione...</option>
                                            <option value="1">Auxiliar administrativo</option>
                                            <option value="2">Operario</option>                                            
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
                    <th>ID</th>
                    <th>Nombres</th>                    
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->id }}</td>
                        <td>{{ $usuario->name }}</td>
                        
                        
                        <td>
                            <div class="d-flex align-items-center ">
                                <form action="{{ route('costos-de-infraestructura.destroy', $usuario) }}" method="POST">
                                    @method('DELETE')
                                    @csrf

                                    <input 
                                        type="submit" 
                                        value="Elminar" 
                                        class="btn btn-sm btn-danger "
                                        onclick="return confirm('¿desea eliminar el costo de infraestructura?')">
                                </form>

                                <a href="{{ route('costos-de-infraestructura.edit', $usuario) }}" class="btn btn-sm btn-warning"> Editar</a>
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
<script>
 $(document).ready(function() {
    $('#listaUsuarios').DataTable({
        "language": {
                "url": "/DataTables/Spanish.json"
                },
        "responsive": true
    });
} );   
</script>
@endsection