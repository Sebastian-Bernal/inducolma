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
                {!! print_r($data) !!}
            </div>
            <form action="{{ route('reporte-costos') }}" method="GET" target="_blank"
                    rel="noopener noreferrer"
                    id="formReporteProceso">
                <div hidden>
                    <input type="text" readonly name="tipoReporteCosotos" id="tipoReporteCosotos" value="{{ $tipoReporte }}">

                    <input type="text" name="costoDesde" id="costoDesde" value="{{ $desde }}">
                    <input type="text" name="costoHasta" id="costoHasta" value="{{ $hasta }}">
                    <input type="text" name="maquina" id="maquina" value="{{ $maquina }}">
                    <input type="text" name="usuario" id="usuario" value="{{ $usuario }}">
                    <input type="text" name="pedido" id="pedido" value="{{ $pedido }}">
                    <input type="text" name="item" id="item" value="{{ $item }}">
                    <input type="text" readonly name="generar" id="generar" value="">

                </div>
                <div class="d-flex justify-content-end mb-5 mt-2" >
                    <a href="{{ route('reportes-costos') }}" class=" btn btn-secondary">volver</a>
                    <button type="button" class="btn mx-1 btn-outline-danger" onclick="generarReporteCostos('1')"><i class="fa-regular fa-file-pdf"></i> PDF</button>
                    <button type="button" class="btn mx-1 btn-outline-success" onclick="generarReporteCostos('2')"><i class="fa-regular fa-file-excel"></i> EXCEL</button>
                    <button type="button" class="btn mx-1 btn-outline-success" onclick="generarReporteCostos('3')"><i class="fa-solid fa-file-csv"></i> CSV</button>

                </div>
            </form>
        </div>
    </div>

@endsection

@section('js')
<script src="/js/modulos/alertas-swift.js"></script>
<script src="/js/modulos/reportes/costos/reportes-costos.js"></script>

@endsection
