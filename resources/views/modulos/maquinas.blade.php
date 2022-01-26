@extends('layout')

@section('css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css"/> 

@endsection

@section('title', 'Crear Maquina | inducolma')
    
@section('submenu')
    <div class="offcanvas offcanvas-start" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
        <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasScrollingLabel">Costos</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="mt-3">                
                <a href="{{ route('maquinas.index') }}" class="btn btn-light ">Maquinas</a>
            </div>
            <div class="mt-3">
                <a href="#" class="btn btn-light">Operaciones</a>
            </div>
            <div class="mt-3">
                <a href="#" class="btn btn-light ">Descripciones</a>
            </div>
           
            



        </div>
  </div>
@endsection
@section('content')
    <div class="div container h-content">        
        <div class="row">            
            <div class="col-12 col-sm-10 col-lg-6 mx-auto">
                
               
                <h1 class="display-5" >Crear Maquina</h1>
                <hr>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#creaMaquina">
                    Crear maquina
                </button>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            - {{ $error }} <br>
                        @endforeach
                    </div>
                    
                @endif
                <!-- Modal Crea maquina-->
                <form action="{{ route('maquinas.store') }}" method="POST">
                    @csrf
                    <div class="modal fade" id="creaMaquina" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Crea Maquina</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="input-group mb-3">                               
                                    <input type="text" class="form-control" placeholder="Nombre maquina" name="maquina" id="maquina" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar maquina</button>
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
                        <th>Maquina</th>                            
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($maquinas as $maquina)
                        <tr>
                            <td>{{ $maquina->id }}</td>
                            <td>{{ $maquina->maquina }}</td>
                            <td>
                                <div class="d-flex align-items-center ">
                                    <form action="{{ route('maquinas.destroy', $maquina) }}" method="POST">
                                        @method('DELETE')
                                        @csrf

                                        <input 
                                            type="submit" 
                                            value="Elminar" 
                                            class="btn btn-sm btn-danger "
                                            onclick="return confirm('¿desea eliminar la maquina: {{ $maquina->maquina }}?')">
                                    </form>

                                    <a href="{{ route('maquinas.edit', $maquina) }}" class="btn btn-sm btn-warning"> Editar</a>
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
