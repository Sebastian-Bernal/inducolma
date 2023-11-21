<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=si">
    <!-- Bootstrap CSS -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <style>
        @page {
            margin-left: 1.5cm;
            margin-right: 2cm;
            padding-inline-end: 0%;
        }
        hr {
            margin:0;
            padding:0;
        }
        br {
            margin:0;
            padding:0;
        }
        p {
            margin:0;
            padding:0;
        }
    </style>
<body>  
    <div class="div container-fluid col-12 h-content m-auto ">
        <div class="row">
            <table>
                <tbody>
                    <td class="col-4">
                        <p class="text-left"><img src="{{ public_path('img/logo.png') }}" style="width:80%"></p>
                    </td>
                    <td class="col-7">
                        <h5 class="text-center" style="color:#649f21;"><strong>{{ $encabezado }}</strong></h5>
                    
                    </td>
                </tbody>
    
                </table>
    @forelse ($data->procesos_pedido as $pedido)
                  
    <div  class="container-fluid px-2 py-2 " >

       

                        Clinete : {{ $pedido->cliente }} <br>
                        Producto : {{ $pedido->producto }} <br>
                        Cantidad : {{ $pedido->cantidad }} <br>

                        <p>
                      
                            <table class="table table-striped table-bordered align-middle mb-0 caption-top m-auto" >
                                <caption>Procesos y subprocesos:</caption>
                                <thead>
                                    <tr class="text-white" style="background-color: #fb8c00;">
                                        <th class="pl-2 border border-secondary" style="font-size:10px;">Pedido No.</th>
                                        <th class="pl-2 border border-secondary" style="font-size:10px;">Fecha y hora de inicio</th>
                                        <th class="pl-2 border border-secondary" style="font-size:10px;">Fecha y hora de fin</th>
                                        <th class="pl-2 border border-secondary" style="font-size:10px;">tarjeta entrada</th>
                                        <th class="pl-2 border border-secondary" style="font-size:10px;">tarjeta salida</th>
                                        <th class="pl-2 border border-secondary" style="font-size:10px;">subpaqueta</th>
                                        <th class="pl-2 border border-secondary" style="font-size:10px;">alto</th>
                                        <th class="pl-2 border border-secondary" style="font-size:10px;">ancho</th>
                                        <th class="pl-2 border border-secondary" style="font-size:10px;">largo</th>
                                        <th class="pl-2 border border-secondary" style="font-size:10px;">sobrante</th>
                                        <th class="pl-2 border border-secondary" style="font-size:10px;">leña</th>
                                        <th class="pl-2 border border-secondary" style="font-size:10px;">cm3 procesados</th>
                                        <th class="pl-2 border border-secondary" style="font-size:10px;">consumo energia</th>

                                    </tr>
                                    
                                </thead>
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
                    </div>


                @empty
                    <span>no hay datos</span>
                @endforelse
        </div>
    </div>
</body>

</html>