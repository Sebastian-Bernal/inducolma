@extends('layouts.web')
@section('title', ' Reportes | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content')
    <div class="div container h-content ">
        <div class="row">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            - {{ $error }} <br>
                        @endforeach
                    </div>

                @endif
                {{-- REPORTE PROCESOS CONSTRUCCION --}}
                <div class="card mb-3 shadow-sm">
                    <h5 class="card-header bg-primary text-white">
                        <div class="d-grid gap-2 align-content-left ">
                            <button class="btn text-white px-0 py-0" data-bs-toggle="collapse" href="#collpaseConstruccion" role="button" aria-expanded="false" aria-controls="collpaseConstruccion">
                                <h5 class="d-flex justify-content-between mb-0">
                                    Reportes Construcción  <i class="fa-solid fa-chevron-down text-end"></i>
                                </h5>

                            </button>
                        </div>
                    </h5>

                    <div class="card-body">
                        <div class="collapse" id="collpaseConstruccion" >
                            <p class="card-text text-secondary">

                            </p>
                            <div class="row g-3">
                                <div>
                                    <div class="card card-body">
                                        <form action="{{ route('reporte-proceso') }}" id="formReporteProceso"
                                            class="row gx-3 gy-2 align-items-center"
                                            name="formReporteProceso"
                                            method="GET" >
                                            <div class="col-auto">
                                                <div class="input-group ">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">Tipo reporte: </span>
                                                    <select class="form-select form-select-sm"
                                                            aria-label=".form-select-sm example"
                                                            id="tipoReporteConstruccion"
                                                            name="tipoReporteConstruccion"
                                                            required
                                                            onchange="datoEspecificoConstruccion()">
                                                        <option  value="" selected>Sleccione...</option>
                                                        <option value="1">Reporte proceso por fecha</option>
                                                        <option value="2">Reporte proceso por item y fecha</option>
                                                        <option value="3">Reporte eventos de maquina</option>
                                                        <option value="4">Reporte estados de maquina</option>
                                                        <option value="5">Ordenes produccion por fecha</option>
                                                        <option value="6">Ordenes de produccion pendientes</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="input-group " id="divEspecifico" style="display:none;">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">Maquina: </span>
                                                    <select class="form-select form-select-sm"
                                                            aria-label=".form-select-sm example"
                                                            id="maquina"
                                                            name="maquina"
                                                            required>
                                                        <option  value="" selected></option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-auto">
                                                <div class="input-group " id="divEspecifico1" style="display:none;">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">Item: </span>
                                                    <select class="form-select form-select-sm"
                                                            aria-label=".form-select-sm example"
                                                            id="item"
                                                            name="item"
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
                                                            id="procesoDesde"
                                                            name="procesoDesde"
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
                                                            id="procesoHasta"
                                                            name="procesoHasta"
                                                            required>
                                                </div>
                                            </div>

                                            <div class="col-auto">
                                                <button type="button"  class="btn btn-outline-success" onclick="reporteConstruccion()">Generar reporte</button>
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
                                    Reportes Ensamble <i class="fa-solid fa-chevron-down text-end"></i>
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

        </div>
    </div>

@endsection

@section('js')
<script src="/js/modulos/alertas-swift.js"></script>
<script src="/js/modulos/reportes/produccion/reportes-construccion.js"></script>
<script src="/js/modulos/reportes/produccion/reportes-ensamble.js"></script>
@endsection
