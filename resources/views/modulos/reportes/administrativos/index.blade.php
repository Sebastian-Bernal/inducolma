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
                {{-- REPORTE INGRESO DE MADERAS --}}
                <div class="card mb-3 shadow-sm">
                    <h5 class="card-header bg-primary text-white">
                        <div class="d-grid gap-2 align-content-left ">
                            <button class="btn text-white px-0 py-0" data-bs-toggle="collapse" href="#collapseIngresoMadera" role="button" aria-expanded="false" aria-controls="collapseIngresoMadera">
                                <h5 class="d-flex justify-content-between mb-0">
                                    Reporte ingreso de maderas  <i class="fa-solid fa-chevron-down text-end"></i>
                                </h5>

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
                                <div>
                                    <div class="card card-body">
                                        <form action="{{ route('ingreso-maderas') }}" id="reporteIngresoMadera"
                                            class="row gx-3 gy-2 align-items-center"
                                            name="reporteIngresoMadera"
                                            method="GET" >
                                            <div class="col-auto">
                                                <div class="input-group ">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">Tipo reporte: </span>
                                                    <select class="form-select form-select-sm"
                                                            aria-label=".form-select-sm example"
                                                            id="tipoReporte"
                                                            name="tipoReporte"
                                                            required
                                                            onchange="datoEspecifico()">
                                                        <option  value="" selected>Tipo reporte</option>
                                                        <option value="1">Alta densidad</option>
                                                        <option value="2">Baja densidad</option>
                                                        <option value="3">Proveedor</option>
                                                        <option value="4">Tipo madera</option>
                                                        <option value="5">ICA</option>
                                                        <option value="6">CVC</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="input-group " id="divEspecifico" style="display:none;">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">Filtro: </span>
                                                    <select class="form-select form-select-sm"
                                                            aria-label=".form-select-sm example"
                                                            id="especifico"
                                                            name="especifico"
                                                            required>
                                                        <option  value="" selected></option>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="input-group ">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">Desde: </span>
                                                    <input type="date"
                                                            class="form-control"
                                                            aria-label="Sizing example input"
                                                            aria-describedby="inputGroup-sizing-default"
                                                            id="desdeIm"
                                                            name="desdeIm"
                                                            required>
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
                                                            name="hastaIm"
                                                            required>
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
                {{-- REPORTE CUBICAJES --}}
                <div class="card mb-3 shadow-sm">
                    <h5 class="card-header bg-warning text-white">
                        <div class="d-grid gap-2 align-content-left">
                            <button class="btn text-white px-0 py-0" data-bs-toggle="collapse" href="#collapseCubicajes" role="button" aria-expanded="false" aria-controls="collapseCubicajes">
                                <h5 class="d-flex justify-content-between mb-0 text-start">
                                    Reporte cubicajes <i class="fa-solid fa-chevron-down text-end"></i>
                                </h5>
                            </button>
                        </div>
                    </h5>

                    <div class="card-body">
                        <div class="collapse" id="collapseCubicajes" >
                            <p class="card-text text-secondary">

                            </p>
                            <div class="row g-3">
                                <div>
                                    <div class="card card-body">
                                        <form action="{{ route('reporte-cubicajes') }}" id="formReporteCubicajes"
                                            class="row gx-3 gy-2 align-items-center"
                                            name="formReporteCubicajes"
                                            method="GET" target="_blank"
                                            rel="noopener noreferrer"
                                            >
                                            <div class="col-auto">
                                                <div class="input-group ">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">Tipo reporte: </span>
                                                    <select class="form-select form-select-sm"
                                                            aria-label=".form-select-sm example"
                                                            id="tipoReporteCubicaje"
                                                            name="tipoReporteCubicaje"
                                                            required
                                                            onchange="filtroCubicajes()">
                                                        <option  value="" selected>Seleccione...</option>
                                                        <option value="1">Cubicaje por viaje</option>
                                                        <option value="2">Transformacion  de madera por viaje</option>
                                                        <option value="3">Calificacion de paquetas por viaje</option>
                                                        <option value="4">Calificacion de paquetas por proveedor por viaje </option>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="input-group " id="divFiltroCubicaje1" style="display:none;">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">Viaje: </span>
                                                    <input  class="form-control"  type="number" id="filtroCubiaje1" name="filtroCubiaje1" required min="1">
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="input-group " id="divFiltroCubicaje2" style="display:none;">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">Proveedor: </span>
                                                    <select class="form-select form-select-sm"
                                                            aria-label=".form-select-sm example"
                                                            id="filtroCubiaje2"
                                                            name="filtroCubiaje2"

                                                            required>
                                                        <option  value="" selected></option>

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-auto">
                                                <div class="input-group " id="divDesde" style="display:none;">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">Desde: </span>
                                                    <input type="date"
                                                            class="form-control"
                                                            aria-label="Sizing example input"
                                                            aria-describedby="inputGroup-sizing-default"
                                                            id="cubicajeDesde"
                                                            name="cubicajeDesde"
                                                            required>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="input-group" id="divHasta" style="display:none;">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">Hasta: </span>
                                                    <input type="date"
                                                            class="form-control"
                                                            aria-label="Sizing example input"
                                                            aria-describedby="inputGroup-sizing-default"
                                                            id="cubicajeHasta"
                                                            name="cubicajeHasta"
                                                            required>
                                                </div>
                                            </div>


                                            <div class="col-auto">
                                                <button type="button"  class="btn btn-outline-success" onclick="reporteCubicajes()">Generar reporte</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>
                {{-- REPORTE INGRESO DE PERSONAL --}}
                <div class="card mb-3 shadow-sm">
                    <h5 class="card-header bg-secondary text-white">
                        <div class="d-grid gap-2 align-content-left">
                            <button class="btn text-white px-0 py-0" data-bs-toggle="collapse" href="#collapseIngresoPersonal" role="button" aria-expanded="false" aria-controls="collapseIngresoPersonal">
                                <h5 class="d-flex justify-content-between mb-0 text-start">
                                    Reportes de trabajadores <i class="fa-solid fa-chevron-down text-end"></i>
                                </h5>
                            </button>
                        </div>
                    </h5>

                    <div class="card-body">
                        <div class="collapse" id="collapseIngresoPersonal" >
                            <p class="card-text text-secondary">

                            </p>
                            <div class="row g-3">
                                <div>
                                    <div class="card card-body">
                                        <form action="{{ route('reporte-personal') }}" id="formReportePersonal"
                                            class="row gx-3 gy-2 align-items-center"
                                            name="formReportePersonal"
                                            method="GET" target="_blank"
                                            rel="noopener noreferrer"
                                            >
                                            <div class="col-auto">
                                                <div class="input-group ">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">Tipo reporte: </span>
                                                    <select class="form-select form-select-sm"
                                                            aria-label=".form-select-sm example"
                                                            id="tipoReportePersonal"
                                                            name="tipoReportePersonal"
                                                            required
                                                            onchange="datoEspecifico()">
                                                        <option  value="" selected>Seleccione...</option>
                                                        <option value="1">Turno por empleado</option>
                                                        <option value="2">Eventos de empleados</option>
                                                        <option value="3">Horas laboradas</option>
                                                        <option value="4">Ingreso de empleados</option>
                                                        <option value="5">ingreso de terceros</option>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="input-group " id="divEspecifico" style="display:none;">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">Filtro: </span>
                                                    <select class="form-select form-select-sm"
                                                            aria-label=".form-select-sm example"
                                                            id="filtroPersonal"
                                                            name="filtroPersonal"
                                                            required>
                                                        <option  value="" selected></option>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="input-group ">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">Desde: </span>
                                                    <input type="date"
                                                            class="form-control"
                                                            aria-label="Sizing example input"
                                                            aria-describedby="inputGroup-sizing-default"
                                                            id="personalDesde"
                                                            name="personalDesde"
                                                            required>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="input-group">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">Hasta: </span>
                                                    <input type="date"
                                                            class="form-control"
                                                            aria-label="Sizing example input"
                                                            aria-describedby="inputGroup-sizing-default"
                                                            id="personalHasta"
                                                            name="personalHasta"
                                                            required>
                                                </div>
                                            </div>

                                            <div class="col-auto">
                                                <button type="button"  class="btn btn-outline-success" onclick="reportePersonal()">Generar reporte</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
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
<script src="/js/modulos/reportes/administrativos/reportes-ingreso-madera.js"></script>
<script src="/js/modulos/reportes/administrativos/reportes-cubicajes.js"></script>
<script src="/js/modulos/reportes/administrativos/reportes-personal.js"></script>
@endsection
