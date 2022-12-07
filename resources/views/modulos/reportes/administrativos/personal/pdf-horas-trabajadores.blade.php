{{ $encabezado }}
<table class="table table-striped table-bordered align-middle mt-5" >
    <thead>
        <tr>
            <th>Nombre empleado</th>
            <th>Hora entrada</th>
            <th>Hora salida</th>
            <th>Horas laboradas</th>
            <th>Maquina</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $empleado)
            <tr>
                <td>{{ $empleado->name }}</td>
                <td>{{ $empleado->entrada }}</td>
                <td>{{ $empleado->salida }}</td>
                <td>{{ $empleado->horas }}</td>
                <td>{{ $empleado->maquina }}</td>
                <td>{{ $empleado->fecha }}</td>
            </tr>
        @endforeach
    </tbody>

</table>
