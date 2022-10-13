@extends('layouts.web')
@section('title', ' Procesos | Inducolma')

@section('submenu')
@include('modulos.sidebars.costos-side')
@endsection
@section('content')
<div class="div container h-content ">
    <div class="d-flex flex-wrap row  align-items-start container-fluid ">
       <div class="container col-xl-9 col-lg-9 col-md-9 col-sm-12 bg-light shadow rounded-3  min-vh-100 mb-2 mt-2 overflow-scroll"> 
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
        </div>
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 card  bg-secondary shadow m-3 rounded-3 min-vh-100 m-auto mt-2 mb-2">
        
            <div class="card-body">
              <h5 class="card-title">ESTADO DE LA MAQUINA</h5>
              <p class="card-text"></p>
              <a href="#" class="btn btn-primary">Go somewhere</a>
            </div>
        
    </div>
    </div>
   
    <hr>

</div>

@endsection

@section('js')
<script src="/js/modulos/maquina_ordenes.js"></script>
@endsection
