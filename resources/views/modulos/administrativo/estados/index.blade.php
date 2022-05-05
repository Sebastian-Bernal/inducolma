@extends('layouts.web')
@section('title', ' Estados | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content') 
    <div class="div container h-content ">        
        <div class="row">            
            <div class="col-12 col-sm-10 col-lg-6 mx-auto">
                
            
                <h1 class="display-6" >Estados</h1>
                <hr>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#creacliente">
                    Crear estado
                </button>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            - {{ $error }} <br>
                        @endforeach
                    </div>
                    
                @endif
                <!-- Modal Crea maquina-->
                <form action="{{ route('estados.store') }}" method="POST">
                    @csrf
                    <div class="modal fade" id="creacliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Crea estado</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                                    name="descripcion" value="{{ old('descripcion') }}" 
                                                    required autocomplete="descripcion" autofocus
                                                    onkeyup="mayusculas()">
            
                                            @error('descripcion')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                            </div>
                                    
                                
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar estado</button>
                            </div>
                        </div>
                        </div>
                    </div>   
                </form>               
            </div>
            <!-- Tabla -->

            <table id="listaestados" class="table table-bordered table-striped dt-responsive">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Descripci&oacute;n</th>   
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($estados as $estado)
                        <tr>
                            <td>{{ $estado->id }}</td>
                            <td>{{ $estado->descripcion }}</td>                      
                            
                            
                            <td>
                                <div class="d-flex align-items-center ">
                                    
                                    <button class="btn btn-sm btn-danger" onclick="eliminarEstado({{ $estado }})">
                                        <i class="fa-regular fa-trash-can fa-lg" style="color: black"></i>
                                    </button>
                                    <a href="{{ route('estados.show',$estado) }}" class="btn btn-sm btn-warning">
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
<script src="/js/modulos/estados.js"></script>
@endsection