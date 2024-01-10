@extends('layouts.web')

@section('title', 'Editar costo de  infraestructura | Inducolma')

@section('content')
<form action="{{ route('costos-de-infraestructura.update', $estandarUnidadesMinuto->id) }}" method="POST" >
    @csrf
    @method('PATCH')


        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Editar costo de infraestructura</h5>
            <a href="{{ route('costos-de-infraestructura.index') }}" class="btn-close" ></a>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <span class="input-group-text">Maquina:</span>
                    <select class="form-select form-select-sm"
                            aria-label=".form-select-sm example"
                            name="maquina"
                            id="idMaquina"
                            required>
                            <OPtion value="" selected>Seleccione una maquina...</OPtion>
                        @foreach ($maquinas as $maquina)
                            <option value="{{ $maquina->id }}" {{ $estandarUnidadesMinuto->maquina_id == $maquina->id ? 'selected' : '' }} >{{ $maquina->maquina }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">Tipo material:</span>
                    <select
                        class="form-select form-select-sm"
                        aria-label=".form-select-sm example"
                        name="tipo_material"
                        id="tipoMaterial"
                        required>
                        <option value="" selected>Seleccione un tipo de material...</option>
                        <option value="BASTIDORES">BASTIDORES {{ $estandarUnidadesMinuto->tipo_material == 'BASTIDORES' ? 'selected' : '' }}</option>
                        <option value="BLOQUES" {{ $estandarUnidadesMinuto->tipo_material == 'BASTIDORES' ? 'selected' : '' }}>BLOQUES</option>
                        <option value="TABLAS" {{ $estandarUnidadesMinuto->tipo_material == 'BLOQUES' ? 'selected' : '' }}>TABLAS</option>
                        <option value="CUARTONES" {{ $estandarUnidadesMinuto->tipo_material == 'BASTIDORES' ? 'selected' : '' }}>CUARTONES</option>
                        <option value="TACOS" {{ $estandarUnidadesMinuto->tipo_material == 'CUARTONES' ? 'selected' : '' }}>TACOS</option>
                        <option value="PUNTAS" {{ $estandarUnidadesMinuto->tipo_material == 'PUNTAS' ? 'selected' : '' }}>PUNTAS</option>
                        <option value="ESTIBAS" {{ $estandarUnidadesMinuto->tipo_material == 'ESTIBAS' ? 'selected' : '' }}>ESTIBAS</option>
                        <option value="TROZA" {{ $estandarUnidadesMinuto->tipo_material == 'TROZA' ? 'selected' : '' }}>TROZA</option>

                    </select>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text">Tipo madera:</span>
                    <select class="form-select form-select-sm"
                            aria-label=".form-select-sm example"
                            name="tipo_madera"
                            id="tipoMadera"
                            required>
                        <option value="" selected>Seleccione una madera...</option>
                        @foreach ($maderas as $madera)
                            <option value="{{ $madera->id }}"
                                {{ $estandarUnidadesMinuto->tipo_madera == $madera->id ? 'selected' : ''}}
                                >{{ $madera->descripcion }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">Estandar unidades por minuto:</span>
                    <input type="number"
                            class="form-control"
                            min="1"
                            value="{{ $estandarUnidadesMinuto->estandar_u_minuto }}"
                            step="0.1"
                            name="unidades_minuto"
                            id="estandarUnidadesMinuto"
                            placeholder="Ej. 10"
                            required>
                </div>



            </div>
            @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    - {{ $error }} <br>
                @endforeach
            </div>

        @endif
            <div class="modal-footer">
            <a href="{{ route('costos-de-infraestructura.index') }}" class="btn btn-secondary" >Volver</a>
            <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </div>
        </div>

</form>
@endsection
