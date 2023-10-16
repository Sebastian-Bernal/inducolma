@extends('layouts.web')
@section('title', ' Items | Inducolma')

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
                <!-- Modal modificar item-->
                <form action="{{ route('items.update', $item) }}" method="POST">
                    @csrf
                    @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Modificar item</h5>
                            <a href="{{ route('items.index') }}" type="button" class="btn-close"></a>
                            </div>
                            <div class="modal-body">

                                <div class="card-body">

                                    <div class="row mb-3">
                                        <label for="descripcion" class="col-md-4 col-form-label text-md-end">{{ __('Descripción') }}</label>
                                        <div class="col-md-6">
                                            <input id="descripcion"
                                                    type="text"
                                                    class="form-control @error('descripcion') is-invalid @enderror text-uppercase"

                                                    name="descripcion" value="{{ old('descripcion',$item->descripcion) }}"
                                                    required autocomplete="descripcion" autofocus
                                                    onkeyup="mayusculas()">

                                            @error('descripcion')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="alto" class="col-md-4 col-form-label text-md-end">{{ __('Alto') }}</label>
                                        <div class="col-md-6">
                                            <input id="alto"
                                                    type="number"
                                                    class="form-control @error('alto') is-invalid @enderror text-uppercase"
                                                    min="0.01"
                                                    step="0.01"
                                                    name="alto" value="{{ old('alto',$item->alto) }}"
                                                    required autocomplete="alto" autofocus
                                                    onkeyup="mayusculas()">

                                            @error('alto')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="largo" class="col-md-4 col-form-label text-md-end">{{ __('Largo') }}</label>
                                        <div class="col-md-6">
                                            <input id="largo"
                                                    type="number"
                                                    class="form-control @error('largo') is-invalid @enderror text-uppercase"
                                                    min="0.01"
                                                    step="0.01"
                                                    name="largo" value="{{ old('largo',$item->largo) }}"
                                                    required autocomplete="largo" autofocus
                                                    onkeyup="mayusculas()">

                                            @error('largo')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="ancho" class="col-md-4 col-form-label text-md-end">{{ __('Ancho') }}</label>
                                        <div class="col-md-6">
                                            <input id="ancho"
                                                    type="number"
                                                    class="form-control @error('ancho') is-invalid @enderror text-uppercase"
                                                    min="0.01"
                                                    step="0.01"
                                                    name="ancho" value="{{ old('ancho',$item->ancho) }}"
                                                    required autocomplete="ancho" autofocus
                                                    onkeyup="mayusculas()">

                                            @error('ancho')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="row mb-3">
                                        <label for="tipo_madera" class="col-md-4 col-form-label text-md-end">{{ __('Tipo de Madera') }}</label>
                                        <div class="col-md-6">
                                            <select id="tipo_madera"
                                                    class="form-control @error('tipo_madera') is-invalid @enderror text-uppercase"
                                                    name="tipo_madera" value="{{ old('tipo_madera') }}"
                                                    required
                                                    autofocus
                                                    >
                                                <option value="" selected>Seleccione...</option>
                                                @foreach ($tipos_maderas as $tipo_madera)
                                                    <option value="{{ $tipo_madera->id }}"
                                                        {{ $tipo_madera->id == $item->tipo_madera->id ? 'selected' : '' }}>{{ $tipo_madera->descripcion }}</option>
                                                @endforeach
                                            </select>

                                            @error('tipo_madera')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="codigo_cg" class="col-md-4 col-form-label text-md-end">{{ __('Código CG') }}</label>
                                        <div class="col-md-6">
                                            <input id="codigo_cg"
                                                    type="number"
                                                    class="form-control @error('codigo_cg') is-invalid @enderror text-uppercase"
                                                    name="codigo_cg" value="{{ old('codigo_cg', $item->codigo_cg) }}"
                                                    required autocomplete="codigo_cg" autofocus
                                                    onkeyup="mayusculas()">

                                            @error('codigo_cg')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>




                            </div>


                            </div>
                            <div class="modal-footer">
                            <a href="{{ route('items.index') }}" type="button" class="btn btn-secondary">Cerrar</a>
                            <button type="submit" class="btn btn-primary">Modificar item</button>
                            </div>
                        </div>

                </form>
            </div>

        </div>
    </div>

@endsection

@section('js')
<script src="/js/modulos/items.js"></script>
@endsection
