{{ $encabezado }}
<table >
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Cedula</th>
            <th>Fecha ingreso</th>
            <th>Horas salida</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $tercero)
            <tr>
                <td>{{ $tercero->nombre }}</td>
                <td>{{ $tercero->cc }}</td>
                <td>{{ $tercero->created_at }}</td>
                <td>{{ $tercero->deleted_at }}</td>

            </tr>
        @endforeach
    </tbody>

</table>
