@extends('layouts.web')

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


                <h4 class="display-6" >Crear Maquina</h4>
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
                            <h4 class="modal-title" id="exampleModalLabel">Crea Maquina</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Maquina:</span>
                                    <input type="text"
                                        class="form-control text-uppercase"
                                        placeholder="Nombre maquina"
                                        name="maquina"
                                        id="maquina"
                                        required>
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text">Corte:</span>
                                    <select name="corte"
                                            id="corte"
                                            class="form-select"
                                            required>
                                        <option selected>Seleccione un tipo de corte</option>
                                        <option value="INICIAL">INICIAL</option>
                                        <option value="INTERMEDIO">INTERMEDIO</option>
                                        <option value="FINAL">FINAL</option>
                                        <option value="ACABADOS">ACABADOS DE ITEM</option>
                                        <option value="ASERRIO">ASERRIO</option>
                                        <option value="ENSAMBLE">ENSAMBLE</option>
                                        <option value="ACABADO_ENSAMBLE">ACAMBADOS DE ENSAMBLE</option>
                                        <option value="REASERRIO">REASERRIO</option>

                                    </select>
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
                        <th>Tipo de corte</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($maquinas as $maquina)
                        <tr>
                            <td>{{ $maquina->id }}</td>
                            <td>{{ $maquina->maquina }}</td>
                            <td>{{ $maquina->corte }}</td>
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
