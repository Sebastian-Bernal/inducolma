@extends('layouts.web')
@section('title', ' Turnos | Inducolma')

@section('submenu')
@include('modulos.sidebars.costos-side')
@endsection
@section('content')
<div class="div container h-content ">
    <div class="row">
        <div class="col-12 col-sm-10 col-lg-6 mx-auto">


            <h1 class="display-6">Turnos</h1>
            <hr>
            <!-- Button trigger modal -->

            @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                - {{ $error }} <br>
                @endforeach
            </div>

            @endif
            <!-- Modal Crea tipo de madera-->
            <form action="{{ route('turnos.update', $turno) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Actualizar turno</h5>
                            <a href="{{ route('turnos.index') }}" class="btn-close"
                                aria-label="Close"></a>
                        </div>
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <label for="turno" class="col-md-4 col-form-label text-md-end">{{ __('Descripción del turno') }}</label>
                                    <div class="col-md-6">
                                        <input id="turno" type="text"
                                            class="form-control @error('turno') is-invalid @enderror text-uppercase"
                                            name="turno" value="{{ old('turno', $turno->turno) }}" required
                                            autocomplete="turno" autofocus onkeyup="mayusculas()">
                                        @error('turno')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="hora_inicio" class="col-md-4 col-form-label text-md-end">{{ __('Hora inicio') }}</label>
                                    <div class="col-md-6">
                                        <input id="hora_inicio" type="time"
                                            class="form-control @error('hora_inicio') is-invalid @enderror text-uppercase"
                                            name="hora_inicio" value="{{ old('hora_inicio', $turno->hora_inicio) }}" required
                                            autocomplete="hora_inicio" autofocus onkeyup="mayusculas()">
                                        @error('hora_inicio')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="hora_fin" class="col-md-4 col-form-label text-md-end">{{ __('Hora fin') }}</label>
                                    <div class="col-md-6">
                                        <input id="hora_fin" type="time"
                                            class="form-control @error('hora_fin') is-invalid @enderror text-uppercase"
                                            name="hora_fin" value="{{ old('hora_fin', $turno->hora_fin) }}" required
                                            autocomplete="hora_fin" autofocus onkeyup="mayusculas()">
                                        @error('hora_fin')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="estado" class="col-md-4 col-form-label text-md-end">{{ __('Estado') }}</label>
                                    <div class="col-md-6">
                                        <select name="estado" id="estado" class="form-control">
                                            <option value="DISPONIBLE" {{ $turno->estado == 'DISPONIBLE' ? 'selected' : '' }}>DISPONIBLE</option>
                                            <option value="NO DISPONIBLE" {{ $turno->estado == 'NO DISPONIBLE' ? 'selected' : '' }}>NO DISPONIBLE</option>
                                        </select>
                                        @error('estado')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="{{ route('turnos.index') }}" class="btn btn-secondary">Volver</a>
                            <button type="submit" class="btn btn-primary">Actualizar turno</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <hr>

</div>

@endsection

@section('js')
<script src="/js/modulos/turnos.js"></script>
@endsection
