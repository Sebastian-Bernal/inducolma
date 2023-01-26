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
                            <th>Id proceso</th>
                            <th>Fecha ejecucion</th>
                            <th>hora inicio</th>
                            <th>hora fin</th>
                            <th>Cantidad producida</th>
                            <th>Costo</th>
                            <th>Salida</th>
                            <th>Item</th>
                            <th>usuario</th>
                            <th>Viaje</th>
                            <th>Paqueta</th>
                            <th>Cm3 salida</th>
                            <th>Costo Cm3</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $proceso)
                            <tr>
                                <td>{{ $proceso->id }}</td>
                                <td>{{ $proceso->fecha_ejecucion }}</td>
                                <td>{{ $proceso->hora_inicio }}</td>
                                <td>{{ $proceso->hora_fin }}</td>
                                <td>{{ $proceso->sub_paqueta }}</td>
                                <td>{{ $proceso->costo }}</td>
                                <td>{{ $procesos->salida }}</td>
                                <td>{{ $proceso->descripcion}}</td>
                                <td>{{ $proceso->name }}</td>
                                <td>{{ $proceso->entrada_madera_id }}</td>
                                <td>{{ $proceso->paqueta }}</td>
                                <td>{{ $proceso->cm3_salida }}</td>
                                <td>{{ $proceso->costo_cm3 }}</td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>
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
<script src="/js/modulos/reportes/produccion/reportes-construccion.js"></script>

@endsection
