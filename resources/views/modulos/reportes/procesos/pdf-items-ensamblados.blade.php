{{ $encabezado }}
<table class="table table-striped table-bordered align-middle mt-5" >
    <thead>
        <tr>
            <th>Producto</th>
            <th>Item</th>
            <th>Cantidad</th>

        </tr>
    </thead>
    <tbody>
        @foreach ($data as $producto)
            <tr>
                <td>{{ $producto->producto }}</td>
                <td>{{ $producto->item }}</td>
                <td>{{ $producto->cantidad }}</td>

            </tr>
        @endforeach
    </tbody>

</table>
