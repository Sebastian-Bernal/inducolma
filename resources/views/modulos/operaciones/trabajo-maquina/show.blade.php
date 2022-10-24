@extends('layouts.web')
@section('title', ' Procesos | Inducolma')
@section('submenu')
@include('modulos.sidebars.costos-side')
@endsection
@section('content')
<div class="div container h-content ">
    <div class="d-flex flex-wrap row  m-auto align-items-center container-fluid ">
        <div
            class="container col-xl-12 col-lg-12 col-md-12 col-sm-12 bg-light shadow rounded-3 min-vh-100 mb-2 mt-2 overflow-scroll">
            <div class="d-flex flex-wrap align-items-center ">
                <div class="self-start col-md-6 col-sm-12 col-12 ">
                    <button class="btn btn-warning text-light mt-2 mb-2 w-100 rounded-pill" type="button"
                        data-bs-toggle="collapse" data-bs-target="#estadoMaquina" aria-expanded="false"
                        aria-controls="estadoMaquina">
                        ESTADOS DE LA MAQUINA
                    </button>
                    <div class="collapse " id="estadoMaquina">
                        <div class="card card-body border border-warning border-4 rounded-3">
                            @forelse ($estados as $estado)
                            <button type="button"
                                    class="btn w-80 {{ $estado->id == $estado_actual->estado_id ? 'btn-primary' : 'btn-secondary' }} text-light m-2"
                                    onclick="estadoDeMaquina({{ $estado.','.$maquina }})"
                                    id='{{ "estado$estado->id" }}'>
                                    {{ $estado->descripcion }}
                            </button>
                            @empty
                            <span>Ningun estado encontrado</span>
                            @endforelse


                        </div>
                    </div>
                </div>
                <div class="self-end col-md-6 col-sm-12 col-12">
                    <button class="btn btn-primary text-light mt-2 mb-2 w-100 rounded-pill" type="button"
                        data-bs-toggle="collapse" data-bs-target="#eventoMaquina" aria-expanded="false"
                        aria-controls="eventoMaquina" id="eventosDeMaquina">
                        EVENTOS DE LA MAQUINA
                    </button>
                    <div class="collapse " id="eventoMaquina">
                        <div class="card card-body border border-primary border-4 rounded-3">
                            @forelse ($tipos_evento as $tipo_evento)
                            <button type="button" class="btn w-80 btn-primary text-light m-2"
                                id="{{ 'tipoEvento'.$tipo_evento->id }}"
                                onclick="listarEventos({{ $tipo_evento->id .','. $eventos }})">
                                {{ $tipo_evento->tipo_evento }}
                            </button>
                            @empty
                            <span>Ningun tipo de evento encontrado, por favor agrege uno</span>
                            @endforelse


                        </div>
                    </div>
                </div>


            </div>

            <div class="text-primary">
                <h1 class="display-6">Ordenes de produccion pendientes</h1>
                <hr>
            </div>


            <!-- Tabla -->
            <div>
                <table id="listaOrdenes" class="table table-bordered table-striped dt-responsive"
                    style="box-sizing: border-box">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Viaje</th>
                            <th>Paqueta</th>
                            <th>Bloque inicial</th>
                            <th>BLoque final</th>
                            <th>Observación</th>
                            <th>Entrada</th>
                            <th>Salida</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($procesos as $proceso)
                        <tr>
                            <td>{{ $proceso->id }}</td>
                            <td>{{ $proceso->cubicaje->entrada_madera_id }}</td>
                            <td>{{ $proceso->cubicaje->paqueta }}</td>
                            <td>{{ $proceso->orden_produccion->transformaciones->where('tipo_corte',
                                'INICIAL')->load('cubicaje')->min('cubicaje.bloque') }}</td>
                            <td>{{ $proceso->orden_produccion->transformaciones->where('tipo_corte',
                                'INICIAL')->load('cubicaje')->max('cubicaje.bloque') }}</td>
                            <th>{{ $proceso->observacion }}</th>
                            <td>{{ $proceso->entrada }}</td>
                            <td>{{ $proceso->salida }}</td>
                            <td>{{ $proceso->estado }}</td>
                            <td>
                                <div class="d-flex align-items-center ">
                                    <a href="{{ route('trabajo-maquina.show',$proceso) }}"
                                        class="btn btn-sm btn-primary">
                                        <i class="fa-solid fa-person-digging"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
        <!-- Button trigger modal -->
        <button type="button"
                class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#eventosModal"
                hidden
                id="modalEventos">
        </button>

        <!-- Modal -->
        <div class="modal fade" id="eventosModal" tabindex="-1" aria-labelledby="eventosModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="eventosModalLabel">Eventos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="eventos">
                    <div class="input-group mb-3 mt-3">
                        <select  id="listaEventos"
                                name="eventosMaquina"
                                type="text"
                                class="form-control  text-uppercase"
                                name="listaEventos"
                                required
                                autofocus>

                        </select>
                    </div>
                    <div class="form-floating">
                        <textarea class="form-control" placeholder="Observaciones" id="observacionEvento" style="height: 100px"></textarea>
                        <label for="floatingTextarea2">Observaciones:</label>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cerrarEvento">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="guardaEvento({{ Auth::user()->id.','.$maquina }})">Guardar evento</button>
                </div>
            </div>
            </div>
        </div>

    </div>

    <hr>

</div>

@endsection

@section('js')
<script src="/js/modulos/alertas-swift.js"></script>
<script src="/js/modulos/maquina_ordenes.js"></script>
@endsection
