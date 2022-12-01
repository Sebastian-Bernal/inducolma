<table>
    <thead>
        <tr>
            <th>Entrada madera</th>
            <th>Paqueta</th>
            <th>Bloque</th>
            <th>alto</th>
            <th>largo</th>
            <th>ancho</th>
            <th>fecha creacion</th>
            <th>tipo madera</th>
            <th>transformaciones</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($data as $tr)
            <tr>
                <td>{{ $tr->entrada_madera_id }}</td>
                <td>{{ $tr->paqueta }}</td>
                <td>{{ $tr->bloque }}</td>
                <td>{{ $tr->alto }}</td>
                <td>{{ $tr->largo }}</td>
                <td>{{ $tr->ancho }}</td>
                <td>{{ $tr->fecha_creacion }}</td>
                <td>{{ $tr->tipo_madera }}</td>
                @foreach ($tr->transformaciones as $item)
                    <p>
                        {{ $item->item }}
                        {{ $item->cantidad }}
                        {{ $item->orden_produccion_id }}
                        {{ $item->pedido_id }}
                        {{ $item->cliente }}
                    </p>
                    <hr>
                @endforeach
            </tr>
        @endforeach

    </tbody>


</table>
