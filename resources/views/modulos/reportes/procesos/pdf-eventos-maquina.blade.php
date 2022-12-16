<table class="table table-striped table-bordered align-middle mt-5" >
    <thead>
        <tr>
            <th>Evento</th>
            <th>Usuario que registro el evento</th>
            <th>Fecha de creacion del evento</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $proceso)
            <tr>
                <td>{{ $proceso->descripcion }}</td>
                <td>{{ $proceso->name }}</td>
                <td>{{ str_replace("T", " -- ", Str::substr($proceso->created_at , 0, -8)) }}</td>
            </tr>
        @endforeach
    </tbody>

</table>
