@extends('layouts.web')
@section('title', ' Clientes | Inducolma')

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

                <div class="card">
                <h5 class="card-header bg-primary text-white">Reporte ingreso de maderas</h5>
                <div class="card-body">
                    {{-- <h5 class="card-title">Special title treatment</h5> --}}
                    <p class="card-text text-secondary">
                        Puede generar un reporte por fecha del ingreso de maderas, Ej. Desde : 01/01/2022 - Hasta: 31/12/2022
                    </p>
                    <div class="row g-3">
                        <div class="col-auto">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-default">Desde: </span>
                                <input type="date" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-default">Hasta: </span>
                                <input type="date" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                            </div>
                        </div>

                    </div>
                    <a href="#" class="btn btn-primary">Go somewhere</a>
                </div>
                </div>

            {{-- </div> --}}
        </div>
    </div>

@endsection

@section('js')
<script src="/js/modulos/clientes.js"></script>
@endsection
