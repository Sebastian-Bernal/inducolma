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
                            <th>Producto</th>
                            <th>Item</th>
                            <th>Cantidad</th>
                            <th>Fecha y hora de inicio</th>
                            <th>Fecha y hora de fin </th>
                            <th>Usuario que realizo el registro</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $producto)
                            <tr>
                                <td>{{ $producto->producto }}</td>
                                <td>{{ $producto->item }}</td>
                                <td>{{ $producto->cantidad }}</td>
                                <td>{{ $producto->created_at }}</td>
                                <td>{{ $producto->updated_at }}</td>
                                <td>{{ $producto->name }}</td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
            <form action="{{ route('reporte-proceso') }}" method="GET" target="_blank"
                    rel="noopener noreferrer"
                    id="formReporteProceso">
                <div hidden>
                    <input type="text" readonly name="tipoReporteConstruccion" id="tipoReporteConstruccion" value="{{ $tipoReporte }}">

                    <input type="text" name="procesoDesde" id="procesoDesde" value="{{ $desde }}">
                    <input type="text" name="procesoHasta" id="procesoHasta" value="{{ $hasta }}">
                    <input type="text" name="maquina" id="maquina" value="{{ $maquina }}">
                    <input type="text" name="item" id="item" value="{{ $item }}">
                    <input type="text" readonly name="generar" id="generar" value="">
                </div>
                <div class="d-flex justify-content-end mb-5 mt-2" >
                    <a href="{{ route('reportes-procesos') }}" class=" btn btn-secondary">volver</a>
                    <button type="button" class="btn mx-1 btn-outline-danger" onclick="generarReporteProceso('1')"><i class="fa-regular fa-file-pdf"></i> PDF</button>
                    <button type="button" class="btn mx-1 btn-outline-success" onclick="generarReporteProceso('2')"><i class="fa-regular fa-file-excel"></i> EXCEL</button>
                    <button type="button" class="btn mx-1 btn-outline-success" onclick="generarReporteProceso('3')"><i class="fa-solid fa-file-csv"></i> CSV</button>

                </div>
            </form>
        </div>
    </div>

@endsection

@section('js')
<script src="/js/modulos/alertas-swift.js"></script>
<script src="/js/modulos/reportes/produccion/reportes-construccion.js"></script>

@endsection
