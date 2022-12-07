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
                            <th>Nombre</th>
                            <th>Cedula</th>
                            <th>Fecha ingreso</th>
                            <th>Horas salida</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $tercero)
                            <tr>
                                <td>{{ $tercero->nombre }}</td>
                                <td>{{ $tercero->cc }}</td>
                                <td>{{ $tercero->created_at }}</td>
                                <td>{{ $tercero->deleted_at }}</td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
            <form action="{{ route('reporte-personal') }}" method="GET" target="_blank"
                    rel="noopener noreferrer"
                    id="formGenerarReportePersonal">
                <div hidden>
                    <input type="text" readonly name="tipoReportePersonal" id="tipoReportePersonal" value="{{ $tipoReporte }}">

                    <input type="text" name="personalDesde" id="personalDesde" value="{{ $desde }}">
                    <input type="text" name="personalHasta" id="personalHasta" value="{{ $hasta }}">
                    <input type="text" name="filtroPersonal" id="filtroPersonal" value="{{ $especifico }}">
                    <input type="text" readonly name="generar" id="generar" value="">
                </div>
                <div class="d-flex justify-content-end mb-5 mt-2" >
                    <a href="{{ route('reportes-administrativos') }}" class=" btn btn-secondary">volver</a>
                    <button type="button" class="btn mx-1 btn-outline-danger" onclick="generarReportePersonal('1')"><i class="fa-regular fa-file-pdf"></i> PDF</button>
                    <button type="button" class="btn mx-1 btn-outline-success" onclick="generarReportePersonal('2')"><i class="fa-regular fa-file-excel"></i> EXCEL</button>
                    <button type="button" class="btn mx-1 btn-outline-success" onclick="generarReportePersonal('3')"><i class="fa-solid fa-file-csv"></i> CSV</button>

                </div>
            </form>
        </div>
    </div>

@endsection

@section('js')
<script src="/js/modulos/alertas-swift.js"></script>
<script src="/js/modulos/reportes/administrativos/reportes-personal.js"></script>

@endsection
