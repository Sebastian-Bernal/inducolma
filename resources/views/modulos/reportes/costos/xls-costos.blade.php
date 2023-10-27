


@foreach ($data->procesos_pedido as $pedido)
        <table>
            <thead>
                <tr>
                    <th style="border: 1px solid black; background-color: #f2f2f2; font-weight: bold;">Pedido No.</th>
                    <th style="border: 1px solid black; background-color: #f2f2f2; font-weight: bold;">Fecha y hora de inicio</th>
                    <th style="border: 1px solid black; background-color: #f2f2f2; font-weight: bold;">Fecha y hora de fin</th>
                    <th style="border: 1px solid black; background-color: #f2f2f2; font-weight: bold;">tarjeta entrada</th>
                    <th style="border: 1px solid black; background-color: #f2f2f2; font-weight: bold;">tarjeta salida</th>
                    <th style="border: 1px solid black; background-color: #f2f2f2; font-weight: bold;">subpaqueta</th>
                    <th style="border: 1px solid black; background-color: #f2f2f2; font-weight: bold;">alto</th>
                    <th style="border: 1px solid black; background-color: #f2f2f2; font-weight: bold;">ancho</th>
                    <th style="border: 1px solid black; background-color: #f2f2f2; font-weight: bold;">largo</th>
                    <th style="border: 1px solid black; background-color: #f2f2f2; font-weight: bold;">sobrante</th>
                    <th style="border: 1px solid black; background-color: #f2f2f2; font-weight: bold;">lena</th>
                    <th style="border: 1px solid black; background-color: #f2f2f2; font-weight: bold;">cm3 procesados</th>
                    <th style="border: 1px solid black; background-color: #f2f2f2; font-weight: bold;">consumo energia</th>
                </tr>
            </thead>

            @foreach ($pedido->procesos as $proceso)
                <tbody style="border: 1px solid black;">
                    <tr>
                        <td rowspan=" {{ count($proceso->subprocesos) }} "> {{ $proceso->pedido_id  }}</td>
                        <td rowspan=" {{ count($proceso->subprocesos) }} "> {{ $proceso->fecha_ejecucion. ' '.$proceso->hora_inicio }}  </td>
                        <td rowspan=" {{ count($proceso->subprocesos) }} "> {{ $proceso->fecha_fin.' '.$proceso->hora_fin }}  </td>

                    @foreach ($proceso->subprocesos as $subproceso)
                        @if (!$loop->first) {
                            <tr>
                        }
                        @endif
                            <td> {{ $subproceso->tarjeta_entrada }} </td>
                            <td> {{$subproceso->tarjeta_salida}} </td>
                            <td> {{ $subproceso->subpaqueta }} </td>
                            <td> {{ $subproceso->alto  }}</td>
                            <td> {{ $subproceso->ancho }} </td>
                            <td> {{ $subproceso->largo }} </td>
                            <td> {{ $subproceso->sobrante }} </td>
                            <td> {{ $subproceso->lena }} </td>
                            <td> {{ $subproceso->cm3_procesados }} </td>
                            <td>{{ ' ' }}</td>
                        </tr>
                    @endforeach

                    <tr>
                        <td colspan="9" style="border: 1px solid black; background-color: #5cb5cb; font-weight: bold;"> Total del proceso</td>
                        <td style="border: 1px solid black; background-color: #5cb5cb; font-weight: bold;"> {{ $proceso->total_sobrante }} </td>
                        <td style="border: 1px solid black; background-color: #5cb5cb; font-weight: bold;"> {{ $proceso->total_lena }} </td>
                        <td style="border: 1px solid black; background-color: #5cb5cb; font-weight: bold;"> {{ $proceso->total_cm3 }}</td>
                        <td style="border: 1px solid black; background-color: #5cb5cb; font-weight: bold;"> {{ $proceso->consumo_energia }} </td>
                    </tr>
                </tbody>
                @endforeach

        </table>
        <table>
            <thead>
                <tr>
                    <th style="border: 1px solid black; background-color: #71c085; font-weight: bold;">Total cm3 en el pedido</th>
                    <th style="border: 1px solid black; background-color: #71c085; font-weight: bold;">Total sobrante en el pedido</th>
                    <th style="border: 1px solid black; background-color: #71c085; font-weight: bold;">Total leña en el pedido</th>
                    <th style="border: 1px solid black; background-color: #71c085; font-weight: bold;">Consumo de energia en el pedido</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: 1px solid black; background-color: #71c085;"> {{ $pedido->total_cm3_pedido }} </td>
                    <td style="border: 1px solid black; background-color: #71c085;"> {{ $pedido->total_sobrante_pedido }} </td>
                    <td style="border: 1px solid black; background-color: #71c085;"> {{ $pedido->total_lena_pedido }} </td>
                    <td style="border: 1px solid black; background-color: #71c085;"> {{ $pedido->consumo_energia }} </td>
                </tr>
            </tbody>
        </table>
@endforeach

