<!doctype html>
<html lang="en">

<head>
    <title>Laravel</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        table {
            font-size: 12px;
        }
    </style>
</head>
    <h5>{{ $encabezado }}</h5>
    <body>
        <table class="table table-bordered mt-5" >
            <thead>
                <tr>
                    <th>Proveedor</th>
                    <th>Entidad vigilante</th>
                    <th>mes</th>
                    <th>ano</th>
                    <th>fecha</th>
                    <th>Acto administrativo</th>
                    <th>Salvoconducto remision</th>
                    <th>Titular del salvoconducto_remision</th>
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
