<table>
    <thead>
        <tr>
            <th>Producto</th>
            <th>Item</th>
            <th>Cantidad</th>
            <th>Fecha y hora de inicio</th>
            <th>Fecha y hora de fin </th>
            <th>Usuario que realizo el registro</th>

        </tr>
    </thead>
    <tbody>
        @foreach ($data as $producto)
            <tr>
                <td>{{ $producto->producto }}</td>
                <td>{{ $producto->item }}</td>
                <td>{{ $producto->cantidad }}</td>
                <td>{{ $producto->created_at }}</td>
                <td>{{ $producto->updated_at }}</td>
                <td>{{ $producto->name }}</td>

            </tr>
        @endforeach
    </tbody>

</table>
