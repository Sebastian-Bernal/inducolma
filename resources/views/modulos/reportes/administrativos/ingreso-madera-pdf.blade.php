<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @yield('css')
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <style>
            table {
                width: 100%;
                }
            th, td {
                width: 25%;
                text-align: left;
                border: 1px solid black
                }
        </style>
    </head>
    <body>
        <table >

            @foreach ($data as $madera)
            <tr>
                <td >{{ $madera['entrada_madera_id']/* . " " . $madera->mes */ }}</td>
            </tr>
            @endforeach

        </table>
    </body>
</html>
