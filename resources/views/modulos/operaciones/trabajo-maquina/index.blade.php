@extends('layouts.web')
@section('title', ' Trabajo maquina | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content')
    <div class="div container h-content ">
        <div class="row">
            {{ $usuario }} <br>
            {{ $turno_usuarios }} <br>{{-- usuarios en el mismo turno del usuario que usa la aplicacion --}}
            {{ $maquinas }} <br>
            {{ $eventos }} <br>
        </div>
    </div>

@endsection

@section('js')
<script src="/js/modulos/.js"></script>
@endsection
