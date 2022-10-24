@extends('layouts.web')

@section('submenu')
    @include('modulos.sidebars.costos-side')

@endsection

@section('content')
    <div class="text-center">

        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="img-fluid">
    </div>
@endsection
