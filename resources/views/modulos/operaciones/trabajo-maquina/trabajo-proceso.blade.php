@extends('layouts.web')
@section('title', ' Trabajo proceso | Inducolma')

@section('submenu')
@include('modulos.sidebars.costos-side')
@endsection
@section('content')
<div class="div container h-content m-auto">
    <h1 class="text-primary text-center m-auto">Orden de Producción No. {{ $trabajo_maquina->orden_produccion_id }}     --     Item a Fabricar: {{ $trabajo_maquina->item->descripcion }}</h1>
    <h3 class="text-secondary text-center">Viaje No. {{ $trabajo_maquina->cubicaje->entrada_madera_id }}  --  Paqueta No. {{ $trabajo_maquina->cubicaje->paqueta }} -- Cantidad items: {{ $trabajo_maquina->cantidad_items }}</h3>
    <h3 class="text-dark text-center">Observaciones: {{ $trabajo_maquina->observacion }}</h3>
    
    <div class="d-flex flex-wrap row  m-auto align-items-center container-fluid ">
        <div class="container col-xl-6 col-lg-6 col-md-6 col-sm-12 border border-4 border-warning shadow rounded-3 rounded pt-3 mb-2 mt-2">
            <span class="text-warning fw-bold">OPERARIOS EN ESTA MAQUINA:</span><br>
            @forelse ($turno_usuarios as $turno)
                <span> * {{ $turno->user->name }}</span><br>
            @empty
                <span>No se encontraron operarios</span>
            @endforelse

        </div>
        <div class="self-end col-md-6 col-sm-12 col-12">
            @include('modulos.partials.eventos')
        </div>
    </div>
    <div class="col-sm-12 bg-ligth border border-4 border-secondary shadow p-3 m-auto rounded round-3">
        <div class="col-sm-12 p-2 m-auto d-flex flex-wrap container-fluid">
            <div class="col-sm-12 col-md-6 p-2">
                <label class="text-center w-100" for="entradaItem">Item entrante:</label><br>
                <input class="w-100 rounded rounded-pill text-center" type="text" name="entradaItem" value="{{ $trabajo_maquina->entrada }}" readonly>
            </div>
            <div class="col-sm-12 col-md-6 p-2">
                <label class="text-center w-100" for="salidaItem">Item saliente:</label><br>
                <input class="w-100 rounded rounded-pill text-center" type="text" name="salidaItem" value="{{ $trabajo_maquina->salida }}" readonly>
            </div>
        </div>
        <div class="col-sm-12 p-2 m-auto d-flex flex-wrap container-fluid">
            <div class="col-sm-12 col-md-6 p-2">
                <label class="text-center w-100" for="entradacm">Cm3 entrada:</label><br>
                <input class="w-100 rounded rounded-pill text-center" type="number" name="entradacm" value="{{ $trabajo_maquina->cm3_entrada }}" readonly>
            </div>
            <div class="col-sm-12 col-md-6 p-2">
                <label class="text-center w-100" for="salidaItem">Cm3 salida:</label><br>
                <input class="w-100 rounded rounded-pill text-center" type="number" name="salidaItem" >
            </div>
        </div>
        <div class="col-sm-12 p-2 m-auto d-flex flex-wrap container-fluid">
            <div class="col-sm-12 col-md-6 p-2">
                <label class="text-center w-100" for="entradacan">Cantidad entrada:</label><br>
                <input class="w-100 rounded rounded-pill text-center" type="number" name="entradacan" value="{{ $trabajo_maquina->cantidad_items }}" readonly>
            </div>
            <div class="input-group col-sm-12 col-md-6 p-2">
                <span class="input-gtoup-text text-center">Cantidad salida:</span>
                <input class="form-control rounded rounded-pill text-center" type="number" name="salidacan" >
            </div>
        </div>
        <div class="col-sm-12 p-2 m-auto d-flex flex-wrap container-fluid">
            <div class="col-sm-12 col-md-6 p-2">
                <label class="text-center w-100" for="entradatar">Tarjeta entrada:</label><br>
                <input class="w-100 rounded rounded-pill text-center" type="text" name="entradatar">
            </div>
            <div class="col-sm-12 col-md-6 p-2">
                <label class="text-center w-100" for="salidatar">Tarjeta salida:</label><br>
                <input class="w-100 rounded rounded-pill text-center" type="text" name="salidatar" >
            </div>
        </div>
        <div class="col-sm-12 p-2 m-auto d-flex flex-wrap container-fluid">
            <div class="col-sm-12 col-md-6 p-2">
                <label class="text-center w-100" for="sobrante">Sobrante:</label><br>
                <input class="w-100 rounded rounded-pill text-center" type="text" name="sobrante">
            </div>
            <div class="col-sm-12 col-md-6 p-2">
                <label class="text-center w-100" for="lena">Leña:</label><br>
                <input class="w-100 rounded rounded-pill text-center" type="text" name="lena" >
            </div>
        </div>
        <div class="col-sm-12 p-2 m-auto d-flex flex-wrap container-fluid">
            <div class="col-sm-12 col-md-4 p-2">
                <label class="text-center w-100 mr-2" for="alto">Alto Subpaqueta:</label>
                <input class="w-100 rounded rounded-pill text-center" type="number" name="alto">
            </div>
            <div class="col-sm-12 col-md-4 p-2">
                <label class="text-center w-100 mr-2" for="ancho">Ancho Subpaqueta:</label>
                <input class="w-100 rounded rounded-pill text-center" type="number" name="ancho">
            </div>
            <div class="col-sm-12 col-md-4 p-2">
                <label class="text-center w-100 mr-2" for="largo">Largo Subpaqueta:</label>
                <input class="w-100 rounded rounded-pill text-center" type="number" name="largo">
            </div>
            
        </div>
        <div class="col-sm-12 p-2 m-auto d-flex flex-wrap container-fluid">
        <button class="btn text-light rounded rounded-pill btn-warning w-100 col-sm-12">Guardar subpaqueta</button>
        </div>
    </div>
    <hr>
    <button class="btn text-light rounded rounded-pill btn-primary w-100 col-sm-12">Terminar orden</button>
</div>

@endsection

@section('js')
<script src="/js/modulos/alertas-swift.js"></script>
<script src="/js/modulos/procesos.js"></script>
<script src="/js/modulos/partials/eventos.js"></script>
@endsection
