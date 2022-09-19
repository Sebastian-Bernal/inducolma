@extends('layouts.web')
@section('title', ' Clientes | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content')
    <div class="div container h-content ">
        <div class="row">
            {{-- <div class="col-12 col-sm-10 col-lg-6 mx-auto"> --}}

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            - {{ $error }} <br>
                        @endforeach
                    </div>

                @endif

                <div class="card mb-3 shadow-sm">
                    <h5 class="card-header bg-primary text-white">Reporte ingreso de maderas</h5>
                    <div class="card-body">
                        {{-- <h5 class="card-title">Special title treatment</h5> --}}
                        <p class="card-text text-secondary">
                            Puede generar un reporte por fecha del ingreso de maderas, Ej. Desde : 01/01/2022 - Hasta: 31/12/2022
                        </p>
                        <div class="row g-3">

                                <form action="" id="reporteIngresoMadera"
                                class="row gx-3 gy-2 align-items-center"
                                name="reporteIngresoMadera"
                                method="GET" target="_blank"
                                rel="noopener noreferrer"
                                >
                                <div class="col-auto">
                                    <div class="input-group ">
                                        <span class="input-group-text" id="inputGroup-sizing-default">Desde: </span>
                                        <input type="date"
                                                class="form-control"
                                                aria-label="Sizing example input"
                                                aria-describedby="inputGroup-sizing-default"
                                                id="desdeIm"
                                                name="desdeIm">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="input-group">
                                        <span class="input-group-text" id="inputGroup-sizing-default">Hasta: </span>
                                        <input type="date"
                                                class="form-control"
                                                aria-label="Sizing example input"
                                                aria-describedby="inputGroup-sizing-default"
                                                id="hastaIm"
                                                name="hastaIm">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-outline-danger" onclick="reporteIngresoMaderas('ingreso-maderas-pdf')">PDF</button>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-outline-success" onclick="reporteIngresoMaderas('ingreso-maderas-xls')">EXCEL</button>
                                </div>
                                <div class="col-auto">
                                    <button type="button"  class="btn btn-outline-success" onclick="reporteIngresoMaderas('ingreso-maderas-csv')">CSV</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>

                <hr>

                <div class="card mb-3 shadow-sm">
                    <h5 class="card-header bg-warning text-white">Reporte ingreso cubicajes</h5>
                    <div class="card-body">
                        {{-- <h5 class="card-title">Special title treatment</h5> --}}
                        <p class="card-text text-secondary">
                            Puede generar un reporte por fecha del ingreso de cubicajes, Ej. Desde : 01/01/2022 - Hasta: 31/12/2022
                        </p>
                        <div class="row g-3">
                            <div class="col-auto">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="inputGroup-sizing-default">Desde: </span>
                                    <input type="date" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="inputGroup-sizing-default">Hasta: </span>
                                    <input type="date" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                </div>
                            </div>

                            <div class="col-auto">
                                <a href="#" class="btn btn-outline-danger">PDF</a>
                            </div>
                            <div class="col-auto">
                                <a href="#" class="btn btn-outline-success">EXCEL</a>
                            </div>
                            <div class="col-auto">
                                <a href="#" class="btn btn-outline-success">CSV</a>
                            </div>
                        </div>

                    </div>
                </div>

                <hr>

                <div class="card mb-3 shadow-sm">
                    <h5 class="card-header bg-secondary text-white">Reporte ingreso del personal</h5>
                    <div class="card-body">
                        {{-- <h5 class="card-title">Special title treatment</h5> --}}
                        <p class="card-text text-secondary">
                            Puede generar un reporte por fecha del ingreso del personal, Ej. Desde : 01/01/2022 - Hasta: 31/12/2022
                        </p>
                        <div class="row g-3">
                            <div class="col-auto">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="inputGroup-sizing-default">Desde: </span>
                                    <input type="date" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="inputGroup-sizing-default">Hasta: </span>
                                    <input type="date" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                </div>
                            </div>

                            <div class="col-auto">
                                <a href="#" class="btn btn-outline-danger">PDF</a>
                            </div>
                            <div class="col-auto">
                                <a href="#" class="btn btn-outline-success">EXCEL</a>
                            </div>
                            <div class="col-auto">
                                <a href="#" class="btn btn-outline-success">CSV</a>
                            </div>
                        </div>

                    </div>
                </div>

            {{-- </div> --}}
        </div>
    </div>

@endsection

@section('js')
<script src="/js/modulos/reportes.js"></script>
@endsection
