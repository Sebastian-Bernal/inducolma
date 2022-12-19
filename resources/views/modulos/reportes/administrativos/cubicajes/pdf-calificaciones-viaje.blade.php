<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"  integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> --}}
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
        th {
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
        .giro{
            transform: rotate(-90deg);
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
                               {{--  <p class="text-center"> Tipo de madera: {{ $data[0]->descripcion }}</p>
                               <p class="text-center">Proveedor: {{ $data[0]->nombre }}</p>  --}}
                            </td>
                        </tbody>
                        
                       </table>
                            
                            
                            
                        
                        
                
                <div class="mx-auto">
                    <table class="table table-striped table-bordered align-middle container-fluid" style="page-break-after:auto" >
                        <thead>
                            <tr class="text-white" style="background-color: #fb8c00;">
                                <th class="text-center" style="font-size: 8px;">Paqueta</th>
                                <th class="text-center" style="font-size: 8px;">Proveedor</th>
                                <th class="text-center" style="font-size: 8px;"><p>Calif.</p><p>total</p></th>
                                <th class="text-center" style="font-size: 8px;"><p >Longitud</p><p>de la</p><p>madera</p></th>
                                <th class="text-center"style="font-size: 8px;"><p>Canto-</p><p>nera</p></th>
                                <th class="text-center" style="font-size: 8px;">Hongos</th>
                                <th class="text-center" style="font-size: 8px;"><p>Raja-</p><p>dura</p></th>
                                <th class="text-center" style="font-size: 8px;">Bic.</th>
                                <th class="text-center" style="font-size: 8px;">Org.</th>
                                <th class="text-center" style="font-size: 8px;">A. tran.</th>
                                <th style="font-size: 8px;">no conv.</th>
                                
                            
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $calificacion)
                            <tr>
                                <td style="font-size:10px;">{{ $calificacion->paqueta }}</td>
                                <td style="font-size:10px;">{{ $calificacion->nombre }}</td>
                                <td style="font-size:10px;">{{ $calificacion->total }}</td>
                                <td style="font-size:10px;">{{ $calificacion->longitud_madera }}</td>
                                <td style="font-size:10px;">{{ $calificacion->cantonera }}</td>
                                <td style="font-size:10px;">{{ $calificacion->hongos }}</td>
                                <td style="font-size:10px;">{{ $calificacion->rajadura }}</td>
                                <td style="font-size:10px;">{{ $calificacion->bichos }}</td>
                                <td style="font-size:10px;">{{ $calificacion->organizacion }}</td>
                                <td style="font-size:10px;">{{ $calificacion->areas_transversal_max_min }}</td>
                                <td style="font-size:10px;">{{ $calificacion->areas_no_conveniente }}</td>
                
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
            
            </div>
        </div>
        <script type="text/php">
            if (isset($pdf)) {
                $pdf->page_script('
                    $font = $fontMetrics->get_font("Arial, Helvetica,sans-serif", "normal");
                    $pdf->text(270, 810, "Pág $PAGE_NUM", $font, 8);
                ');
            }
        </script>
    </body>
