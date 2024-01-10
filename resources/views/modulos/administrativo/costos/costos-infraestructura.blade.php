@extends('layouts.web')


@section('title', 'Estandar unidades por minuto | inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content')
    <div class="div container h-content ">
        <div class="row">
            <div class="col-12 col-sm-10 col-lg-6 mx-auto">


                <h1 class="display-6" >Crear tiempo estándar </h1>
                <hr>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#creaCostoInfraestructura">
                    Crear tiempo estándar
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
                            <h5 class="modal-title" id="exampleModalLabel">Crear tiempo estándar </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Maquina:</span>
                                    <select class="form-select form-select-sm"
                                            aria-label=".form-select-sm example"
                                            name="maquina"
                                            id="idMaquina"
                                            required>
                                            <OPtion value="" selected>Seleccione una maquina...</OPtion>
                                        @foreach ($maquinas as $maquina)
                                            <option value="{{ $maquina->id }}">{{ $maquina->maquina }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Tipo material:</span>
                                    <select
                                        class="form-select form-select-sm"
                                        aria-label=".form-select-sm example"
                                        name="tipo_material"
                                        id="tipoMaterial"
                                        required>
                                        <option value="" selected>Seleccione un tipo de material...</option>
                                        <option value="BASTIDORES">BASTIDORES</option>
                                        <option value="BLOQUES">BLOQUES</option>
                                        <option value="TABLAS">TABLAS</option>
                                        <option value="CUARTONES">CUARTONES</option>
                                        <option value="TACOS">TACOS</option>
                                        <option value="PUNTAS">PUNTAS</option>
                                        <option value="ESTIBAS">ESTIBAS</option>
                                        <option value="TROZA">TROZA</option>

                                    </select>
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text">Tipo madera:</span>
                                    <select class="form-select form-select-sm"
                                            aria-label=".form-select-sm example"
                                            name="tipo_madera"
                                            id="tipoMadera"
                                            required>
                                        <option value="" selected>Seleccione una madera...</option>
                                        @foreach ($maderas as $madera)
                                            <option value="{{ $madera->id }}"

                                                >{{ $madera->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Estandar unidades por minuto:</span>
                                    <input type="number"
                                            class="form-control"
                                            min="1"
                                            step="0.1"
                                            name="unidades_minuto"
                                            id="estandarUnidadesMinuto"
                                            placeholder="Ej. 10"
                                            required>
                                </div>



                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
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
                        <th>Id</th>
                        <th>Maquina</th>
                        <th>Tipo de material</th>
                        <th>Tipo de madera</th>
                        <th>Estandar Unidades por Miuto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($estadaresUnidadesMinuto as $estadarUnidadesMinuto)
                        <tr>
                            <td>{{ $estadarUnidadesMinuto->id }}</td>
                            <td>{{ $estadarUnidadesMinuto->maquina->maquina }}</td>
                            <td>{{ $estadarUnidadesMinuto->tipo_material }}</td>
                            <td>{{ $estadarUnidadesMinuto->madera->descripcion }}</td>
                            <td>{{ $estadarUnidadesMinuto->estandar_u_minuto }}</td>
                            <td>
                                <div class="d-flex align-items-center ">
                                    <form action="{{ route('costos-de-infraestructura.destroy', $estadarUnidadesMinuto) }}" method="POST">
                                        @method('DELETE')
                                        @csrf

                                        <input
                                            type="submit"
                                            value="Elminar"
                                            class="btn btn-sm btn-danger "
                                            onclick="return confirm('¿desea eliminar el costo de infraestructura?')">
                                    </form>

                                    <a href="{{ route('costos-de-infraestructura.edit', $estadarUnidadesMinuto) }}" class="btn btn-sm btn-warning"> Editar</a>
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
