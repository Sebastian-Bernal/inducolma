@extends('layouts.web')
@section('title', ' Trabajo proceso | Inducolma')

@section('submenu')
@include('modulos.sidebars.costos-side')
@endsection
@section('content')
<div class="div container h-content m-auto">
    <h1 class="text-primary m-auto">Orden de Producción No. {{ $trabajo_maquina->orden_produccion_id }} - Item a Fabricar: Nombre del item</h1>

    <div class="d-flex flex-wrap row  m-auto align-items-center container-fluid ">
        <div class="container col-xl-6 col-lg-6 col-md-6 col-sm-12 border border-4 border-warning shadow rounded-3 rounded pt-3 mb-2 mt-2 overflow-scroll">
            <h3 class="text-warning">OPERARIOS EN ESTA MAQUINA:</h3>
            <p> * nombre del operario</p>
            <p> * nombre del operario</p>
        </div>
        <div class="self-end col-md-6 col-sm-12 col-12">
            <button class="btn btn-primary text-light mt-2 mb-2 w-100 rounded-pill" type="button"
                data-bs-toggle="collapse" data-bs-target="#eventoMaquina" aria-expanded="false"
                aria-controls="eventoMaquina" id="eventosDeMaquina">
                EVENTOS DE LA MAQUINA
            </button>
            <div class="collapse " id="eventoMaquina">
                <div class="card card-body border border-primary border-4 rounded-3">
                    {{--  @forelse ($tipos_evento as $tipo_evento)
                    <button type="button" class="btn w-80 btn-primary text-light m-2"
                        id="{{ 'tipoEvento'.$tipo_evento->id }}"
                        onclick="listarEventos({{ $tipo_evento->id .','. $eventos }})">
                        {{ $tipo_evento->tipo_evento }}
                    </button>
                    @empty
                    <span>Ningun tipo de evento encontrado, por favor agrege uno</span>
                    @endforelse  --}}


                </div>
            </div>
        </div>

        {{ $trabajo_maquina }}
      

    </div>
    <div class="col-sm-12 bg-ligth border border-4 border-secondary shadow  min-vh-100 m-auto rounded round-3">
          

    </div>
    <hr>

</div>

@endsection

@section('js')
<script src="/js/modulos/procesos.js"></script>
@endsection
