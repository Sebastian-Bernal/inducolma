<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"  integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> --}}


    <h5 class="h5">{{ $encabezado }}</h5>
    <body>
        <table class="table table-striped table-bordered align-middle mt-5" >
            <thead>
                <tr class="table-primary">
                    <th>Proveedor</th>
                    <th>Entidad vigilante</th>
                    <th>mes</th>
                    <th>ano</th>
                    <th>fecha</th>
                    <th>Acto administrativo</th>
                    <th>Salvoconducto remision</th>
                    <th>Titular del salvoconducto remision</th>
                    <th>procedencia de la madera</th>
                    <th>maderas</th>
                </tr>
            </thead>
            @foreach ($data as $madera)
            <tr>
                <td >{{ $madera->proveedor }}</td>
                <td>{{ $madera->entidad_vigilante }}</td>
                <td>{{ $madera->mes }}</td>
                <td>{{ $madera->ano }}</td>
                <td>{{ $madera->fecha }}</td>
                <td>{{ $madera->acto_administrativo }}</td>
                <td>{{ $madera->salvoconducto_remision }}</td>
                <td>{{ $madera->titular_salvoconducto }}</td>
                <td>{{ $madera->procedencia_madera }}</td>
                <td>
                    @foreach ($madera->maderas as $item)
                        {{ "$item->nobre_comun - $item->nombre_cientifico - $item->m3entrada \n" }}
                        <hr>
                    @endforeach
                </td>
            </tr>
            @endforeach

        </table>
    </body>
</html>
