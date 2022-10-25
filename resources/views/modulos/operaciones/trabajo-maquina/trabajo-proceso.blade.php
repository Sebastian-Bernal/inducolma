@extends('layouts.web')
@section('title', ' Trabajo proceso | Inducolma')

@section('submenu')
@include('modulos.sidebars.costos-side')
@endsection
@section('content')
<div class="div container h-content m-auto">
    <h1 class="text-primary m-auto">Orden de Producción No. {{ $trabajo_maquina->orden_produccion_id }} - Item a Fabricar: {{ $trabajo_maquina->item->descripcion }}</h1>

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
    <div class="col-sm-12 bg-ligth border border-4 border-secondary shadow  min-vh-100 m-auto rounded round-3">


    </div>
    <hr>

</div>

@endsection

@section('js')
<script src="/js/modulos/alertas-swift.js"></script>
<script src="/js/modulos/procesos.js"></script>
<script src="/js/modulos/partials/eventos.js"></script>
@endsection
