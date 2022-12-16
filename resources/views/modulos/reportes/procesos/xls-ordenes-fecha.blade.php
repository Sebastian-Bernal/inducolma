<table >
    <thead>
        <tr>
            <th># orden</th>
            <th>Cliente</th>
            <th>Producto</th>
            <th>Item</th>
            <th>Fecha de creacion</th>
            <th>Usuario</th>
            <th>Estado</th>

        </tr>
    </thead>
    <tbody>
        @foreach ($data as $orden)
            <tr>
                <td>{{ $orden->id }}</td>
                <td>{{ $orden->nombre }}</td>
                <td>{{ $orden->producto }}</td>
                <td>{{ $orden->item }}</td>
                <td>{{ $orden->created_at }}</td>
                <td>{{ $orden->name }}</td>
                <td>{{ $orden->estado }}</td>

            </tr>
        @endforeach
    </tbody>

</table>
