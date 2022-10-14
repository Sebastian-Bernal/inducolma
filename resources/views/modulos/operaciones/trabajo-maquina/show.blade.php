@extends('layouts.web')
@section('title', ' Procesos | Inducolma')

@section('submenu')
@include('modulos.sidebars.costos-side')
@endsection
@section('content')
<div class="div container h-content ">
    <div class="d-flex flex-wrap row  m-auto align-items-center container-fluid ">
       <div class="container col-xl-12 col-lg-12 col-md-12 col-sm-12 bg-light shadow rounded-3  min-vh-100 mb-2 mt-2 overflow-scroll"> 
        <p class="d-flex align-items-center">
            <button class="btn btn-warning text-light m-2 self-start" type="button" data-bs-toggle="collapse" data-bs-target="#estadoMaquina" aria-expanded="false" aria-controls="estadoMaquina">
              ESTADOS DE LA MAQUINA
            </button>
            <button class="btn btn-primary text-light m-2 self-end" type="button" data-bs-toggle="collapse" data-bs-target="#eventoMaquina" aria-expanded="false" aria-controls="eventoMaquina">
                EVENTOS DE LA MAQUINA
              </button>
          </p>
          <div class="collapse" id="estadoMaquina">
            <div class="card card-body">
                <button type="button" class="btn col-sm-6 col-md-3 btn-primary m-2" onclick="estadoDeMaquina('ENCENDIDA')">ENCENDIDA</button>
                <button type="button" class="btn col-sm-6 col-md-3 btn-warning m-2 " onclick="estadoDeMaquina('ASEO')">ASEO</button>
                <button type="button" class="btn col-sm-6 col-md-3 btn-danger m-2" onclick="estadoDeMaquina('APAGADA')">APAGADA</button>
                <button type="button" class="btn col-sm-6 col-md-3  btn-secondary m-2" onclick="estadoDeMaquina('MANTENIMIENTO')">MANTENIMIENTO</button>
                <button type="button" class="btn col-sm-6 col-md-3 btn-dark m-2" onclick="estadoDeMaquina('REPARACION')">REPARACIÓN</button>
            </div>
          </div>
          <div class="collapse" id="eventoMaquina">
            <div class="card card-body">
                <button type="button" class="btn col-sm-6 col-md-3 btn-primary m-2">EVENTO DE LA MAQUINA</button>
                <button type="button" class="btn col-sm-6 col-md-3 btn-warning m-2">EVENTO DE PRODUCCIÓN</button>
                <button type="button" class="btn col-sm-6 col-md-3 btn-danger m-2">EVENTO DE OPERARIO</button>
                <button type="button" class="btn col-sm-6 col-md-3 btn-secondary m-2">EVENTO ADMINISTRATIVO</button>
            </div>
          </div>
            <div class="text-primary">
                <h1 class="display-6">Ordenes de produccion pendientes</h1>
                <hr>
            </div>

            <!-- Tabla -->
            <div >
            <table id="listaOrdenes" class="table table-bordered table-striped dt-responsive" style="box-sizing: border-box" >
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
                    <td>{{ $proceso->orden_produccion->transformaciones->where('tipo_corte', 'INICIAL')->load('cubicaje')->min('cubicaje.bloque') }}</td>
                    <td>{{ $proceso->orden_produccion->transformaciones->where('tipo_corte', 'INICIAL')->load('cubicaje')->max('cubicaje.bloque') }}</td>
                    <th>{{ $proceso->observacion }}</th>
                    <td>{{ $proceso->entrada }}</td>
                    <td>{{ $proceso->salida }}</td>
                    <td>{{ $proceso->estado }}</td>
                    <td>
                        <div class="d-flex align-items-center ">
                            <a href="{{ route('trabajo-maquina.show',$proceso) }}" class="btn btn-sm btn-primary">
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
        
    </div>
   
    <hr>

</div>

@endsection

@section('js')
<script src="/js/modulos/maquina_ordenes.js"></script>
@endsection
