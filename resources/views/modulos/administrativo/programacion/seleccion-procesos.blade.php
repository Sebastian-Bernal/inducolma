@extends('layouts.web')
@section('title', ' Ruta de programación | Inducolma')

@section('submenu')
@include('modulos.sidebars.costos-side')
@endsection
@section('content')
<div class="div container h-content ">
    <div>
        <h2 class="text-center text-primary"><strong>Ruta de Programación</strong></h2>
        <h4>
            <p class="text-center m-1">
                <strong>Item a realizar: {{ $orden->item->descripcion }} / Cliente: {{ $orden->pedido->cliente->nombre
                    }}</strong></br>
                <label>Orden de pedido No.: {{ $orden->pedido->id }} / Tipo de Madera: {{
                    $orden->item->tipo_madera->descripcion }}</label>
            </p>
        </h4>
        {{-- INPUTS HIDDEN --}}
        <input type="hidden" name="cubicaje_id" value="{{ $cubicaje_id }}" id="cubicaje_id" readonly>
        <input type="hidden" name="orden_id" value="{{ $orden->id }}" id="orden_id" readonly>
        <input type="hidden" name="item_id" value="{{ $orden->item->id }}" id="item_id" readonly >
        <input type="hidden" name="item_nome" value="{{ $orden->pedido_id }}" id="pedido_id" readonly>

        <div class="d-flex flex-wrap row-col-sm-1 row-col-md-2 container-fluid">
            {{-- corte inicial tarjeta --}}
            <div class="container-sm col-sm-auto card  bg-light shadow m-3 rounded-3">
                <div class=" card-header bg-primary text-white">
                    Corte Inicial
                </div>
                <div class=" card-body">

                    <div class="text-center">
                        <label> Entrada de Madera </label>
                    </div>
                    <form id="agregarInicial">
                        <div class="input-group mb-3 mt-3">

                            <label class="input-group-text" for="entraInicial">Entrada</label>
                            <select class="form-select" id="entraInicial">
                                <option value="0">Seleccionar...</option>
                                <option value="Bloque">Bloque</option>
                                <option value="Punta">Punta</option>
                                <option value="Troza">Troza</option>
                            </select>
                        </div>
                        <div class="text-center">
                            <label> Salida de Madera </label>
                        </div>
                        <div class="input-group mb-3 mt-3">

                            <label class="input-group-text" for="saleInicial">Salida</label>
                            <select class="form-select" id="saleInicial">
                                <option value="0">Seleccionar...</option>
                                <option value="Taco">Taco</option>
                                <option value="Punta">Punta</option>

                            </select>
                        </div>
                        <div class="text-center">
                            <label> Proceso </label>

                        </div>
                        <div class="input-group mb-3 mt-3">

                            <label class="input-group-text" for="maquinaInicial">Maquina</label>
                            <select class="form-select" id="maquinaInicial">
                                <option value="0">Seleccionar...</option>
                                @forelse ($maquinas['INICIAL'] as $inicial)
                                <option value="{{ $inicial->id }}" name="{{ $inicial->maquina }}">{{ $inicial->maquina
                                    }}</option>

                                @empty
                                <option>No existen maquinas creadas</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="observacionInicial" class="form-label text-center">Observaciones</label>
                            <textarea class="form-control" id="observacionInicial" rows="3"></textarea>
                        </div>
                    </form>
                    <div>
                        <button class=" bg-secondary text-center text-white container p-2 rounded"
                            onClick="agregaRutaInicial()">Agregar a rutas</button>
                    </div>
                </div>


            </div>
            {{-- corte intermedio tarjeta --}}
            <div class="container-sm col-sm-auto card  bg-light shadow m-3 rounded-3">
                <div class=" card-header bg-warning text-white">
                    Corte Intermedio
                </div>
                <div class=" card-body">

                    <div class="text-center">
                        <label> Entrada de Madera </label>
                    </div>
                    <form id="agregarIntermedia">
                        <div class="input-group mb-3 mt-3">

                            <label class="input-group-text" for="entraIntermedia">Entrada</label>
                            <select class="form-select" id="entraIntermedia">
                                <option value="0">Seleccionar...</option>
                                <option value="Punta">Bloque</option>
                            </select>
                        </div>
                        <div class="text-center">
                            <label> Salida de Madera </label>
                        </div>
                        <div class="input-group mb-3 mt-3">

                            <label class="input-group-text" for="saleIntermedia">Salida</label>
                            <select class="form-select" id="saleIntermedia">
                                <option value="0">Seleccionar...</option>
                                <option value="Taco">Taco</option>
                                <option value="Item Final">Item Final</option>
                                < </select>
                        </div>
                        <div class="text-center">
                            <label> Proceso </label>

                        </div>
                        <div class="input-group mb-3 mt-3">

                            <label class="input-group-text" for="maquinaIntermedia">Maquina</label>
                            <select class="form-select" id="maquinaIntermedia">
                                <option value="0">Seleccionar...</option>
                                @forelse ($maquinas['INTERMEDIO'] as $intermedio)
                                <option value="{{ $intermedio->id }}" name="{{ $intermedio->maquina }}">{{
                                    $intermedio->maquina }}</option>

                                @empty
                                <option>No existen maquinas creadas</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="observacionIntermedia" class="form-label text-center">Observaciones</label>
                            <textarea class="form-control" id="observacionIntermedia" rows="3"></textarea>
                        </div>
                    </form>
                    <div>
                        <button class=" bg-secondary text-center text-white container p-2 rounded"
                            onClick="agregaRutaIntermedia()">Agregar a rutas</button>
                    </div>
                </div>


            </div>
            {{-- corte final tarjeta --}}
            <div class="container-sm col-sm-auto card  bg-light shadow m-3 rounded-3">
                <div class=" card-header bg-secondary text-white">
                    Corte Final
                </div>
                <div class=" card-body">

                    <div class="text-center">
                        <label> Entrada de Madera </label>
                    </div>
                    <form id="agregarFinal">
                        <div class="input-group mb-3 mt-3">

                            <label class="input-group-text" for="entraFinal">Entrada</label>
                            <select class="form-select" id="entraFinal">
                                <option value="0">Seleccionar...</option>
                                <option value="Bloque">Bloque</option>
                                <option value="Punta">Punta</option>
                                <option value="Troza">Troza</option>
                            </select>
                        </div>
                        <div class="text-center">
                            <label> Salida de Madera </label>
                        </div>
                        <div class="input-group mb-3 mt-3">

                            <label class="input-group-text" for="saleFinal">Salida</label>
                            <select class="form-select" id="saleFinal">
                                <option value="0">Seleccionar...</option>
                                <option value="Taco">Taco</option>
                                <option value="Punta">Punta</option>
                                < </select>
                        </div>
                        <div class="text-center">
                            <label> Proceso </label>

                        </div>
                        <div class="input-group mb-3 mt-3">

                            <label class="input-group-text" for="maquinaFinal">Maquina</label>
                            <select class="form-select" id="maquinaFinal">
                                <option value="0">Seleccionar...</option>
                                @forelse ($maquinas['FINAL'] as $final)
                                <option value="{{ $final->id }}" name="{{ $final->maquina }}">{{ $final->maquina }}
                                </option>

                                @empty
                                <option>No existen maquinas creadas</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="observacionFinal" class="form-label text-center">Observaciones</label>
                            <textarea class="form-control" id="observacionFinal" rows="3"></textarea>
                        </div>
                    </form>
                    <div>
                        <button class=" bg-secondary text-center text-white container p-2 rounded"
                            onClick="agregaRutaFinal()">Agregar a rutas</button>
                    </div>
                </div>


            </div>
            {{-- ruta de acabados tarjeta --}}
            <div class="container-sm col-sm-auto card  bg-light shadow m-3 rounded-3">
                <div class=" card-header bg-dark text-white">
                    Acabados
                </div>
                <div class=" card-body">

                    <div class="text-center">
                        <label> Entrada de Madera </label>
                    </div>
                    <form id="agregarAcabados">
                        <div class="input-group mb-3 mt-3">

                            <label class="input-group-text" for="entraAcabados">Entrada</label>
                            <select class="form-select" id="entraAcabados">
                                <option value="0">Seleccionar...</option>
                                <option value="Bloque">Bloque</option>
                                <option value="Punta">Punta</option>
                                <option value="Troza">Troza</option>
                            </select>
                        </div>
                        <div class="text-center">
                            <label> Salida de Madera </label>
                        </div>
                        <div class="input-group mb-3 mt-3">

                            <label class="input-group-text" for="saleAcabados">Salida</label>
                            <select class="form-select" id="saleAcabados">
                                <option value="0">Seleccionar...</option>
                                <option value="Taco">Taco</option>
                                <option value="Punta">Punta</option>
                                < </select>
                        </div>
                        <div class="text-center">
                            <label> Proceso </label>

                        </div>
                        <div class="input-group mb-3 mt-3">

                            <label class="input-group-text" for="maquinaAcabados">Maquina</label>
                            <select class="form-select" id="maquinaAcabados">
                                <option value="0">Seleccionar...</option>
                                @forelse ($maquinas['ACABADOS'] as $acabados)
                                <option value="{{ $acabados->id }}" name="{{ $acabados->maquina }}">{{
                                    $acabados->maquina }}</option>

                                @empty
                                <option>No existen maquinas creadas</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="observacionAcabados" class="form-label text-center">Observaciones</label>
                            <textarea class="form-control" id="observacionAcabados" rows="3"></textarea>
                        </div>
                    </form>
                    <div>
                        <button class=" bg-secondary text-center text-white container p-2 rounded"
                            onClick="agregaRutaAcabados()">Agregar a rutas</button>
                    </div>
                </div>


            </div>

        </div>
        <br>
        <br>
        <hr>
        <br>
        <br>
        <div>
            <div class="text-center text-primary">
                <h2>Rutas Asignadas</h2>
                <br>
                <br>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Maquina</th>
                        <th scope="col">Entrada</th>
                        <th scope="col">Salida</th>
                        <th scope="col">Observacion</th>
                        <th scope="col">Eliminar</th>
                    </tr>
                </thead>
                <tbody id="listarProcesos">

                </tbody>
            </table>
        </div>
        <div>
            <button type="button" class="btn btn-primary container-fluid" onclick="terminarRuta()">Guardar ruta de
                programación</button>
        </div>
    </div>

</div>
@endsection

@section('js')
<script src="/js/modulos/rutaProg.js"></script>

@endsection
