@extends('layouts.web')
@section('title', ' Trabajo proceso | Inducolma')

@section('submenu')
@include('modulos.sidebars.costos-side')
@endsection
@section('content')
<div class="div container h-content m-auto">
    <h1 class="text-primary text-center m-auto">Orden de Producción No. {{ $trabajo_maquina->orden_produccion_id }}     --     Item a Fabricar: {{ $trabajo_maquina->item->descripcion }}</h1>
    <h3 class="text-secondary text-center">Viaje No. {{ $trabajo_maquina->cubicaje->entrada_madera_id }}  --  Paqueta No. {{ $trabajo_maquina->cubicaje->paqueta }} -- Cantidad items: {{ $trabajo_maquina->cantidad_items }}</h3>
    <h3 class="text-dark text-center">Observaciones: {{ $trabajo_maquina->observacion }}</h3>

    <div class="d-flex flex-wrap row  m-auto align-items-center container-fluid ">
        <div class="container col-xl-6 col-lg-6 col-md-6 col-sm-12 border border-4 border-warning shadow rounded-3 rounded pt-3 mb-2 mt-2">
            <span class="text-warning fw-bold">OPERARIOS EN ESTA MAQUINA:</span><br>
            @forelse ($turno_usuarios as $turno)
                <span> * {{ $turno->user->name }}</span><br>
            @empty
                <span>No se encontraron operarios</span>
            @endforelse

        </div>
        <div class="self-end col-md-6 col-sm-12 col-12">
            @include('modulos.partials.eventos')
        </div>
    </div>
    <div class="col-sm-12 bg-ligth border border-4 border-secondary shadow p-3 m-auto rounded round-3">
        <form class="row g-3" id="formSubpaqueta"  action="{{ route('subprocesos.store') }}" method="POST">
            @csrf
            {{-- inputs hidden --}}
            <input type="hidden" name="procesoId" value="{{ $trabajo_maquina->id }}">
            <input type="hidden" name="maquinaId" value="{{ $trabajo_maquina->maquina_id }}">
            <input type="hidden" name="paqueta" value="{{ $trabajo_maquina->cubicaje->paqueta }}">
            <input type="hidden" name="terminar" value="" id="terminar">

            <div class="col-md-6">
                <label for="itemEntrante" class="form-label">Item entrante: </label>
                <input type="text"
                        class="form-control"
                        id="itemEntrante"
                        name="itemEntrante"
                        value="{{ $trabajo_maquina->entrada }}"
                        readonly>
            </div>
            <div class="col-md-6">
                <label for="itemSaliente" class="form-label">Item saliente: </label>
                <input type="text"
                        class="form-control"
                        id="itemSaliente"
                        name="itemSaliente"
                        value="{{ $trabajo_maquina->salida }}"
                        readonly>
            </div>

            <div class="col-md-6">
                <label for="cm3Entrada" class="form-label">cm3 entrada:</label>
                <input type="text"
                        class="form-control"
                        id="cm3Entrada"
                        name="cm3Entrada"
                        value="{{ $trabajo_maquina->cm3_entrada }}"
                        readonly>
            </div>
            <div class="col-md-6" hidden>
                <label for="cm3Salida" class="form-label">cm3 salida:</label>
                <input type="number"
                        class="form-control @error('cm3Salida') is-invalid @enderror text-uppercase"
                        id="cm3Salida"
                        name="cm3Salida"
                        step="0.01"
                        readonly>
                @error('cm3Salida')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="col-md-6" hidden>
                <label for="camtidadEntrada" class="form-label">Cantidad entrada: </label>
                <input type="text"
                        class="form-control"
                        id="camtidadEntrada"
                        name="cantidadEntrada"
                        value="{{ $trabajo_maquina->cantidad_items }}"
                        readonly>
            </div>
            <div class="col-md-6">
                <label for="cantidadSalida" class="form-label">Cantidad salida: </label>
                <input type="number"
                    class="form-control @error('cantidadSalida') is-invalid @enderror text-uppercase"
                    id="cantidadSalida"
                    name="cantidadSalida"
                    onchange="salidaCero(this.value)"
                    step="1">
                @error('cantidadSalida')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="tarjetaEntrada" class="form-label">Tarjeta entrada: </label>
                <input type="text"
                        class="form-control"
                        id="tarjetaEntrada"
                        name="tarjetaEntrada">
            </div>
            <div class="col-md-6">
                <label for="tarjetaSalida" class="form-label">Tarjeta salida: </label>
                <input type="text"
                        class="form-control"
                        id="tarjetaSalida"
                        name="tarjetaSalida">
            </div>

            <div class="col-md-6">
                <label for="sobrante" class="form-label">Sobrante: </label>
                <input type="number"
                        class="form-control @error('sobrante') is-invalid @enderror text-uppercase"
                        id="sobrante"
                        name="sobrante"
                        step="1">
                @error('sobrante')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="lena" class="form-label">Leña: </label>
                <input type="number"
                        class="form-control @error('lena') is-invalid @enderror text-uppercase"
                        id="lena"
                        step="1"
                        name="lena">
                @error('lena')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="alto" class="form-label">Alto subpaqueta: </label>
                <input type="number"
                        class="form-control @error('lena') is-invalid @enderror text-uppercase"
                        id="alto"
                        name="alto"
                        step="0.1"
                        onchange="calcularCm3()">
                @error('lena')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="ancho" class="form-label">Ancho subpaqueta: </label>
                <input type="number"
                        class="form-control @error('ancho') is-invalid @enderror text-uppercase"
                        id="ancho"
                        name="ancho"
                        step="0.1"
                        onchange="calcularCm3()">
                @error('ancho')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="largo" class="form-label">Largo subpaqueta: </label>
                <input type="number"
                        class="form-control @error('largo') is-invalid @enderror text-uppercase"
                        id="largo"
                        name="largo"
                        step="0.1"
                        onchange="calcularCm3()">
                @error('largo')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-floating">
                <textarea class="form-control text-uppercase"
                            placeholder="Leave a comment here"
                            id="observacionSubpaqueta"
                            name="observacionSubpaqueta"
                            style="height: 100px"></textarea>
                <label for="floatingTextarea2">Observaciones de subpaqueta</label>
            </div>

            <div class="col-sm-12 p-2 m-auto d-flex flex-wrap container-fluid">
                <button type="button"
                        class="btn text-light rounded rounded-pill btn-warning w-100 col-sm-12"
                        onclick="guardarSubpaqueta()">Guardar subpaqueta</button>
            </div>
        </form>


    </div>
    <hr>
    <button class="btn text-light rounded rounded-pill btn-primary w-100 col-sm-12 mb-4"
                onclick="terminarOrden()">Terminar orden</button>
</div>

@endsection

@section('js')
<script src="/js/modulos/alertas-swift.js"></script>
<script src="/js/modulos/procesos.js"></script>
<script src="/js/modulos/partials/eventos.js"></script>
@endsection
