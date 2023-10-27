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
                <p>
                    maquina_id : {{ $data->maquina_id }} <br>
                    valor_minuto_energia : {{ $data->valor_minuto_energia }} <br>
                    minutos : {{ $data->minutos }} <br>
                    suma_valor_dia : {{ $data->sum_valor_dia }} <br>
                    sum_cm3 : {{ $data->sum_cm3 }} <br>
                    numero_dias : {{ $data->numero_dias }} <br>
                </p>
                <h4>datos de Pedidos Procesos y subprocesos:</h4> <br>

                @forelse ($data->procesos_pedido as $pedido)
                    <p>
                        Pedido Id : {{ $pedido->pedido_id }} <br>
                        Clinete : {{ $pedido->cliente }} <br>
                        Producto : {{ $pedido->producto }} <br>
                        Cantidad : {{ $pedido->cantidad }} <br>

                        <p>
                            <table class="table table-striped table-bordered align-middle mb-0 caption-top" >
                                <caption>Procesos y subprocesos:</caption>
                                <head>
                                    <th>Pedido No.</th>
                                    <th>Fecha y hora de inicio</th>
                                    <th>Fecha y hora de fin</th>
                                    <th>tarjeta entrada</th>
                                    <th>tarjeta salida</th>
                                    <th>subpaqueta</th>
                                    <th>alto</th>
                                    <th>ancho</th>
                                    <th>largo</th>
                                    <th>sobrante</th>
                                    <th>lena</th>
                                    <th>cm3 procesados</th>
                                    <th>consumo energia</th>
                                </head>
                            @forelse ($pedido->procesos as $proceso)
                                <tbody>
                                    <tr>
                                        <td rowspan="{{ count($proceso->subprocesos) }}">{{ $proceso->pedido_id }}</td>
                                        <td rowspan="{{ count($proceso->subprocesos) }}">{{ $proceso->fecha_ejecucion .' '.$proceso->hora_inicio  }}</td>
                                        <td rowspan="{{ count($proceso->subprocesos) }}">{{ $proceso->fecha_fin .' '.$proceso->hora_fin  }}</td>


                                    @forelse ($proceso->subprocesos as $subproceso)

                                            <td>{{ $subproceso->tarjeta_entrada }}</td>
                                            <td>{{ $subproceso->tarjeta_salida }}</td>
                                            <td>{{ $subproceso->subpaqueta }}</td>
                                            <td>{{ $subproceso->alto }}</td>
                                            <td>{{ $subproceso->ancho }}</td>
                                            <td>{{ $subproceso->largo }}</td>
                                            <td>{{ $subproceso->sobrante }}</td>
                                            <td>{{ $subproceso->lena }}</td>
                                            <td>{{ $subproceso->cm3_procesados }}</td>
                                            <td></td>
                                        </tr>
                                    @empty
                                        <span>No se encontraron subprocesos</span>
                                    @endforelse

                                    <tr class="table-info">
                                        <td colspan="9"> Total del proceso</td>
                                        <td>{{ $proceso->total_sobrante }}</td>
                                        <td>{{ $proceso->total_lena }}</td>
                                        <td>{{ $proceso->total_cm3 }}</td>
                                        <td>{{ $proceso->consumo_energia }}</td>
                                    </tr>
                                </tbody>
                                @empty
                                <span>No se encontraron procesos</span>
                            @endforelse
                            </table>
                        </p>

                        <table class="table table-success table-striped caption-top">
                            <caption>Totales en el pedido:</caption>
                            <head>
                                <th>Total cm3 en el pedido</th>
                                <th>Total sobrante en el pedido</th>
                                <th>Total leña en el pedido</th>
                                <th>Consumo de energia en el pedido</th>
                            </head>
                            <tbody>
                                <tr>
                                    <td>{{ $pedido->total_cm3_pedido }}</td>
                                    <td>{{ $pedido->total_sobrante_pedido }}</td>
                                    <td>{{ $pedido->total_lena_pedido }}</td>
                                    <td>{{ $pedido->consumo_energia }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </p>


                @empty
                    <span>no hay datos</span>
                @endforelse


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
                    <input type="text" name="pedido" id="pedido" value="{{ $pedidoId }}">
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
