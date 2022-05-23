@extends('layout')

@section('css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css"/> 

@endsection

@section('title', ' Descripciones | inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content')
    <div class="div container h-content">        
        <div class="row">            
            <div class="col-12 col-sm-10 col-lg-6 mx-auto">
                
               
                <h1 class="display-6" >Crear desripci&oacute;n</h1>
                <hr>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#creaDescripcion">
                    Crear desripci&oacute;n
                </button>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            - {{ $error }} <br>
                        @endforeach
                    </div>
                    
                @endif
                <!-- Modal Crea maquina-->
                <form action="{{ route('descripciones.store') }}" method="POST">
                    @csrf
                    <div class="modal fade" id="creaDescripcion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Crea desripci&oacute;n</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="input-group mb-3"> 
                                    <span class="input-group-text">Descripci&oacute;n:</span>                                   
                                    <input type="text" 
                                            class="form-control text-uppercase" 
                                            placeholder="Nombre descripcion" 
                                            name="descripcion" 
                                            id="descripcion" 
                                            required>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Operaci&oacute;n:</span>                               
                                    <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="idOperacion" id="idOperacion">
                                        @foreach ($operaciones as $operacion)
                                            <option value="{{ $operacion->id }}">{{ $operacion->operacion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar desripci&oacute;n</button>
                            </div>
                        </div>
                        </div>
                    </div>   
                </form>               
            </div>
            <!-- Tabla -->

            <table id="listaDescripciones" class="table table-bordered table-striped dt-responsive">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>desripci&oacute;n</th>
                        <th>Operaci&oacute;n</th>                            
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($descripciones as $descripcion)
                        <tr>
                            <td>{{ $descripcion->id }}</td>
                            <td>{{ $descripcion->descripcion }}</td>
                            <td>{{ $descripcion->operacion->operacion }}</td>
                            <td>
                                <div class="d-flex align-items-center ">
                                    <form action="{{ route('descripciones.destroy', $descripcion) }}" method="POST">
                                        @method('DELETE')
                                        @csrf

                                        <input 
                                            type="submit" 
                                            value="Elminar" 
                                            class="btn btn-sm btn-danger "
                                            onclick="return confirm('¿desea eliminar la descripcion: {{ $descripcion->descripcion }}?')">
                                    </form>

                                    <a href="{{ route('descripciones.edit', $descripcion) }}" class="btn btn-sm btn-warning"> Editar</a>
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
    $('#listaDescripciones').DataTable({
        "language": {
                "url": "/DataTables/Spanish.json"
                },
        "responsive": true
    });
} );   
</script>
@endsection
