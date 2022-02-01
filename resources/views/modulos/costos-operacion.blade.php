@extends('layout')

@section('css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css"/> 

@endsection

@section('title', ' Costos operacion | inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content')
    <div class="div container h-content">        
        <div class="row">            
            <div class="col-12 col-sm-10 col-lg-6 mx-auto">
                
               
                <h1 class="display-5" >Crear costo de operaci&oacute;n</h1>
                <hr>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#creaDescripcion">
                    Crear costo de operaci&oacute;n
                </button>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            - {{ $error }} <br>
                        @endforeach
                    </div>
                    
                @endif
                <!-- Modal Crea maquina-->
                <form action="{{ route('costos-de-operacion.store') }}" method="POST">
                    @csrf
                    <div class="modal fade" id="creaDescripcion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Crea costo de operaci&oacute;n</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="input-group mb-3"> 
                                    <span class="input-group-text">Cantidad:</span>                            
                                    <input type="number" class="form-control" placeholder="Cantidad" step="0.01" name="cantidad" id="cantidad" required>
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text">Valor mes:</span>                               
                                    <input type="number" class="form-control" placeholder="valor mes" step="0.01" name="valorMes" id="valorMes" required>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Valor dia:</span>                               
                                    <input type="number" class="form-control" placeholder="valor dia" step="0.01" name="valorDia" id="valorDia" required>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Costo Kw/h:</span>                               
                                    <input type="number" class="form-control" placeholder="costo kw/h" step="0.01" name="costokwh" id="costokwh" required>
                                </div>
                                
                                <div class="input-group mb-3">                               
                                    <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="idMaquina" id="idMaquina">
                                        @foreach ($maquinas as $maquina)
                                            <option value="{{ $maquina->id }}">{{ $maquina->maquina }}</option>
                                        @endforeach 
                                    </select>
                                </div>
                                <div class="input-group mb-3">                               
                                    <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="idDescripcion" id="idDescripcion">
                                        @foreach ($descripciones as $descripcion)
                                            <option value="{{ $descripcion->id }}">{{ $descripcion->descripcion }}</option>
                                        @endforeach 
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar costo de operaci&oacute;n</button>
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
                        <th>Cantidad</th>
                        <th>Valor mes</th>                            
                        <th>Valor d&iacute;a</th>
                        <th>Costo Kw/h</th>
                        <th>Maquina</th>
                        <th>Operacion</th>
                        <th>Descripcion</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($costosOperacion as $costoOperacion)
                        <tr>
                            <td>{{ $costoOperacion->id }}</td>
                            <td>{{ $costoOperacion->cantidad }}</td>
                            <td>{{ $costoOperacion->valor_mes }}</td>
                            <td>{{ $costoOperacion->valor_dia }}</td>
                            <td>{{ $costoOperacion->costo_kwh }}</td>
                            <td>{{ $costoOperacion->maquina->maquina }}</td>
                            <td>{{ $costoOperacion->descripcion->operacion->operacion }}</td>
                            <td>{{ $costoOperacion->descripcion->descripcion }}</td>
                            <td>
                                <div class="d-flex align-items-center ">
                                    <form action="{{ route('costos-de-operacion.destroy', $costoOperacion) }}" method="POST">
                                        @method('DELETE')
                                        @csrf

                                        <input 
                                            type="submit" 
                                            value="Elminar" 
                                            class="btn btn-sm btn-danger "
                                            onclick="return confirm('¿desea eliminar el costo de operacion?')">
                                    </form>

                                    <a href="{{ route('costos-de-operacion.edit', $costoOperacion) }}" class="btn btn-sm btn-warning"> Editar</a>
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
