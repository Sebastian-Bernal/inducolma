<table >
    <thead>
        <tr>
            <th>Paqueta</th>
            <th>Bloque</th>
            <th>Alto</th>
            <th>Largo</th>
            <th>Ancho</th>
            <th>Centrimetros cubicos</th>
            <th>Fecha de creacion</th>
            <th>Pulgadas cuadradas</th>
            <th>Tipo de madera</th>
            <th>Viaje</th>
            <th>Proveedor</th>
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
                <td>{{ $entrada->cm3 }}</td>
                <td>{{ $entrada->created_at }}</td>
                <td>{{ $entrada->pulgadas_cuadradas }}</td>
                <td>{{ $entrada->descripcion }}</td>
                <td>{{ $entrada->entrada_madera_id }}</td>
                <td>{{ $entrada->razon_social }}</td>

            </tr>
        @endforeach
    </tbody>

</table>
