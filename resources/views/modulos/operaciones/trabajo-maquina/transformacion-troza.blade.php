@extends('layouts.web')
@section('title', ' Transformacion de trozas | Inducolma')

@section('submenu')
@include('modulos.sidebars.costos-side')
@endsection
@section('content')
<div class="div container h-content ">
    <input type="hidden" id="userId" value="{{ Auth::user()->id }}">
    <button hidden id="trozas" onclick="cargarTrozas({{ $trozas }})"></button>
    <div class="row">
        <div class="col-12  mx-auto ">
            <div class="col-12 text-primary">

                <h1 class="text-center">Transformacion de trozas</h1>

            </div>
            <hr>
            <div class="col-12 col-md-12 text-secondary">
                <h3 class="text-center fw-bolder">Entrada a transformar No. {{ $entrada->id }}</h3>
                <div class="col-md-6 col-12 mx-auto">
                    <input type="hidden" class="form-control" id="entrada" name="entrada" value="{{ $entrada->id }}">
                </div>
            </div>

            <div class="col-12 mx-auto bg-primary pb-1 pt-2 mt-3 rounded-3 text-white">
                <h4 class="text-center">Transformación del Bloque principal</h4>
            </div>
            <div class="col-12 col-md-12 mt-3 text-secondary">
                <h3 class="text-center fw-bolder">
                    Transformación del bloque No.
                    <span id="numeroBloque"></span>
                </h3>

            </div>
            <form class=" row g-3 mt-3" id="agregarCubicaje">

                <input type="hidden" id="entradaId">
                <input type="hidden" id="bloque">
                <input type="hidden" id="paqueta">
                <input type="hidden" id="idCubicaje">


                <div class="col-md-4 col-12">
                    <label for="largo" class="form-label">Largo</label>
                    <input type="number" class="form-control" id="largo"  step="0.1" readonly>
                </div>
                <div class="col-md-4 col-12">
                    <label for="alto" class="form-label">Alto</label>
                    <input type="number" class="form-control" id="alto"  step="0.1" min="1">
                </div>
                <div class="col-md-4 col-12">
                    <label for="ancho" class="form-label">Ancho</label>
                    <input type="number" class="form-control" id="ancho" step="0.1" min="1">
                </div>
                <div class="col-md-4 col-12">
                    <button type="button" class="btn btn-primary" onclick="verificarInputs()">Guardar bloque</button>
                </div>
            </form>
            <div class="col-12 mx-auto bg-warning pb-1 pt-2 mt-5 rounded-3 text-white">
                <h4 class="text-center">Transformación del material sobrante</h4>
            </div>

            <form class=" row g-3 mt-3" id="agregarSobrante">
                <input type="hidden" id="ingresoAnterior">
                <input type="hidden" id="trozaId">
                <div class="col-md-4 col-12">
                    <label for="largoSobrante" class="form-label">Largo</label>
                    <input type="number" class="form-control" id="largoSobrante" step="0.1"  min="1">
                </div>
                <div class="col-md-4 col-12">
                    <label for="altoSobrante" class="form-label">Alto</label>
                    <input type="number" class="form-control" id="altoSobrante"  step="0.1" min="1">
                </div>
                <div class="col-md-4 col-12">
                    <label for="anchoSobrante" class="form-label">Ancho</label>
                    <input type="number" class="form-control" id="anchoSobrante" step="0.1" min="1">
                </div>
                <div class="col-md-4 col-12">
                    <button type="button" class="btn btn-warning" onclick="verificarInputsSobrante()">Guardar sobrante</button>
                </div>
            </form>
            <div class="col-12 col-md-12 mt-4 text-secondary">
                <div class="text-center text-warning">
                    <h2>Transformaciones por guardar</h2>
                    <br>
                </div>
                <table class="table table-striped" id="listaCubicaje">
                    <thead>
                        <tr>
                            <th scope="col">Paqueta</th>
                            <th scope="col">Entrada</th>
                            <th scope="col">Bloque No.</th>
                            <th scope="col">Alto</th>
                            <th scope="col">Ancho</th>
                            <th scope="col">Largo</th>
                            <th> Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="listarPaquetas">

                    </tbody>
                </table>
            </div>
            <div class="mb-4">
                <button type="button" class="btn btn-secondary container-fluid"
                    onclick="terminarPaqueta()">Terminar transformacion de entrada</button>
            </div>
        </div>
    </div>

</div>

@endsection

@section('js')
<script src="/js/modulos/alertas-swift.js"></script>
<script src="/js/modulos/trozas.js"></script>
<script src="/js/modulos/troza-sobrante.js"></script>
@endsection
