@extends('layouts.web')
@section('title', ' Reportes | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content')

    <div class="div container h-content ">
        <div class="row">
            <h5>{{ $encabezado }}</h5>
            <div style=" height: 30rem; overflow-y: scroll;">
                <table class="table table-striped table-bordered align-middle mt-5" >
                    <thead>
                        <tr>
                            <th>Proveedor</th>
                            <th>Entidad vigilante</th>
                            <th>mes</th>
                            <th>ano</th>
                            <th>fecha</th>
                            <th>Acto administrativo</th>
                            <th>Salvoconducto remision</th>
                            <th>Titular del salvoconducto_remision</th>
                            <th>procedencia de la madera</th>
                            <th>maderas</th>
                        </tr>
                    </thead>
                    @foreach ($data as $madera)
                    <tr>
                        <td >{{ $madera->proveedor }}</td>
                        <td>{{ $madera->entidad_vigilante }}</td>
                        <td>{{ $madera->mes }}</td>
                        <td>{{ $madera->ano }}</td>
                        <td>{{ $madera->fecha }}</td>
                        <td>{{ $madera->acto_administrativo }}</td>
                        <td>{{ $madera->salvoconducto_remision }}</td>
                        <td>{{ $madera->titular_salvoconducto }}</td>
                        <td>{{ $madera->procedencia_madera }}</td>
                        <td>
                            @foreach ($madera->maderas as $item)
                                {{ "$item->nobre_comun - $item->nombre_cientifico - $item->m3entrada \n" }}
                                <hr>
                            @endforeach
                        </td>
                    </tr>
                    @endforeach

                </table>
            </div>
            <form action="{{ route('ingreso-maderas') }}" method="get" {{-- target="_blank"
                    rel="noopener noreferrer" --}}
                    id="generarReporteIngresoMadera">
                <div hidden>
                    <input type="text" readonly name="tipoReporte" id="tipoReporte" value="{{ $tipoReporte }}">
                    <input type="text" readonly name="especifico" id="especifico" value="{{ $especifico }}">
                    <input type="text" readonly name="desdeIm" id="desdeIm" value="{{ $desde }}">
                    <input type="text" readonly name="hastaIm" id="hastaIm" value="{{ $hasta }}">
                    <input type="text" readonly name="generar" id="generar" value="">
                </div>
                <div class="d-flex justify-content-end mb-5 mt-2" >
                    <a href="{{ route('reportes-administrativos') }}" class=" btn btn-secondary">volver</a>
                    <button type="button" class="btn mx-1 btn-outline-danger" onclick="generarReporteIngresoMadera('1')"><i class="fa-regular fa-file-pdf"></i> PDF</button>
                    <button type="button" class="btn mx-1 btn-outline-success" onclick="generarReporteIngresoMadera('2')"><i class="fa-regular fa-file-excel"></i> EXCEL</button>
                    <button type="button" class="btn mx-1 btn-outline-success" onclick="generarReporteIngresoMadera('3')"><i class="fa-solid fa-file-csv"></i> CSV</button>

                </div>
            </form>
        </div>
    </div>

@endsection

@section('js')
<script src="/js/modulos/alertas-swift.js"></script>
<script src="/js/modulos/reportes/administrativos/reportes-ingreso-madera.js"></script>

@endsection
