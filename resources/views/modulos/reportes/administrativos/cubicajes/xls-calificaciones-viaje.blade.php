<thead>
    <tr>
        <th>Paqueta</th>
        <th>Proveedor</th>
        <th>Calificacion Total</th>
        <th>Longitud de la madera</th>
        <th>Cantonera</th>
        <th>Hongos</th>
        <th>Rajadura</th>
        <th>Bichos</th>
        <th>Organizacion</th>
        <th>Area transversal maxima y minima</th>
        <th>Areas no convenientes</th>
    </tr>
</thead>
<tbody>
    @foreach ($data as $calificacion)
        <tr>
            <td>{{ $calificacion->paqueta }}</td>
            <td>{{ $calificacion->nombre }}</td>
            <td>{{ $calificacion->total }}</td>
            <td>{{ $calificacion->longitud_madera }}</td>
            <td>{{ $calificacion->cantonera }}</td>
            <td>{{ $calificacion->hongos }}</td>
            <td>{{ $calificacion->rajadura }}</td>
            <td>{{ $calificacion->bichos }}</td>
            <td>{{ $calificacion->organizacion }}</td>
            <td>{{ $calificacion->areas_transversal_max_min }}</td>
            <td>{{ $calificacion->areas_no_conveniente }}</td>

        </tr>
    @endforeach
</tbody>
