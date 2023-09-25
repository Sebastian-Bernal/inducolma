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
                            <th>Proveedor</th>
                            <th>Calificacion Total</th>
                            <th>Longitud de la madera</th>
                            <th>Cantonera</th>
                            <th>Hongos</th>
                            <th>Rajadura</th>
                            <th>Bichos</th>
                            <th>Organizacion</th>
                            <th>Area transversal maxima y minima</th>
                            <th>Areas no convenientes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $calificacion)
                            <tr>
                                <td>{{ $calificacion->paqueta }}</td>
                                <td>{{ $calificacion->razon_social }}</td>
                                <td>{{ $calificacion->total }}</td>
                                <td>{{ $calificacion->longitud_madera }}</td>
                                <td>{{ $calificacion->cantonera }}</td>
                                <td>{{ $calificacion->hongos }}</td>
                                <td>{{ $calificacion->rajadura }}</td>
                                <td>{{ $calificacion->bichos }}</td>
                                <td>{{ $calificacion->organizacion }}</td>
                                <td>{{ $calificacion->areas_transversal_max_min }}</td>
                                <td>{{ $calificacion->areas_no_conveniente }}</td>

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
                    <input type="text" readonly name="filtroCubiaje2" id="filtroCubiaje2" value="{{ $proveedor }}">
                    <input type="text" readonly name="cubicajeDesde" value="{{ $desde }}">
                    <input type="text" readonly name="cubicajeHasta" value="{{ $hasta }}">
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


