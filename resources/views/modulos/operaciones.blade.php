@extends('layout')

@section('css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css"/> 

@endsection

@section('title', ' Maquinas | inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content')
    <div class="div container h-content">        
        <div class="row">            
            <div class="col-12 col-sm-10 col-lg-6 mx-auto">
                
               
                <h1 class="display-5" >Crear Operaci&oacute;n</h1>
                <hr>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#creaOperacion">
                    Crear Operaci&oacute;n
                </button>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            - {{ $error }} <br>
                        @endforeach
                    </div>
                    
                @endif
                <!-- Modal Crea maquina-->
                <form action="{{ route('operaciones.store') }}" method="POST">
                    @csrf
                    <div class="modal fade" id="creaOperacion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Crea Operaci&oacute;n</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="input-group mb-3">                               
                                    <input type="text" class="form-control" placeholder="Nombre operacion" name="operacion" id="operacion" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar Operaci&oacute;n</button>
                            </div>
                        </div>
                        </div>
                    </div>   
                </form>               
            </div>
            <!-- Tabla -->

            <table id="listaMaquinas" class="table table-bordered table-striped dt-responsive">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Operaci&oacute;n</th>                            
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($operaciones as $operacion)
                        <tr>
                            <td>{{ $operacion->id }}</td>
                            <td>{{ $operacion->operacion }}</td>
                            <td>
                                <div class="d-flex align-items-center ">
                                    <form action="{{ route('operaciones.destroy', $operacion) }}" method="POST">
                                        @method('DELETE')
                                        @csrf

                                        <input 
                                            type="submit" 
                                            value="Elminar" 
                                            class="btn btn-sm btn-danger "
                                            onclick="return confirm('¿desea eliminar la maquina: {{ $operacion->operacion }}?')">
                                    </form>

                                    <a href="{{ route('operaciones.edit', $operacion) }}" class="btn btn-sm btn-warning"> Editar</a>
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
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>


<script>
 $(document).ready(function() {
    $('#listaMaquinas').DataTable();
} );   
</script>
@endsection
