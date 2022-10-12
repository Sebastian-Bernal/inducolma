@extends('layouts.web')
@section('title', ' Trabajo maquina | Inducolma')

@section('submenu')
@include('modulos.sidebars.costos-side')
@endsection
@section('content')
<div class="container h-content ">

    <button style="display:none" id="users" onclick="listaUser({{ count($turno_usuarios) > 0 ? $turno_usuarios : ''}})"></button>

<div class="d-flex flex-wrap justify-content-center container-fluid">
    {{-- PROCESO --}}
    <div id="maquinas" class="container card  bg-light shadow m-2 rounded-3">
        <div class=" card-header bg-primary text-white">
            Proceso
        </div>
        <div class=" card-body">

            <div class="text-center">
                <label> Maquina a trabajar: </label>
                <h4 class="text-warning fw-bolder">{{ $turno->maquina->maquina }}</h4>
            </div>

        </div>


    </div>
    {{-- AUXILIARES --}}
    <div class="container card  bg-light shadow m-3 rounded-3">
        <div class=" card-header bg-secondary text-white">
            Auxiliares
        </div>
        <div class=" card-body">

            <div class="text-center">
                <label> Usuarios auxiliares en este proceso </label>
            </div>
            <div class="text-center" id="spinnerAuxiliares">

            </div>

            <div class="input-group mb-3 mt-3">

                <table class="table table-striped">
                    <thead>
                        <tr>

                            <th scope="col">Usuario</th>

                            <th scope="col">Acciones</th>

                        </tr>
                    </thead>
                    <tbody id="listarUsers">

                    </tbody>
                </table>

            </div>
        </div>


    </div>

    {{-- MODAL EVENTUALIDADES --}}
    <!-- Button trigger modal -->
    <button type="button"
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#staticBackdrop"
            hidden
            id="eventualidad">
    </button>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Evento falta del trabajador</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3 mt-3">
                    <select class="form-select" name="eventos" id="eventos">
                        @forelse ($eventos as $evento)
                            <option value="{{ $evento->id }}">{{ $evento->descripcion }}</option>
                        @empty
                            <option value="">Ningun evento encontrado</option>
                        @endforelse
                    </select>
                </div>

                <div class="form-floating">
                    <textarea class="form-control" placeholder="Observaciones" id="observacionEvento" style="height: 100px"></textarea>
                    <label for="floatingTextarea2">Observaciones:</label>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cerrarEvento">Cerrar</button>
            <button type="button" class="btn btn-primary" onclick="guardaEvento({{ $turno->user_id }})">Guardar evento</button>
            </div>
        </div>
        </div>
    </div>

</div>



</div>

@endsection

@section('js')
<script src="/js/modulos/alertas-swift.js"></script>
<script src="/js/modulos/maquina_inicia.js"></script>
@endsection
