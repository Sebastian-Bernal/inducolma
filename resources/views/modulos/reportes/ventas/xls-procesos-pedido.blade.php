<table>
    <thead>
        <tr>
            <th>Numero de pedido</th>
            <th>Producto a fabricar</th>
            <th>Numero de orden</th>
            <th>Estado de la orden</th>
            <th>Item a fabricar</th>
            <th>Numero del proceso</th>
            <th>Estado del proceso</th>
            <th>Maquina</th>

        </tr>
    </thead>
    <tbody>
        @foreach ($data as $pedido)
            <tr>
                <td>{{ $pedido->pedido_id }}</td>
                <td>{{ $pedido->producto }}</td>
                <td>{{ $pedido->orden_id }}</td>
                <td>{{ $pedido->estado_orden }}</td>
                <td>{{ $pedido->item }}</td>
                <td>{{ $pedido->id_proceso }}</td>
                <td>{{ $pedido->estado_proceso }}</td>
                <td>{{ $pedido->maquina }}</td>


            </tr>
        @endforeach
    </tbody>

</table>
