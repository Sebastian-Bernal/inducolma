@extends('layout')
@section('title', ' Maderas | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content') 
<div class="div container h-content ">        
    <div class="row">            
        <div class="col-12 col-sm-10 col-lg-6 mx-auto">
            
           
            <h1 class="display-6" >Maderas</h1>
            <hr>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#creaUsuario">
                Registrar Madera
            </button>
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        - {{ $error }} <br>
                    @endforeach
                </div>
                
            @endif
            <!-- Modal Crea maquina-->
            <form action="{{ route('maderas.store') }}" method="POST">
                @csrf
                <div class="modal fade" id="creaUsuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Registrar madera</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                             
                            <div class="card-body">                                                
                                                   
                                <div class="row mb-3">
                                    <label for="nombre" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="nombre" type="text" class="form-control @error('nombre') is-invalid @enderror text-uppercase" name="nombre" value="{{ old('nombre') }}" required autocomplete="nombre" autofocus>
        
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
                                        <input id="nombre_cientifico" type="text" class="form-control @error('nombre_cientifico') is-invalid @enderror text-uppercase" name="nombre_cientifico" value="{{ old('nombre_cientifico') }}" required autocomplete="nombre_cientifico" autofocus>
        
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
                                            <option value="ALTA DENSIDAD">ALTA DENSIDAD</option>
                                            <option value="BAJA DENSIDAD">BAJA DENSIDAD</option>
                                        </select>  
                                    </div>                                                                  
                                    
                                </div>                
                        </div>
                                
                            
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar madera</button>
                        </div>
                    </div>
                    </div>
                </div>   
            </form>               
        </div>
        <!-- Tabla -->

        <table id="listaMaderas" class="table table-bordered table-striped dt-responsive">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre madera</th>   
                    <th>Nombre cientifico</th>
                    <th>Densidad</th>         
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($maderas as $madera)
                    <tr>
                        <td>{{ $madera->id }}</td>
                        <td>{{ $madera->nombre }}</td>                      
                        <td>{{ $madera->nombre_cientifico }}</td>
                        <td>{{ $madera->densidad }}</td>
                        <td>
                            <div class="d-flex align-items-center ">
                                
                                <button class="btn btn-sm btn-danger" onclick="eliminarMadera({{ $madera}})">
                                    <i class="fa-regular fa-trash-can fa-lg" style="color: black"></i>
                                </button>
                                <a href="{{ route('maderas.show',$madera) }}" class="btn btn-sm btn-warning">
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
<script src="/js/modulos/maderas.js"></script>
@endsection