@extends('layouts.web')
@section('title', ' Trabajo proceso | Inducolma')

@section('submenu')
@include('modulos.sidebars.costos-side')
@endsection
@section('content')
<div class="div container h-content ">
    <div class="row">
        {{ $trabajo_maquina }}


    </div>
    <hr>

</div>

@endsection

@section('js')
<script src="/js/modulos/procesos.js"></script>
@endsection
