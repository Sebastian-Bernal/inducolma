@extends('layouts.web')
@section('title', ' Reportes | Inducolma')

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
                    <h5 class="card-header bg-primary text-white">
                        <div class="d-grid gap-2 align-content-left">
                            <button class="btn text-white px-0 py-0" data-bs-toggle="collapse" href="#collapseIngresoMadera" role="button" aria-expanded="false" aria-controls="collapseIngresoMadera">
                                <h5 class="mb-0 text-start">Reporte ingreso de maderas</h5>
                            </button>
                        </div>
                    </h5>

                    <div class="card-body">
                        <div class="collapse" id="collapseIngresoMadera" >
                            <p class="card-text text-secondary">
                                Para generar un reporte de igreso de maderas es necesario seleccionar el tipo de reporte y las fechas
                                ,Ej. ICA,  Desde : 01/01/2022 - Hasta: 31/12/2022
                            </p>
                            <div class="row g-3">
                                <p>
                                    <input type="radio" class="btn-check" name="options-outlined" id="alta-densidad" autocomplete="off" onclick="seleccionaReporteIngresoMadera('alta-densidad')">
                                    <label class="btn btn-outline-primary" for="alta-densidad">Alta densidad</label>

                                    <input type="radio" class="btn-check" name="options-outlined" id="baja-densidad" autocomplete="off" onclick="seleccionaReporteIngresoMadera('baja-densidad')">
                                    <label class="btn btn-outline-primary" for="baja-densidad">Baja densidad</label>

                                    <input type="radio" class="btn-check" name="options-outlined" id="proveedor" autocomplete="off" onclick="seleccionaReporteIngresoMadera('proveedor')">
                                    <label class="btn btn-outline-primary" for="proveedor">Proveedor</label>

                                    <input type="radio" class="btn-check" name="options-outlined" id="tipo-madera" autocomplete="off" onclick="seleccionaReporteIngresoMadera('tipo-madera')">
                                    <label class="btn btn-outline-primary" for="tipo-madera">Tipo madera</label>

                                    <input type="radio" class="btn-check" name="options-outlined" id="ica" autocomplete="off" onclick="seleccionaReporteIngresoMadera('ica')">
                                    <label class="btn btn-outline-primary" for="ica">ICA</label>

                                    <input type="radio" class="btn-check" name="options-outlined" id="cvc" autocomplete="off" onclick="seleccionaReporteIngresoMadera('cvc')">
                                    <label class="btn btn-outline-primary" for="cvc">CVC</label>
                                </p>
                                <div>
                                    <div class="card card-body">
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
                                                <button type="button"  class="btn btn-outline-success" onclick="reporteIngresoMaderas()">Generar reporte</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
<script src="/js/modulos/alertas-swift.js"></script>
<script src="/js/modulos/reportes.js"></script>

@endsection
