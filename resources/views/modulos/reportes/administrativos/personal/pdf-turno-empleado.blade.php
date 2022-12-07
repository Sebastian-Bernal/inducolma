{{ $encabezado }}
<table class="table table-striped table-bordered align-middle mt-5" >
    <thead>
        <tr>
            <th>Nombre empleado</th>
            <th>Maquina</th>
            <th>Fecha</th>
            <th>Turno</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $turno)
            <tr>
                <td>{{ $turno->name }}</td>
                <td>{{ $turno->maquina }}</td>
                <td>{{ $turno->fecha }}</td>
                <td>{{ $turno->turno }}</td>

            </tr>
        @endforeach
    </tbody>

</table>
