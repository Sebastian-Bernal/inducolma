<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"  integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> --}}

    <body>
        <p class="px-2 py-2 text-center rounded mx-auto col-12" style=" background-color:#fb8c00; color: white;">
            <strong>{{ $encabezado }}</strong>

        </p>
        <div  class="container-fluid px-2 py-2 " >

            <div>
                @foreach ($data as $madera)

                <div class="row col-12 m-auto mt-1 mb-2 rounded p-1 border border-ligth" style="page-break-inside:avoid;" >
                    <div class="mt-1 mx-auto" style="background-color:#649f21;">
                        <p class="col-12 text-white m-1 mx-auto" style="font-size: 10px;"> Viaje No. {{ $madera->entrada_madera_id }}
                            &nbsp;&nbsp;  - &nbsp;Entidad Vigilante:  {{ $madera->entidad_vigilante }}
                            &nbsp;&nbsp; &nbsp;-  &nbsp;&nbsp; &nbsp;Mes: {{ $madera->mes }}
                            &nbsp;&nbsp; &nbsp;- &nbsp;&nbsp; &nbsp; Año: {{ $madera->ano }}
                            &nbsp;&nbsp; &nbsp; - &nbsp;&nbsp; &nbsp;Fecha de Ingreso:  {{ $madera->fecha }}
                        </p>
                    </div>
                    <div class="col-12 mt-3 mx-auto">
                        <label class="col-4 px-1 py-1" style="color:#649f21; font-size:10px;">PROVEEDOR:</label>
                        <label class="col-4 px-1 py-1" style="color:#649f21; font-size:10px;">ACTO-ADMON:</label>
                        <label class="col-3 px-1 py-1" style="color:#649f21; font-size:10px;">SALVOCNDUCTO:</label>
                    </div>
                    <div class="col-12 mx-auto">
                        <label class="col-4 rounded  py-1 px-1" style="font-size:10px; background-color:#cccccc;">{{ $madera->razon_social }}</label>
                        <label class="col-4 rounded py-1 px-1" style="font-size:10px; background-color:#cccccc;">{{ $madera->acto_administrativo }}</label>
                        <label class="col-3 rounded py-1 px-1" style="font-size:10px; background-color:#cccccc;">{{ $madera->salvoconducto_remision }}</label>
                    </div>
                    <div class="col-12 mt-1">
                        <label class="col-5 px-1 py-1" style="color:#649f21; font-size:10px;">TITULAR SALVOCONDUCTO:</label>
                        <label class="col-6 px-1 py-1" style="color:#649f21; font-size:10px;">CIUDAD:</label>
                    </div>
                    <div class="col-12 mx-auto">
                        <label class="col-5 rounded  py-1 px-1" style="font-size:10px; background-color:#cccccc;">{{ $madera->titular_salvoconducto }}</label>
                        <label class="col-6 rounded py-1 px-1" style="font-size:10px; background-color:#cccccc;">{{ $madera->procedencia_madera }}</label>
                    </div>
                    <div class="col-10 mt-1 mx-auto">
                        <p style="background-color:#fb8c00; min-height:2px;"></p>
                        <p class="text-center" style="color:#fb8c00; min-height:2px;">Maderas en esta entrada</p>
                        <table class="border border-light mx-auto col-10">
                            <thead style="background-color:#bbbbbb">
                            <tr>

                                <th class="pl-2 border border-secondary" style="font-size:10px;">Nombre Común</th>
                                <th class="pl-2 border border-secondary" style="font-size:10px;">Nombre Cientifico</th>
                                <th class="pl-2 border border-secondary" style="font-size:10px;">Metros Cubicos</th>
                                <th class="pl-2 border border-secondary" style="font-size:10px;">Condic&oacute;n de la madera</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($madera->maderas as $item)

                                <td class="text-center border border-secondary" style="font-size:10px;">{{ "$item->nobre_comun" }}</td>
                                <td class="text-center border border-secondary" style="font-size:10px;">{{ "$item->nombre_cientifico" }}</td>
                                <td class="text-center border border-secondary" style="font-size:10px;">{{ "$item->m3entrada" }}</td>
                                <td class="text-center border border-secondary" style="font-size:10px;">{{ "$item->condicion_madera" }}</td>
                                @endforeach

                            </tbody>

                        </table>
                    </div>



                </div>
                <br>
                @endforeach
            </div>

        </div>
    </body>
</html>
