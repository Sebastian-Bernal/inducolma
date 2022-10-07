@extends('layouts.web')
@section('title', ' Turnos | Inducolma')

@section('submenu')
@include('modulos.sidebars.costos-side')
@endsection
@section('content')
<div class="div container h-content ">
    <div class="row">
        <div class="col-12 col-sm-10 col-lg-6 mx-auto">


            <h1 class="display-6">Asignar turnos a empleado</h1>
            <hr>

            @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                - {{ $error }} <br>
                @endforeach
            </div>

            @endif
            <!-- Modal Crea tipo de madera-->
            <form action="{{ route('asignar-turnos.update', $asignar_turno) }}" method="POST" id="formAsignarTurno">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Asignar turno</h5>
                        <a class="btn-close" aria-label="Close" href="{{ route('asignar-turnos.index') }}"></a>
                    </div>
                    <div class="modal-body">
                        <div class="card-body">
                            <div class="row mb-3">
                                <label for="usuario" class="col-md-4 col-form-label text-md-end">{{ __('Empleado') }}</label>
                                <div class="col-md-6">
                                    <select name="usuario" id="usuario" class="form-control">
                                        <option value="{{ $asignar_turno->user_id }}" selected>{{ $asignar_turno->user->name }}</option>
                                    </select>
                                    @error('usuario')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="turno" class="col-md-4 col-form-label text-md-end">{{ __('Turno') }}</label>
                                <div class="col-md-6">
                                    <select name="turno" id="turno" class="form-control ">
                                        @forelse ($turnos as $turno)
                                            <option value="{{ $turno->id }}" {{ $turno->id == $asignar_turno->turno->id ? 'selected' : '' }}>{{ $turno->turno }}</option>
                                        @empty
                                            <option value="">No se encontraron turnos</option>
                                        @endforelse
                                    </select>
                                    @error('turno')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="maquina" class="col-md-4 col-form-label text-md-end">{{ __('Maquina') }}</label>
                                <div class="col-md-6">
                                    <select name="maquina" id="maquina" class="form-control ">
                                        @forelse ($maquinas as $maquina)
                                            <option value="{{ $maquina->id }}" {{ $asignar_turno->maquina->id == $turno->maquina_id ? 'selected' : '' }}>{{ $maquina->maquina }}</option>
                                        @empty
                                            <option value="">No se encontraron maquinas</option>
                                        @endforelse
                                    </select>
                                    @error('maquina')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="fecha" class="col-md-4 col-form-label text-md-end">{{ __('desde') }}</label>
                                <div class="col-md-6">
                                    <input id="desde" type="date"
                                        class="form-control @error('desde') is-invalid @enderror text-uppercase"
                                        name="desde" value="{{ old('desde', $asignar_turno->fecha) }}"
                                        min='{{ date('Y-m-d', strtotime('1 days')) }}'
                                        required
                                        readonly
                                        autocomplete="desde" autofocus onkeyup="mayusculas()">
                                    @error('desde')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-secondary" href="{{ route('asignar-turnos.index') }}">volver</a>
                        <button type="submit" class="btn btn-primary">Modificar turno asignado</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
    <hr>

</div>

@endsection

@section('js')
<script src="/js/modulos/turnos-asignados.js"></script>
@endsection
