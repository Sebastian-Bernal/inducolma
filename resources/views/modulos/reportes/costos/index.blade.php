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
                {{-- REPORTES COSTOS --}}
                <div class="card mb-3 shadow-sm">
                    <h5 class="card-header bg-primary text-white">
                        <div class="d-grid gap-2 align-content-left ">
                            <button class="btn text-white px-0 py-0" data-bs-toggle="collapse" href="#collapseIngresoMadera" role="button" aria-expanded="false" aria-controls="collapseIngresoMadera">
                                <h5 class="d-flex justify-content-between mb-0">
                                    Reportes de costos  <i class="fa-solid fa-chevron-down text-end"></i>
                                </h5>

                            </button>
                        </div>
                    </h5>

                    <div class="card-body">
                        <div class="collapse" id="collapseIngresoMadera" >
                            <p class="card-text text-secondary">
                            </p>
                            <div class="row g-3">
                                <div>
                                    <div class="card card-body">
                                        <form action="{{ route('reporte-costos') }}" id="formReportePedidos"
                                            class="row gx-3 gy-2 align-items-center"
                                            name="formReportePedidos"
                                            method="GET" >
                                            <div class="col-auto">
                                                <div class="input-group ">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">Tipo reporte: </span>
                                                    <select class="form-select form-select-sm"
                                                            aria-label=".form-select-sm example"
                                                            id="tipoReporteCosotos"
                                                            name="tipoReporteCosotos"
                                                            required
                                                            onchange="datoEspecificoCostos()">
                                                        <option  value="" selected>Seleccione ...</option>
                                                        <option value="1">POR FECHA Y MAQUINA</option>
                                                        <option value="2">POR FECHA Y USUARIO</option>
                                                        <option value="3">POR PEDIDO</option>
                                                        <option value="4">POR ITEM Y FECHA</option>
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
                                                    <span class="input-group-text" id="inputGroup-sizing-default">Usuario: </span>
                                                    <select class="form-select form-select-sm"
                                                            aria-label=".form-select-sm example"
                                                            id="usuario"
                                                            name="usuario"
                                                            required>
                                                        <option  value="" selected></option>

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-auto">
                                                <div class="input-group " id="divEspecifico2" style="display:none;">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">Nro pedido: </span>
                                                    <input type="number"
                                                        class="form-control"
                                                        aria-label="Sizing example input"
                                                        aria-describedby="inputGroup-sizing-default"
                                                        id="pedido"
                                                        name="pedido"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="col-auto">
                                                <div class="input-group " id="divEspecifico3" style="display:none;">
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
                                                            id="costoDesde"
                                                            name="costoDesde"
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
                                                            id="costoHasta"
                                                            name="costoHasta"
                                                            required>
                                                </div>
                                            </div>

                                            <div class="col-auto">
                                                <button type="button"  class="btn btn-outline-success" onclick="reporteCostos()">Generar reporte</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>

@endsection

@section('js')
<script src="/js/modulos/alertas-swift.js"></script>
<script src="/js/modulos/reportes/costos/reportes-costos.js"></script>
@endsection
