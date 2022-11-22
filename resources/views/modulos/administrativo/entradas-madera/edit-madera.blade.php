@extends('layouts.web')
@section('title', ' Costo Madera | Inducolma')

@section('submenu')
@include('modulos.sidebars.costos-side')
@endsection
@section('content')
<div class="div container h-content ">
    <div class="row">
        <div class="col-12 col-sm-10 col-lg-6 mx-auto">



            <form action="{{ route('actualizar-costo',$entrada) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modificar costo de la entrada</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="card-body">

                            <div class="row mb-3">
                                <p>
                                    <span class="fw-bolder">Id:</span> {{ $entrada->id }} <br>
                                    <span class="fw-bolder">Viaje:</span> {{ $entrada->entrada_madera_id }} <br>
                                    <span class="fw-bolder">Proveedor:</span> {{ $entrada->entrada_madera->proveedor->nombre }} <br>
                                    <span class="fw-bolder">Tipo de madera:</span> {{ $entrada->madera->tipo_madera->descripcion }} <br>
                                    <span class="fw-bolder">Condicion:</span> {{ $entrada->condicion_madera }} <br>
                                    <span class="fw-bolder">m3:</span> {{ $entrada->m3entrada }} <br>
                                </p>

                            </div>

                            <div class="row mb-3">
                                <label for="costo" class="col-md-4 col-form-label text-md-end">{{ __('Precio de compra') }}</label>

                                <div class="col-md-6">
                                    <input id="costo"
                                            type="number"
                                            min="0.1"
                                            step="0.1"
                                            class="form-control @error('costo') is-invalid @enderror text-uppercase"
                                            name="costo"
                                            required
                                            autocomplete="costo"
                                            autofocus>

                                    @error('costo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="medida" class="col-md-4 col-form-label text-md-end">{{ __('unidad de medida')
                                    }}</label>
                                <div class="col-md-6">
                                    <select class="form-select" name="medida" required>
                                        <option value="" selected>Seleccione...</option>
                                        <option value="CENTIMETROS CUBICOS">CENTIMETROS CUBICOS</option>
                                        <option value="METRO CUBICO">METRO CUBICO</option>
                                        <option value="PULGADA CUADRADA POR 3 METROS">PULGADA CUADRADA POR 3 METROS</option>
                                    </select>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('costo-madera') }}" type="button" class="btn btn-secondary">Volver</a>
                        <button type="submit" class="btn btn-primary">Modificar costo madera</button>
                    </div>
                </div>

            </form>
        </div>
        <!-- Tabla -->


    </div>
</div>

@endsection

@section('js')
<script src="/js/modulos/maderas.js"></script>
@endsection
