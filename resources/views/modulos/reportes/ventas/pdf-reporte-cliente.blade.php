{{ $encabezado }}

<table>
    <thead>
        <tr>
            <th>Numero de pedido</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Fecha de entrega</th>
            <th>Realizado por</th>

        </tr>
    </thead>
    <tbody>
        @foreach ($data as $pedido)
            <tr>
                <td>{{ $pedido->id }}</td>
                <td>{{ $pedido->descripcion }}</td>
                <td>{{ $pedido->cantidad }}</td>
                <td>{{ $pedido->fecha_entrega }}</td>
                <td>{{ $pedido->name }}</td>

            </tr>
        @endforeach
    </tbody>

</table>
