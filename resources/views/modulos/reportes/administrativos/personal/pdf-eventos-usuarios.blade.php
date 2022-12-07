{{ $encabezado }}

<table class="table table-striped table-bordered align-middle mt-5" >
    <thead>
        <tr>
            <th>Nombre empleado</th>
            <th>Descripcion</th>
            <th>Observacion</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $evento)
            <tr>
                <td>{{ $evento->name }}</td>
                <td>{{ $evento->descripcion }}</td>
                <td>{{ $evento->observacion }}</td>
                <td>{{ $evento->fecha }}</td>
            </tr>
        @endforeach
    </tbody>

</table>
