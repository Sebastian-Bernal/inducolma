@extends('layouts.web')
@section('title', ' Trabajo ensamble | Inducolma')

@section('submenu')
@include('modulos.sidebars.costos-side')
@endsection
@section('content')
<div class="div container h-content m-auto">
    <div class="d-flex flex-wrap row  m-auto align-items-center container-fluid ">
        <div
            class="container col-xl-12 col-lg-12 col-md-12 col-sm-12 bg-light shadow rounded-3 min-vh-100 mb-2 mt-2 ">

            <div class="text-primary">
                <h1 class="display-6">Entradas de madera en condicion troza</h1>
                <hr>
            </div>


            <!-- Tabla -->
            <div>
                <table id="listaEntradas" class="table table-bordered table-striped dt-responsive"
                    style="box-sizing: border-box">
                    <thead>
                        <tr>
                            <th># entrada</th>
                            <th>proveedor</th>
                            <th>fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($entradas as $entrada)
                        <tr>
                            <td>{{ $entrada->entrada_madera_id }}</td>
                            <td>{{ $entrada->proveedor->razon_social }}</td>
                            <td>{{ $entrada->fecha }}</td>
                            <td>
                                <div class="d-flex align-items-center ">
                                    <a href="{{ route('trabajo-troza',$entrada->entrada_madera_id) }}"
                                        class="btn btn-sm btn-primary"
                                        title="Transformar trozas de la entrada:  {{ $entrada->entrada_madera_id }}">
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
</div>

@endsection

@section('js')
<script src="/js/modulos/alertas-swift.js"></script>
<script src="/js/modulos/ensamble.js"></script>
@endsection
