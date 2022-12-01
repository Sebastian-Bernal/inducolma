
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
            <th>M3 ENTRADA</th>
            <th>NOMBRE CIENTIFICO</th>
            <th>TIPO DE MADERA</th>
        </tr>
    </thead>
    @foreach ($data as $madera)
    <tr>
        <td>{{ $madera->nombre }}</td>
        <td>{{ $madera->entidad_vigilante }}</td>
        <td>{{ $madera->mes }}</td>
        <td>{{ $madera->ano }}</td>
        <td>{{ $madera->fecha }}</td>
        <td>{{ $madera->acto_administrativo }}</td>
        <td>{{ $madera->salvoconducto_remision }}</td>
        <td>{{ $madera->titular_salvoconducto }}</td>
        <td>{{ $madera->procedencia_madera }}</td>
        <td>{{ $madera->m3entrada }}</td>
        <td>{{ $madera->nombre_cientifico }}</td>
        <td>{{ $madera->descripcion }}</td>
    </tr>
    @endforeach

</table>
