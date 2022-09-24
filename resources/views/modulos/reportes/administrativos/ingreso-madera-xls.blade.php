
<table >
    <thead>
        <tr style="background-color: rgb(156, 177, 170)">
            <th>PROVEEDOR</th>
            <th>ENTIDAD VIGILANTE</th>
            <th>MES</th>
            <th>AÑO</th>
            <th>FECHA</th>
            <th>ACTO ADMINISTRATIVO</th>
            <th>SALVOCONDUCTO REMISION</th>
            <th>TITULAR DEL SALVOCONDUCTO</th>
            <th>PROCEDENCIA DE LA MADERA</th>
            <th>MADERAS</th>
        </tr>
    </thead>
    @foreach ($data as $madera)
    <tr>
        <td>{{ $madera->proveedor }}</td>
        <td>{{ $madera->entidad_vigilante }}</td>
        <td>{{ $madera->mes }}</td>
        <td>{{ $madera->ano }}</td>
        <td>{{ $madera->fecha }}</td>
        <td>{{ $madera->acto_administrativo }}</td>
        <td>{{ $madera->salvoconducto_remision }}</td>
        <td>{{ $madera->titular_salvoconducto }}</td>
        <td>{{ $madera->procedencia_madera }}</td>
        <td>
            <ol>
                @foreach ($madera->maderas as $item)

                    <li>{{ "- $item->nobre_comun - $item->nombre_cientifico - $item->m3entrada m3" }}</li>

                @endforeach
            </ol>

        </td>
    </tr>
    @endforeach

</table>
