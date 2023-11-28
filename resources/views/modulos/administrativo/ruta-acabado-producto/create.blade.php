@extends('layouts.web')
@section('title', ' Pedidos | Inducolma')

@section('submenu')
@include('modulos.sidebars.costos-side')
@endsection
@section('content')
<div class="div container h-content ">
    <div class="row">
        <div class="col-12 col-sm-10 col-lg-6 mx-auto">
                    {{-- INPUTS HIDDEN --}}
        <input type="hidden" name="pedido_id" value="{{ $pedido->id }}" id="pedido_id" readonly>
        <input type="hidden" name="cantidad_p" value="{{ $pedido->cantidad }}" id="cantidad_p" readonly>
        <input type="hidden" name="cliente_rs" value="{{ $pedido->cliente->razon_social }}" id="cliente_rs" readonly >
        <input type="hidden" name="diseno_name" value="{{ $pedido->diseno_producto_final->descripcion }}" id="diseno_name" readonly>

            {{-- Id del ususario para enviar en json: User: {{ Auth::user()->id }} --}}
            <h2 class="text-center text-primary"><strong>Crear ruta de acabados para el producto : {{
                    $pedido->diseno_producto_final->descripcion }}</strong></h2>
            <h4>
                <p class="text-center m-1">
                    <label><strong class="text-warning">PEDIDO No.:</strong> {{ $pedido->id }} / <strong class="text-warning">CLIENTE:</strong>  {{ $pedido->cliente->razon_social }}</label>

                    <label><strong class="text-warning">CANTIDAD:</strong>  {{ $pedido->cantidad }}</label>
                </p>
            </h4>



        </div>
        <div class="d-flex flex-wrap row-col-sm-1 row-col-md-2 container justify-content-center ">

            {{-- ENSANBLE tarjeta --}}
            <div class="container-sm col-sm-auto card  bg-light shadow m-3 rounded-3">
                <div class=" card-header bg-primary text-white text-center">
                    <strong>Maquina de Ensamble</strong>
                </div>
                <div class=" card-body">

                    <div class="text-center">
                        <label> Entrada de Material </label>
                    </div>
                    <form id="agregarEnsamble">
                        <div class="input-group mb-3 mt-3">

                            <label class="input-group-text" for="entraEnsamble">Entrada</label>
                            <select class="form-select" id="entraEnsamble">
                                <option value="0">Seleccionar...</option>
                                <option value="Item Diseño">Item Diseño</option>
                            </select>
                        </div>
                        <div class="text-center">
                            <label> Salida de Material </label>
                        </div>
                        <div class="input-group mb-3 mt-3">

                            <label class="input-group-text" for="saleEnsamble">Salida</label>
                            <select class="form-select" id="saleEnsamble">
                                <option value="0">Seleccionar...</option>
                                <option value="Estiba">Estiba</option>
                                <option value="Tablero">Tablero</option>
                                <option value="Carreto">Carreto</option>

                            </select>
                        </div>
                        <div class="mb-3 text-center">
                            <label for="CantidadEnsamble" class="form-label text-center">Cantidad a
                                Construir</label>
                            <input type="number" class="form-control" id="CantidadEnsamble" name="CantidadEnsamble"></input>
                        </div>
                        <div class="text-center">
                            <label> Proceso </label>

                        </div>

                        <div class="input-group mb-3 mt-3">

                            <label class="input-group-text" for="maquinaensamble">Maquina</label>
                            <select class="form-select" id="maquinaensamble">
                                @isset($maquinas['ENSAMBLE'])
                                <option value="0">Seleccionar...</option>
                                @foreach ($maquinas['ENSAMBLE'] as $ensamble)

                                <option value="{{ $ensamble['id'] }}" name="{{ $ensamble['maquina'] }}">{{
                                    $ensamble['maquina']}}</option>

                                @endforeach
                                @else
                                <option value="0">No existen maquinas de ensamble</option>
                                @endisset
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="observacionEnsamble" class="form-label text-center">Observaciones</label>
                            <textarea class="form-control" id="observacionEnsamble" rows="3"></textarea>
                        </div>
                    </form>
                    <div>
                        <button class=" bg-secondary text-center text-white container p-2 rounded"
                            onClick="agregaRutaEnsamble()">Agregar a rutas</button>
                    </div>
                </div>
            </div>

            {{--ACABADOS DE ENSANBLE tarjeta --}}
            <div class="container-sm col-sm-auto card  bg-light shadow m-3 rounded-3">
                <div class=" card-header bg-warning text-white text-center">
                    <strong>Maquina de Acabados del Diseño</strong>
                </div>
                <div class=" card-body">

                    <div class="text-center">
                        <label> Entrada de Material </label>
                    </div>
                    <form id="agregarAcabadoEnsamble">
                        <div class="input-group mb-3 mt-3">

                            <label class="input-group-text" for="entraAcabadoEnsamble">Entrada</label>
                            <select class="form-select" id="entraAcabadoEnsamble">
                                <option value="0">Seleccionar...</option>
                                <option value="Estiba">Estiba</option>
                                <option value="Tablero">Tablero</option>
                                <option value="Carreto">Carreto</option>
                            </select>
                        </div>
                        <div class="text-center">
                            <label> Salida de Material </label>
                        </div>
                        <div class="input-group mb-3 mt-3">

                            <label class="input-group-text" for="saleAcabadoEnsamble">Salida</label>
                            <select class="form-select" id="saleAcabadoEnsamble">
                                <option value="0">Seleccionar...</option>
                                <option value="Estiba">Estiba</option>
                                <option value="Tablero">Tablero</option>
                                <option value="Carreto">Carreto</option>

                            </select>
                        </div>
                        <div class="mb-3 text-center">
                            <label for="CantidadAcabadoEnsamble" class="form-label text-center">Cantidad a
                                Construir</label>
                            <input type="number" class="form-control" id="CantidadAcabadoEnsamble"></input>
                        </div>
                        <div class="text-center">
                            <label> Proceso </label>

                        </div>

                        <div class="input-group mb-3 mt-3">

                            <label class="input-group-text" for="maquinaAcabadoensamble">Maquina</label>
                            <select class="form-select" id="maquinaAcabadoensamble">
                                @isset($maquinas['ACABADO_ENSAMBLE'])
                                <option value="0">Seleccionar...</option>
                                @foreach ($maquinas['ACABADO_ENSAMBLE'] as $acabado_ensamble)

                                <option value="{{ $acabado_ensamble['id'] }}" name="{{ $acabado_ensamble['maquina'] }}">
                                    {{ $acabado_ensamble['maquina']}}
                                </option>

                                @endforeach
                                @else
                                <option value="0">No existen maquinas de ensamble</option>
                                @endisset
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="observacionAcabadoEnsamble" class="form-label text-center">Observaciones</label>
                            <textarea class="form-control" id="observacionAcabadoEnsamble" rows="3"></textarea>
                        </div>
                    </form>
                    <div>
                        <button class=" bg-secondary text-center text-white container p-2 rounded"
                            onClick="agregaRutaAcabadoEnsamble()">Agregar a rutas</button>
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
                    <button type="button" class="btn btn-primary container-fluid" onclick="terminarRuta()">Guardar ruta
                        de
                        programación</button>
                </div>
    </div>

</div>
</div>

@endsection

@section('js')
<script src="/js/modulos/rutaAcabados.js"></script>
@endsection