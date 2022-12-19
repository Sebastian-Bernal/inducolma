{{ $encabezado }}

<table class="table table-striped table-bordered align-middle mt-5" >
    <thead>
        <tr>
            <th>Estado</th>
            <th>Usuario que registro el estado</th>
            <th>Fecha de creacion del estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $estado)
            <tr>
                <td>{{ $estado->descripcion }}</td>
                <td>{{ $estado->name }}</td>
                <td>{{ str_replace("T", " -- ", Str::substr($estado->created_at , 0, -8)) }}</td>
            </tr>
        @endforeach
    </tbody>

</table>
