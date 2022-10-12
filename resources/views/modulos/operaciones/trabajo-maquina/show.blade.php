@extends('layouts.web')
@section('title', ' Procesos | Inducolma')

@section('submenu')
@include('modulos.sidebars.costos-side')
@endsection
@section('content')
<div class="div container h-content ">
    <div class="row">
        <div class="col-12 col-sm-10 col-lg-6 mx-auto">
            <h1 class="display-6">Ordenes de produccion pendientes</h1>
            <hr>
        </div>

        <!-- Tabla -->

        <table id="listaTipoMadera" class="table table-bordered table-striped dt-responsive">
            <thead>
                <tr>
                    <th>Id</th>
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
                    <td>{{ $proceso->observacion }}</td>
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
    <hr>

</div>

@endsection

@section('js')
<script src="/js/modulos/procesos.js"></script>
@endsection
