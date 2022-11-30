@extends('layouts.web')
@section('title', ' Reportes | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content')

    <div class="div container h-content ">
        <div class="row">
            <h5>{{ $encabezado }}</h5>
            <div style=" height: 30rem; overflow-y: scroll;">
                <table class="table table-striped table-bordered align-middle mt-5" >
                    <thead>
                        <tr>
                            <th>Paqueta</th>
                            <th>Bloque</th>
                            <th>Alto</th>
                            <th>Largo</th>
                            <th>Ancho</th>
                            <th>Fecha de creacion</th>
                            <th>Pulgadas cuadradas</th>
                            <th>Tipo de madera</th>
                            <th>Viaje</th>
                            <th>Proveedor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $entrada)
                            <tr>
                                <td>{{ $entrada->paqueta }}</td>
                                <td>{{ $entrada->bloque }}</td>
                                <td>{{ $entrada->alto }}</td>
                                <td>{{ $entrada->largo }}</td>
                                <td>{{ $entrada->ancho }}</td>
                                <td>{{ $entrada->created_at }}</td>
                                <td>{{ $entrada->pulgadas_cuadradas }}</td>
                                <td>{{ $entrada->descripcion }}</td>
                                <td>{{ $entrada->entrada_madera_id }}</td>
                                <td>{{ $entrada->nombre }}</td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
            <form action="{{ route('reporte-cubicajes') }}" method="GET" target="_blank"
                    rel="noopener noreferrer"
                    id="formGenerarReporteCubicajes">
                <div hidden>
                    <input type="text" readonly name="tipoReporteCubicaje" id="tipoReporteCubicaje" value="{{ $tipoReporte }}">
                    <input type="text" readonly name="filtroCubiaje1" id="filtroCubiaje1" value="{{ $especifico }}">
                    <input type="text" readonly name="generar" id="generar" value="">
                </div>
                <div class="d-flex justify-content-end mb-5 mt-2" >
                    <a href="{{ route('reportes-administrativos') }}" class=" btn btn-secondary">volver</a>
                    <button type="button" class="btn mx-1 btn-outline-danger" onclick="generarReporteCubicajes('1')"><i class="fa-regular fa-file-pdf"></i> PDF</button>
                    <button type="button" class="btn mx-1 btn-outline-success" onclick="generarReporteCubicajes('2')"><i class="fa-regular fa-file-excel"></i> EXCEL</button>
                    <button type="button" class="btn mx-1 btn-outline-success" onclick="generarReporteCubicajes('3')"><i class="fa-solid fa-file-csv"></i> CSV</button>

                </div>
            </form>
        </div>
    </div>

@endsection

@section('js')
<script src="/js/modulos/alertas-swift.js"></script>
<script src="/js/modulos/reportes/administrativos/reportes-cubicajes.js"></script>

@endsection
