@extends('layouts.web')
@section('title', ' Usuarios | inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content')
<div class="div container h-content ">
    <div class="row">
        <div class="col-12 col-sm-10 col-lg-6 mx-auto">



            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        - {{ $error }} <br>
                    @endforeach
                </div>

            @endif
            <!-- Modal Crea maquina-->
            <form action="{{ route('usuarios.update',$usuario) }}" method="POST">
                @csrf
                @method('PATCH')

                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modificar Usuario</h5>

                        </div>
                        <div class="modal-body">

                            <div class="card-body">
                                <div class="row mb-3">
                                    <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Identificacion') }}</label>

                                    <div class="col-md-6">
                                        <input id="identificacionUsuario"
                                            type="text"
                                            class="form-control @error('identificacionUsuario') is-invalid @enderror"
                                            name="identificacionUsuario"
                                            value="{{ old('identificacionUsuario',$usuario->identificacion) }}"
                                            required
                                            autocomplete="identificacionUsuario"
                                            autofocus>

                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="primer_nombre" class="col-md-4 col-form-label text-md-end">{{ __('Primer nombre') }}</label>

                                    <div class="col-md-6">
                                        <input  id="primer_nombre"
                                                type="text"
                                                class="form-control @error('primer_nombre') is-invalid @enderror text-uppercase"
                                                name="primer_nombre"
                                                value="{{ old('primer_nombre',$usuario->primer_nombre) }}"
                                                required
                                                autocomplete="primer_nombre"
                                                autofocus>
                                        @error('primer_nombre')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="segundo_nombre" class="col-md-4 col-form-label text-md-end">{{ __('Segundo nombre') }}</label>

                                    <div class="col-md-6">
                                        <input  id="segundo_nombre"
                                                type="text"
                                                class="form-control @error('segundo_nombre') is-invalid @enderror text-uppercase"
                                                name="segundo_nombre"
                                                value="{{ old('segundo_nombre', $usuario->segundo_nombre) }}"

                                                autocomplete="segundo_nombre"
                                                autofocus>
                                        @error('segundo_nombre')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="primer_apellido" class="col-md-4 col-form-label text-md-end">{{ __('Primer apellido') }}</label>
                                    <div class="col-md-6">
                                        <input  id="primer_apellido"
                                                type="text"
                                                class="form-control @error('primer_apellido') is-invalid @enderror text-uppercase"
                                                name="primer_apellido"
                                                value="{{ old('primer_apellido',$usuario->primer_apellido) }}"
                                                required
                                                autocomplete="primer_apellido">

                                        @error('primer_apellido')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="segundo_apellido" class="col-md-4 col-form-label text-md-end">{{ __('Segundo apellido') }}</label>

                                    <div class="col-md-6">
                                        <input  id="segundo_apellido"
                                                type="text"
                                                class="form-control @error('segundo_apellido') is-invalid @enderror text-uppercase"
                                                name="segundo_apellido"
                                                value="{{ old('segundo_apellido', $usuario->segundo_apellido) }}"

                                                autocomplete="segundo_apellido">
                                        @error('segundo_apellido')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="row mb-3">
                                    <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email') }}</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email',$usuario->email) }}" required autocomplete="email">

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="row mb-3">
                                    <label for="rol" class="col-md-4 col-form-label text-md-end">{{ __('Rol') }}</label>
                                    <div class="col-md-6">
                                        <select class="form-select" name="rolUsuario" required >
                                            @foreach ($roles as $rol)
                                                <option value="{{ $rol->id }}"
                                                    @if ($rol->id == $usuario->rol_id)
                                                        {{ 'selected' }}
                                                    @endif
                                                >{{ $rol->nombre }}</option>
                                            @endforeach

                                        </select>
                                    </div>

                                </div>

                                <div class="row mb-3">
                                    <label for="pagoMesAnterior" class="col-md-4 col-form-label text-md-end">{{ __('Pago mes anterior') }}</label>
                                    <div class="col-md-6">
                                        <input id="pagoMesAnterior"
                                                type="number"
                                                class="form-control @error('pagoMesAnterior') is-invalid @enderror" name="pagoMesAnterior"
                                                value="{{ $usuario->pago_mes_anterior }}"
                                                min="0"
                                                max="10000000"

                                                autocomplete="pagoMesAnterior">

                                        @error('pagoMesAnterior')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="pagoEstandar" class="col-md-4 col-form-label text-md-end">{{ __('Pago estandar') }}</label>
                                    <div class="col-md-6">
                                        <input id="pagoEstandar"
                                                type="number"
                                                class="form-control @error('pagoEstandar') is-invalid @enderror" name="pagoEstandar"
                                                value="{{ $usuario->pago_estandar }}"
                                                min="100000"
                                                max="10000000"
                                                required
                                                autocomplete="pagoEstandar">

                                        @error('pagoEstandar')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                        </div>

                        </div>
                        <div class="modal-footer">
                        <a href="{{ route('usuarios.index') }}" type="button" class="btn btn-secondary">Volver</a>
                        <button type="submit" class="btn btn-primary">Actualizar usuario</button>
                        </div>
                    </div>
                    </div>

            </form>
        </div>

    </div>
</div>

@endsection

@section('js')
<script src="/js/modulos/usuarios.js"></script>
@endsection
