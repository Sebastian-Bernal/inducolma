
<table>
    <thead>
        <tr>
            <th>Id costo</th>
            <th>Fecha ejecucion</th>
            <th>hora inicio</th>
            <th>hora fin</th>
            <th>Cantidad producida</th>
            <th>Costo</th>
            <th>Salida</th>
            <th>Item</th>
            <th>usuario</th>
            <th>Viaje</th>
            <th>Paqueta</th>
            <th>Cm3 salida</th>
            <th>Costo Cm3</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($data as $costo)
            <tr>

                <td>{{ $costo->id }}</td>
                <td>{{ $costo->fecha_ejecucion }}</td>
                <td>{{ $costo->hora_inicio }}</td>
                <td>{{ $costo->hora_fin }}</td>
                <td>{{ $costo->sub_paqueta }}</td>
                <td>{{ $costo->costo }}</td>
                <td>{{ $costo->salida }}</td>
                <td>{{ $costo->descripcion}}</td>
                <td>{{ $costo->name }}</td>
                <td>{{ $costo->entrada_madera_id }}</td>
                <td>{{ $costo->paqueta }}</td>
                <td>{{ $costo->cm3_salida }}</td>
                <td>{{ $costo->costo_cm3 }}</td>

            </tr>
        @endforeach
    </tbody>

</table>
