<table >
    <thead>
        <tr>
            <th>Paqueta</th>
            <th>Bloque</th>
            <th>Alto</th>
            <th>Largo</th>
            <th>Ancho</th>
            <th>Fecha de creacion</th>
            <th>Tipo de madera</th>
            <th>Transformacion final</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $entrada)
            <tr>
                <td>{{ $entrada->paqueta }}</td>
                <td>{{ $entrada->bloque }}</td>
                <td>{{ $entrada->alto }}</td>
                <td>{{ $entrada->largo }}</td>
                <td>{{ $entrada->ancho }}</td>
                <td>{{ $entrada->created_at }}</td>
                <td>{{ $entrada->tipo_madera }}</td>
                <td>{{ $entrada->trnasformacion_final }}</td>

            </tr>
        @endforeach
    </tbody>

</table>
