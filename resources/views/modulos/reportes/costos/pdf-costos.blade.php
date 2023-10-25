{{ $encabezado }}
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
