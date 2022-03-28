@extends('layout')


@section('title', ' Costos de infraestructura | inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content')
    <div class="div container h-content ">        
        <div class="row">            
            <div class="col-12 col-sm-10 col-lg-6 mx-auto">
                
               
                <h1 class="display-6" >Crear costo de costo de infraestructura</h1>
                <hr>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#creaCostoInfraestructura">
                    Crear costo de costo de infraestructura
                </button>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            - {{ $error }} <br>
                        @endforeach
                    </div>
                    
                @endif
                <!-- Modal Crea maquina-->
                <form action="{{ route('costos-de-infraestructura.store') }}" method="POST">
                    @csrf
                    <div class="modal fade" id="creaCostoInfraestructura" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Crea costo de costo de infraestructura</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="input-group mb-3"> 
                                    <span class="input-group-text">Valor operativo:</span>                            
                                    <input type="number" class="form-control" placeholder="Valor operativo" step="0.01" name="valorOperativo" id="valorOperativo" required>
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text">Tipo de material</span>                               
                                    <input type="text" class="form-control" placeholder="Tipo de material"  name="tipoMaterial" id="tipoMaterial" required>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Tipo madera:</span>                               
                                    <input type="text" class="form-control" placeholder="tipo madera"  name="tipoMadera" id="tipoMadera" required>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Proceso madera:</span>                               
                                    <input type="text" class="form-control" placeholder="Proceso madera"  name="procesoMadera" id="procesoMadera" required>
                                </div>
                                
                                <div class="input-group mb-3"> 
                                    <span class="input-group-text">Promedio piezas:</span>                            
                                    <input type="number" class="form-control" placeholder="Promedio piezas" step="0.01" name="promedioPiezas" id="promedioPiezas" required>
                                </div>
                                <div class="input-group mb-3"> 
                                    <span class="input-group-text">Minimo piezas:</span>                            
                                    <input type="number" class="form-control" placeholder="Minimo piezas" step="0.01" name="minimoPiezas" id="minimoPiezas" required>
                                </div>
                                <div class="input-group mb-3"> 
                                    <span class="input-group-text">Maximo piezas:</span>                            
                                    <input type="number" class="form-control" placeholder="Maximo piezas" step="0.01" name="maximoPiezas" id="maximoPiezas" required>
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text">Maquina:</span>                                 
                                    <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="idMaquina" id="idMaquina">
                                        @foreach ($maquinas as $maquina)
                                            <option value="{{ $maquina->id }}">{{ $maquina->maquina }}</option>
                                        @endforeach 
                                    </select>
                                </div>
                                
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar costo de costo de infraestructura</button>
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
                        <th>Valor operativo</th>
                        <th>Tipo de material</th>                            
                        <th>Tipo de madera</th>
                        <th>Proceso de madera</th>
                        <th>Promedio de piezas</th>
                        <th>Minimo de piezas</th>
                        <th>Maximo de piezas</th>
                        <th>Maquina</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($costosIinfraestructura as $costoInfraestructura)
                        <tr>
                            <td>{{ $costoInfraestructura->id }}</td>
                            <td>{{ $costoInfraestructura->valor_operativo }}</td>
                            <td>{{ $costoInfraestructura->tipo_material }}</td>
                            <td>{{ $costoInfraestructura->tipo_madera }}</td>
                            <td>{{ $costoInfraestructura->proceso_madera }}</td>
                            <td>{{ $costoInfraestructura->promedio_piezas }}</td>
                            <td>{{ $costoInfraestructura->minimo_piezas }}</td>
                            <td>{{ $costoInfraestructura->maximo_piezas }}</td>
                            <td>{{ $costoInfraestructura->maquina->maquina }}</td>
                            
                            <td>
                                <div class="d-flex align-items-center ">
                                    <form action="{{ route('costos-de-infraestructura.destroy', $costoInfraestructura) }}" method="POST">
                                        @method('DELETE')
                                        @csrf

                                        <input 
                                            type="submit" 
                                            value="Elminar" 
                                            class="btn btn-sm btn-danger "
                                            onclick="return confirm('¿desea eliminar el costo de infraestructura?')">
                                    </form>

                                    <a href="{{ route('costos-de-infraestructura.edit', $costoInfraestructura) }}" class="btn btn-sm btn-warning"> Editar</a>
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
    $('#listaMaquinas').DataTable({
        "language": {
                "url": "/DataTables/Spanish.json"
                },
        "responsive": true
    });
} );   
</script>


@endsection
