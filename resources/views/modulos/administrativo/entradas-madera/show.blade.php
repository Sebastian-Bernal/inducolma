@extends('layouts.web')
@section('title', ' Entradas entrada | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Entrada de madera</h5>
                </div>
                <div class="modal-body m-3">

                    <div class="row g-3" id="formEntradaMaderas">

                        <div class="col-md-4">
                            <div class="row mb-3">
                                <label for="mes" class="col-md-3 col-form-label text-md-end px-0 pt-7 pb-7 ">{{ __('Mes') }}</label>
                                <div class="col-md-8">
                                    <select name="mes" id="mes" class="form-control @error('mes') is-invalid @enderror" required autocomplete="mes" autofocus>

                                        <option value="{{ $entrada->mes }}" selected>{{ $entrada->mes }}</option>
                                        <hr>

                                    </select>
                                    @error('mes')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="row mb-3">
                                <label for="ano" class="col-md-3 col-form-label text-md-end px-0 pt-7 pb-7">{{ __('Año') }}</label>
                                <div class="col-md-8">
                                    <select name="ano" id="ano" class="form-control @error('ano') is-invalid @enderror" required autocomplete="ano" autofocus>
                                        <option value="{{ $entrada->ano }}" selected>{{ $entrada->ano }}</option>

                                    </select>
                                    @error('ano')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="row mb-3">
                                <label for="hora" class="col-md-3 col-form-label text-md-end px-0 pt-7 pb-7">{{ __('Hora') }}</label>
                                <div class="col-md-8">
                                    <input type="time"
                                            name="hora"
                                            id="hora"
                                            class="form-control @error('hora') is-invalid @enderror"
                                            required
                                            autocomplete="hora"
                                            autofocus
                                            value="{{ $entrada->hora }}"
                                            >
                                    @error('hora')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="row mb-3">
                                <label for="fecha" class="col-md-3 col-form-label text-md-end px-0 pt-7 pb-7">{{ __('Fecha') }}</label>
                                <div class="col-md-8">
                                    <input type="date"
                                            name="fecha"
                                            id="fecha"
                                            class="form-control @error('fecha') is-invalid @enderror"
                                            required
                                            autocomplete="fecha"
                                            autofocus
                                            value="{{ $entrada->fecha }}">
                                    @error('fecha')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="row mb-3">
                                <label for="actoAdministrativo" class="col-md-3 col-form-label text-md-center px-0 pt-0 pb-7">{{ __('Acto administrativo') }}</label>
                                <div class="col-md-8">
                                    <input  type="text"
                                            name="actoAdministrativo"
                                            id="actoAdministrativo"
                                            class="form-control @error('actoAdministrativo') is-invalid @enderror text-uppercase"
                                            required
                                            autocomplete="actoAdministrativo"
                                            value="{{ $entrada->acto_administrativo }}">
                                    @error('actoAdministrativo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="row mb-3">
                                <label for="salvoconducto" class="col-md-3 col-form-label text-md-center px-0 pt-0 pb-7">{{ __('Salvoconducto remision') }}</label>
                                <div class="col-md-8">
                                    <input type="number"
                                            name="salvoconducto"
                                            step="0.1" id="salvoconducto"
                                            class="form-control @error('salvoconducto') is-invalid @enderror text-uppercase"
                                            required
                                            autocomplete="salvoconducto"
                                            autofocus
                                            value="{{ $entrada->salvoconducto_remision }}">
                                    @error('salvoconducto')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="row mb-3">
                                <label for="titularSalvoconducto" class="col-md-3 col-form-label text-md-center px-0 pt-0 pb-7">{{ __('Titular salvoconducto') }}</label>
                                <div class="col-md-8">
                                    <input type="text"
                                            name="titularSalvoconducto"
                                            id="titularSalvoconducto"
                                            class="form-control @error('titularSalvoconducto') is-invalid @enderror text-uppercase"
                                            required
                                            autocomplete="titularSalvoconducto"
                                            autofocus
                                            value="{{ $entrada->titular_salvoconducto }}">
                                    @error('titularSalvoconducto')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="row mb-3">
                                <label for="procedencia" class="col-md-3 col-form-label text-md-center px-0 pt-0 pb-7">{{ __('Procedencia de la madera') }}</label>
                                <div class="col-md-8">
                                    <input type="text"
                                            name="procedencia"
                                            id="procedencia"
                                            class="form-control @error('procedencia') is-invalid @enderror text-uppercase"
                                            required
                                            autocomplete="procedencia"
                                            autofocus
                                            value="{{ $entrada->procedencia_madera }}">
                                    @error('procedencia')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="row mb-3">
                                <label for="entidadVigilante" class="col-md-3 col-form-label text-md-center px-0 pt-0 pb-7">{{ __('Entidad vigilante') }}</label>
                                <div class="col-md-8">
                                    <input type="text"
                                            name="entidadVigilante"
                                            id="entidadVigilante"
                                            class="form-control @error('entidadVigilante') is-invalid @enderror text-uppercase"
                                            required
                                            autocomplete="entidadVigilante"
                                            autofocus
                                            value="{{ $entrada->entidad_vigilante }}">
                                    @error('entidadVigilante')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="row mb-3">
                                <label for="proveedor" class="col-md-3 col-form-label text-md-end px-0 pt-7 pb-7">{{ __('Proveedor') }}</label>
                                <div class="col-md-8">
                                    <select name="proveedor" id="proveedor" class="form-control @error('proveedor') is-invalid @enderror" required autocomplete="proveedor" autofocus>
                                        @foreach ($proveedores as $proveedor)
                                            <option value="{{ $proveedor->id }}"
                                                {{ $entrada->proveedor->pluck('id')->contains($proveedor->id) ? 'selected' : ''}}>
                                                {{ $proveedor->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('proveedor')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>



                    </div>
                    <div class="row g-3" id="formMaderas">
                        {{-- <div class="col-md-4">
                            <div class="row mb-3">
                                <label for="madera" class="col-md-2 col-form-label text-md-end px-0 pt-7 pb-7">{{ __('Madera') }}</label>
                                <div class="col-md-8">
                                    <select name="madera" id="madera" class="form-control @error('madera') is-invalid @enderror"  autocomplete="madera" required autofocus>
                                        <option value="" selected>Seleccione...</option>
                                        @foreach ($maderas as $madera)
                                        <option value="{{ $madera->id }}">{{ $madera->nombre }}</option>
                                    @endforeach
                                    </select>
                                    @error('madera')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="row mb-3">
                                <label for="condicionMadera" class="col-md-3 col-form-label text-md-center px-0 pt-0 pb-0">{{ __('Condicion madera') }}</label>
                                <div class="col-md-8">
                                    <select name="condicionMadera" id="condicionMadera" class="form-control @error('condicionMadera') is-invalid @enderror" required autocomplete="condicionMadera" autofocus>
                                        <option value="" selected>Seleccione..</option>
                                        <option value="Bloque">Bloque</option>
                                        <option value="otro">otro</option>
                                    </select>
                                    @error('condicionMadera')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="row mb-3">
                                <label for="m3entrada" class="col-md-3 col-form-label text-md-center px-0 pt-0 pb-7">{{ __('Metros cubicos de entrada') }}</label>
                                <div class="col-md-8">
                                    <input type="number" name="m3entrada" step="0.1" id="m3entrada" class="form-control @error('m3entrada') is-invalid @enderror" required autocomplete="m3entrada" autofocus>
                                    @error('m3entrada')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}



                        <div class="col-md-12">
                            <table  class="table table-bordered table-striped dt-responsive">
                                <thead>
                                    <th>Madera</th>
                                    <th>Condicion madera</th>
                                    <th>Metros cubicos</th>

                                </thead>
                                <tbody id="listaMaderas">

                                    @foreach ($entrada->entradas_madera_maderas	 as $entrada_madera)
                                        <tr>
                                            <td>{{ $entrada_madera->madera->nombre_cientifico }}</td>
                                            <td>{{ $entrada_madera->condicion_madera }}</td>
                                            <td>{{ $entrada_madera->m3entrada }}</td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <a href="{{ route('entradas-maderas.index') }}" class="btn btn-secondary" >Volver</a>
                    @can('update')
                        <button type="button" class="btn btn-primary" onclick="validarCampos()">Modificar entrada</button>
                    @endcan

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
