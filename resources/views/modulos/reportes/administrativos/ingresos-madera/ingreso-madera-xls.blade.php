
<table >
    <thead>
        <tr style="background-color: rgb(156, 177, 170)">

            <th>CÓDIGO</th>
            <th>MES</th>
            <th>AÑO</th>
            <th>ACTO ADMINISTRATIVO No</th>
            <th>FECHA EXPEDICIÓN  SALVOCONDUCTO O REMISIÓN</th>
            <th>FECHA</th>
            <th>SALVOCONDUCTO O REMISIÓN No.</th>
            <th>TITULAR SACDCTO o REMISIÓN</th>
            <th>M3 SEGÚN DOCUMENTO</th>
            <th>PROCEDENCIA</th>
            <th>PROVEEDOR</th>
            <th>NOMBRE COMÚN</th>
            <th>NOMBRE TÉCNICO</th>
            <th>TIPO DE MADERA</th>
            <th>ENTIDAD  DE EXPEDICIÓN DCTO</th>
            <th>CONDICION DE LA MADERA</th>
        </tr>
    </thead>
    @foreach ($data as $madera)
    <tr>

        <td>{{ $madera->id }}</td>
        <td>{{ $madera->mes }}</td>
        <td>{{ $madera->ano }}</td>
        <td>{{ $madera->acto_administrativo }}</td>
        <td>{{ $madera->fecha }}</td>
        <td>{{ \Carbon\Carbon::parse($madera->created_at)->format('Y-m-d') }}</td>
        <td>{{ $madera->salvoconducto_remision }}</td>
        <td>{{ $madera->titular_salvoconducto }}</td>
        <td>{{ $madera->m3entrada }}</td>
        <td>{{ $madera->procedencia_madera }}</td>
        <td>{{ $madera->razon_social }}</td>
        <td>{{ $madera->nombre_comun }}</td>
        <td>{{ $madera->nombre_cientifico }}</td>
        <td>{{ $madera->densidad }}</td>
        <td>{{ $madera->entidad_vigilante }}</td>
        <td>{{ $madera->condicion_madera }}</td>
    </tr>
    @endforeach

</table>
