@extends('layouts.web')
@section('title', ' Cubicaje | Inducolma')

@section('submenu')
@include('modulos.sidebars.costos-side')
@endsection
@section('content')
<div class="div container h-content ">
    <input type="hidden" id="entradaId" value="{{ $entrada->id }}">
    <input type="hidden" id="userId" value="{{ Auth::user()->id }}">
    <div class="row">
        <div class="col-8 mb-3">
            <button class="btn btn-primary" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseDetallesMadera" aria-expanded="false" aria-controls="collapseDetallesMadera">
                Ver detalles de la madera
            </button>
        </div>
        <div class="collapse" id="collapseDetallesMadera">
            <div class="card card-body">
                <div class="card number-center border-success mb-3 ">
                    <div class="card-header">
                        <h4 class="  ">{{ 'Numero de entrada: '. $entrada->id }}</h4>
                    </div>
                    <div class="card-body">
                        <h6 class="card-title">{{ 'Numero de viaje: '. $entrada->entrada_madera->id }}</h6>
                        <h6 class="card-title">{{ 'Acto administrativo: '. $entrada->entrada_madera->acto_administrativo }}</h6>
                        <h6 class="card-title">{{ 'Proveedor: '. $entrada->entrada_madera->proveedor->nombre }}</h6>
                        <hr>
                        <a href="{{ route('cubicaje.index') }}" class="btn btn-secondary">volver al inicio</a>
                    </div>
                    <div class="card-footer number-muted">
                        {{ $entrada->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        </div>


        @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
            - {{ $error }} <br>
            @endforeach
        </div>
        @endif
        <div class="mb-5">
            <form class=" row g-3" id="agregarCubicaje">

                <div class="col-md-4">
                    <label for="paqueta" class="form-label">Paqueta Nro.</label>
                    <input type="number" class="form-control" id="paqueta" name="paqueta">
                </div>
                <div class="col-md-4">
                    <label for="largo" class="form-label">Largo</label>
                    <input type="number" class="form-control" id="largo" name="largo" step="0.1">
                </div>

                <div class="col-md-4">
                    <label for="alto" class="form-label">Diametro mayor</label>
                    <input type="number" class="form-control" id="alto" name="alto" step="0.1">
                </div>
                <div class="col-md-4">
                    <label for="ancho" class="form-label">Diametro menor</label>
                    <input type="number" class="form-control" id="ancho">
                </div>

                <div class="col-md-2">
                    <br>
                    <button type="button" class="btn btn-primary mt-2" onclick="verificarInputs();">Agregar</button>
                </div>
            </form>
        </div>



        <!-- Tabla -->
        <div>
            <table id="paquetas" class="table table-bordered table-striped dt-responsive">
                <thead>
                    <tr>
                        <th>Paqueta</th>
                        <th>Bloque</th>
                        <th>Largo</th>
                        <th>Diametro mayor</th>
                        <th>Diametro menor</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody id="listarPaquetas">

                </tbody>
            </table>
        </div>

    </div>
    <!--- Boton terminar paqueta -->
    <div class="d-grid gap-2 mt-3">
        <button class="btn btn-primary" type="button" id="terminarPaqueta" onclick="terminarPaqueta()">Terminar
            Paqueta</button>
    </div>

    <!--- Boton trigger modal calificacion madera -->

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCalifica" hidden
        id="calificarMadera">

    </button>

    <!-- Modal calificacion paqueta -->
    <div class="modal fade " id="modalCalifica" tabindex="-1" aria-labelledby="modalCalificaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCalificaLabel">Calificar paqueta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formCalificacion">
                        <h5>1. LONGITUD DE LA MADERA</h5>
                        <div class="row g-2 mb-4">
                            <div class="col-auto">
                                <select class="form-select" id="longitudMadera" name="longitudMadera"
                                    onchange="sumarPuntos()">
                                    <option selected value="0">Seleccione...</option>
                                    <option value="25">5 centimetros mayor a la solicitada</option>
                                    <option value="19">3 centimetros mayor a la solicitada</option>
                                    <option value="12">igual a la solicitada</option>
                                    <option value="5">menor a la solicitada</option>
                                </select>
                            </div>

                        </div>

                        <h5>2. APARIENCIA FISICA</h5>
                        <div class="row g-2 mb-4">
                            <label for="cantonera" class="form-label">Existencia de cantonera</label>
                            <div class="col-auto">
                                <select class="form-select" id="cantonera" name="cantonera" onchange="sumarPuntos()">
                                    <option selected value="0">Seleccione...</option>
                                    <option value="6.25">0%</option>
                                    <option value="5">5%</option>
                                    <option value="3.75">10%</option>
                                    <option value="2.5">20%</option>
                                    <option value="1.25">mayor a 20%</option>
                                </select>
                            </div>
                            <label for="hongos" class="form-label">Presencia de hogos</label>
                            <div class="col-auto">
                                <select class="form-select" id="hongos" name="hongos" onchange="sumarPuntos()">
                                    <option selected value="0">Seleccione...</option>
                                    <option value="6.25">0%</option>
                                    <option value="5">5%</option>
                                    <option value="3.75">10%</option>
                                    <option value="2.5">20%</option>
                                    <option value="1.25">mayor a 20%</option>
                                </select>
                            </div>
                            <label for="rajadura" class="form-label">Presencia de rajaduras</label>
                            <div class="col-auto">
                                <select class="form-select" id="rajadura" name="rajadura" onchange="sumarPuntos()">
                                    <option selected value="0">Seleccione...</option>
                                    <option value="6.25">0%</option>
                                    <option value="5">5%</option>
                                    <option value="3.75">10%</option>
                                    <option value="2.5">20%</option>
                                    <option value="1.25">mayor a 20%</option>
                                </select>
                            </div>
                            <label for="bichos" class="form-label">Perforaci&oacute;n por bichos</label>
                            <div class="col-auto">
                                <select class="form-select" id="bichos" name="bichos" onchange="sumarPuntos()">
                                    <option selected value="0">Seleccione...</option>
                                    <option value="6.25">0%</option>
                                    <option value="5">5%</option>
                                    <option value="3.75">10%</option>
                                    <option value="2.5">20%</option>
                                    <option value="1.25">mayor a 20%</option>
                                </select>
                            </div>
                        </div>

                        <h5>3. ORGANIZACI&Oacute;N DE LA MADERA</h5>
                        <div class="row g-2 mb-4">
                            <div class="col-auto">
                                <select class="form-select" id="organizacion" name="organizacion"
                                    onchange="sumarPuntos()">
                                    <option selected value="0">Seleccione...</option>
                                    <option value="25">Excelente</option>
                                    <option value="19">Bueno</option>
                                    <option value="12">Aceptable</option>
                                    <option value="5">Deficiente</option>
                                </select>
                            </div>

                        </div>

                        <h5>4. &Aacute;REA TRANSVERSAL</h5>
                        <div class="row g-2 mb-4">
                            <label for="bichos" class="form-label">Bloques que salen del rango maximo y minimo
                                establecido</label>
                            <div class="col-auto">
                                <select class="form-select" id="rangoMaxMin" name="rangoMaxMin"
                                    onchange="sumarPuntos()">
                                    <option selected value="0">Seleccione...</option>
                                    <option value="12.5">0%</option>
                                    <option value="10">5%</option>
                                    <option value="7.5">10%</option>
                                    <option value="5">20%</option>
                                    <option value="2.5">mayor a 20%</option>
                                </select>
                            </div>

                            <label for="bichos" class="form-label">Bloques con &aacute;reas no convencionales</label>
                            <div class="col-auto">
                                <select class="form-select" id="areas" name="areas" onchange="sumarPuntos()">
                                    <option selected value="0">Seleccione...</option>
                                    <option value="12.5">0%</option>
                                    <option value="10">5%</option>
                                    <option value="7.5">10%</option>
                                    <option value="5">20%</option>
                                    <option value="2.5">mayor a 20%</option>
                                </select>
                            </div>

                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">CALIFICACI&Oacute;N TOTAL</span>
                            <input type="text" class="form-control" readonly id="puntos" name="puntos">
                        </div>
                        <div id="estado">

                        </div>
                    </form>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="validarFormulario()">Guardar
                        calificaci&oacute;n</button>

                </div>

            </div>
        </div>
    </div>

</div>

@endsection

@section('js')
<script src="/js/modulos/cubicaje-troza.js"></script>
<script src="/js/modulos/calificacion.js"></script>
@endsection
