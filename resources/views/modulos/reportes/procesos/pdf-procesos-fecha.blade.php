{{ $encabezado }}

<table>
    <thead>
        <tr>
            <th>Maquina</th>
            <th>Item</th>
            <th>Cantidad Items</th>
            <th>Subpaquetas</th>
            <th>Sobrante</th>
            <th>Lena</th>
            <th>Hora inicio</th>
            <th>Hora fin</th>
            <th>Trabajador</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $proceso)
            <tr>
                <td>{{ $proceso->maquina }}</td>
                <td>{{ $proceso->descripcion }}</td>
                <td>{{ $proceso->cantidad_items }}</td>
                <td>{{ $proceso->sub_paqueta }}</td>
                <td>{{ isset($proceso->sobrante) ? $proceso->sobrante : 0 }}</td>
                <td>{{ isset($proceso->lena) ? $proceso->lena : 0 }}</td>
                <td>{{ $proceso->hora_inicio}}</td>
                <td>{{ $proceso->hora_fin }}</td>
                <td>{{ $proceso->name }}</td>

            </tr>
        @endforeach
    </tbody>

</table>
