@extends('layouts.web')
@section('title', ' Entradas entrada | Inducolma')

@section('submenu')
@include('modulos.sidebars.costos-side')
@endsection
@section('content')
<div class="div container h-content ">
    <div class="row">
        <div class="col-12 col-sm-10 col-lg-6 mx-auto">


            <h1 class="display-6">Entradas de madera</h1>
            <hr>
            <!-- Button trigger modal -->
            @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                - {{ $error }} <br>
                @endforeach
            </div>

            @endif
        </div>
        <!-- Tabla -->

        <table id="listaEntradas" class="table table-bordered table-striped dt-responsive">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Viaje</th>
                    <th>Proveedor</th>
                    <th>Tipo de madera</th>
                    <th>Condición</th>
                    <th>m3 entrada</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($entradas as $entrada)
                <tr>
                    <td>{{ $entrada->id }}</td>
                    <td>{{ $entrada->entrada_madera_id }}</td>
                    <td>{{ $entrada->entrada_madera->proveedor->nombre }}</td>
                    <td>{{ $entrada->madera->tipo_madera->descripcion }}</td>
                    <td>{{ $entrada->condicion_madera }}</td>
                    <td>{{ $entrada->m3entrada }}</td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>

@endsection
@section('js')
<script src="/js/modulos/entrada-madera.js"></script>
@endsection
