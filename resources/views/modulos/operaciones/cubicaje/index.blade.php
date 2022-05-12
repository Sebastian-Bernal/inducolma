@extends('layouts.web')
@section('title', ' Entradas cubicaje | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content') 
<div class="div container h-content ">        
    <div class="row">            
        <div class="col-12 col-sm-10 col-lg-6 mx-auto">
            
           
            <h1 class="display-6" >Cubicajes</h1>
            <hr>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary mb-3" id="registrar" data-bs-toggle="modal" data-bs-target="#creaUsuario">
                Cubicar madera
            </button>
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        - {{ $error }} <br>
                    @endforeach
                </div>
                
            @endif
            <!-- Modal Crea maquina-->
            <form id="formRegistro" action="{{ route('cubicaje.create') }}" action="POST">
                
                <div class="modal fade" id="creaUsuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content " >
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Buscar entrada de madera</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body ">                               
                                <div class=" ">
                                    <input type="entrada"
                                     name="entrada" 
                                     id="entrada" 
                                     class="form-control @error('entrada') is-invalid @enderror" 
                                     required 
                                     autocomplete="entrada" 
                                     autofocus> 
                                    @error('entrada')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" >Cancelar</button>
                                <button type="submit" class="btn btn-primary">Buscar</button>
                            </div>
                        </div>
                    </div>
                </div>   
            </form>
            
            
        </div>
        <!-- Tabla -->

        <table id="listaCubicajes" class="table table-bordered table-striped dt-responsive">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Numero de entrada</th>
                    <th>Paqueta</th>   
                    <th>BLoque</th>
                    <th>Largo</th>         
                    <th>Ancho</th>
                    <th>Alto</th>
                    {{-- <th>cm3</th>
                    <th>Pulgadas cuadradas</th> --}}
                    <th>Fecha creaci&oacute;n</th>
                    {{-- <th>Acciones</th> --}}
                </tr>
            </thead>

            <tbody>
                @foreach ($cubicajes as $cubicaje)
                    <tr>
                        <td>{{ $cubicaje->id }}</td>
                        <td>{{ $cubicaje->entrada_madera_id }}</td>
                        <td>{{ $cubicaje->paqueta }}</td>
                        <td>{{ $cubicaje->bloque }}</td>                      
                        <td>{{ $cubicaje->largo }}</td>
                        <td>{{ $cubicaje->ancho }}</td>
                        <td>{{ $cubicaje->alto }}</td>
                        {{-- <td>{{ $cubicaje->cm3 }}</td>
                        <td>{{ $cubicaje->pulgadas_cuadradas }}</td> --}}
                        <td>{{ $cubicaje->created_at }}</td>
                       
                        {{-- <td>
                            @can('update-delete-cubicaje')
                            <div class="d-flex align-items-center ">
                                    
                                    <button class="btn btn-sm btn-danger" onclick="eliminarMadera({{ $cubicaje}})">
                                        <i class="fa-regular fa-trash-can fa-lg" style="color: black"></i>
                                    </button>
                                    <a href="{{ route('cubicajes-maderas.show',$cubicaje) }}" class="btn btn-sm btn-warning">
                                        <i class="fa-solid fa-pen-to-square fa-lg"></i>
                                    </a>
                                
                                </div>
                            @endcan
                            @can('ver-cubicaje')
                                <a href="{{ route('cubicajes-maderas.show',$cubicaje->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            @endcan
                            
                        </td> --}}
                    </tr> 
                @endforeach
                
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('js')
<script src="/js/modulos/cubicaje.js"></script>
<script>
        $('#listaCubicajes').DataTable({
        "language": {
                "url": "/DataTables/Spanish.json"
                },
        "responsive": true, 
        "pageLength": 5
        
    });
</script>
@endsection