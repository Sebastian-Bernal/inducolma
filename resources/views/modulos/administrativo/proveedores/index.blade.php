@extends('layout')
@section('title', ' Proveedores | inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content') 
<div class="div container h-content ">        
    <div class="row">            
        <div class="col-12 col-sm-10 col-lg-6 mx-auto">
            
           
            <h1 class="display-6" >Proveedores</h1>
            <hr>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#creaUsuario">
                Crear proveedor
            </button>
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        - {{ $error }} <br>
                    @endforeach
                </div>
                
            @endif
            <!-- Modal Crea maquina-->
            <form action="{{ route('proveedores.store') }}" method="POST">
                @csrf
                <div class="modal fade" id="creaUsuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Crea Proveedor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                             
                            <div class="card-body">                                                
                                                   
                                <div class="row mb-3">
                                    <label for="identificacion" class="col-md-4 col-form-label text-md-end">{{ __('Identificacion') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="identificacion" type="text" class="form-control @error('identificacion') is-invalid @enderror" name="identificacion" value="{{ old('identificacion') }}" required autocomplete="identificacion" autofocus>
        
                                        @error('identificacion')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="nombre" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="nombre" type="text" class="form-control @error('nombre') is-invalid @enderror text-uppercase" name="nombre" value="{{ old('name') }}" required autocomplete="nombre" autofocus>
        
                                        @error('nombre')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
        
                                <div class="row mb-3">
                                    <label for="direccion" class="col-md-4 col-form-label text-md-end">{{ __('Address') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="direccion" type="text" class="form-control @error('direccion') is-invalid @enderror text-uppercase" name="direccion" value="{{ old('direccion') }}" required autocomplete="direccion">
        
                                        @error('direccion')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div> 

                                <div class="row mb-3">
                                    <label for="telefono" class="col-md-4 col-form-label text-md-end">{{ __('Telefono ') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="telefono" type="number" class="form-control @error('telefono') is-invalid @enderror" name="telefono" value="{{ old('telefono') }}" required autocomplete="telefono">
        
                                        @error('telefono')
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
                                    <label for="razonSocial" class="col-md-4 col-form-label text-md-end">{{ __('Razon social') }}</label>
                                    <div class="col-md-6">
                                        <select class="form-select" name="razonSocial" >
                                            <option selected>Seleccione...</option>
                                            <option value="Sociedades Limitadas - LTDA">Sociedades Limitadas - LTDA</option>
                                            <option value="Sociedades Anónimas – S.A">Sociedades Anónimas – S.A</option>   
                                            <option value="Sociedad en Comandita – & Cía.">Sociedad en Comandita – & Cía.</option>   
                                            <option value="Sociedad en Comandita Simple – S. en C.">Sociedad en Comandita Simple – S. en C.</option>
                                            <option value="Sociedad en Comandita por Acciones – S.C.A.">Sociedad en Comandita por Acciones – S.C.A.</option>  
                                            <option value="Sociedad por Acciones Simplificada – S.A.S.">Sociedad por Acciones Simplificada – S.A.S.</option> 
                                            <option value="Sociedad Colectiva">Sociedad Colectiva</option>                                   
                                        </select>  
                                    </div>                                                                  
                                    
                                </div>                
                        </div>
                                
                            
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar proveedor</button>
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
                    <th>Direccion</th>
                    <th>Telefono</th>
                    <th>Email</th>
                    <th>Razon Social</th>
                    <th>Calificacion</th>                   
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($proveedores as $proveedor)
                    <tr>
                        <td>{{ $proveedor->identificacion }}</td>
                        <td>{{ $proveedor->nombre }}</td>
                        <td>{{ $proveedor->direccion }}</td>
                        <td>{{ $proveedor->telefono }}</td>
                        <td>{{ $proveedor->email }}</td>
                        <td>{{ $proveedor->razon_social }}</td>
                        <td>{{ $proveedor->calificacion }}</td>
                        <td>
                            <div class="d-flex align-items-center ">
                                
                                <button class="btn btn-sm btn-danger" onclick="eliminarUsuario({{ $proveedor }})">
                                    <i class="fa-regular fa-trash-can fa-lg" style="color: black"></i>
                                </button>
                                <a href="{{ route('proveedores.show',$proveedor) }}" class="btn btn-sm btn-warning">
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
<script src="/js/modulos/proveedores.js"></script>
@endsection