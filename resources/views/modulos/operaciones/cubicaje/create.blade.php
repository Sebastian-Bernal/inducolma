@extends('layouts.web')
@section('title', ' Entradas cubicaje | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content') 
<div class="div container h-content ">
    <input type="hidden" id="entradaId" value="{{ $entrada->id }}">        
    <div class="row"> 
        <div class="col-8 mb-3">
            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDetallesMadera" aria-expanded="false" aria-controls="collapseDetallesMadera">
                Ver detalles de la madera
            </button> 
        </div>
        <div class="collapse" id="collapseDetallesMadera">
            <div class="card card-body">
                <div class="card number-center border-success mb-3 ">
                    <div class="card-header">
                        <h4 class="  ">{{ 'ID de entrada: '. $entrada->id }}</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $entrada->acto_administrativo.' --- '. $entrada->proveedor->nombre }}</h5>
                        @foreach ($entrada->entradas_madera_maderas as $madera)
                            <p class="card-number">{!! 'nombre: <b>'.$madera->madera->nombre.',</b> condición: <b>'.$madera->condicion_madera.',</b> metros cubicos: <b>'. $madera->m3entrada.'</b>' !!}</p>
                        @endforeach
                        <a href="{{ route('cubicaje.index') }}" class="btn btn-secondary">volver</a>
                    </div>
                    <div class="card-footer number-muted">
                        {{ $entrada->created_at->diffForHumans() }}
                    </div>
                </div>  
            </div>
        </div>
             
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        - {{ $error }} <br>
                    @endforeach
                </div>
            @endif
            <div class="mb-5">
                <form class=" row g-3" id="agregarCubicaje">

                    <div class="col-md-4">
                        <label for="paqueta" class="form-label">Paqueta Nro.</label>
                        <input type="number" class="form-control" id="paqueta" name="paqueta">
                    </div>
                    <div class="col-md-4">
                        <label for="largo" class="form-label">Largo</label>
                        <input type="number" class="form-control" id="largo" name="largo" step="0.1">
                    </div>
                    
                    <div class="col-md-4">
                      <label for="alto" class="form-label">Alto</label>
                      <input type="number" class="form-control" id="alto" name="alto" step="0.1">
                    </div>
                    <div class="col-md-4">
                      <label for="ancho" class="form-label">Ancho</label>
                      <input type="number" class="form-control" id="ancho">
                    </div>
                    <div class="col-md-4">
                        <label for="pulgadas_alto" class="form-label">Pulgadas menos alto</label>
                        <input type="number" class="form-control" id="pulgadas_alto" value="0" step="0.1">
                    </div>
                    <div class="col-md-4">
                        <label for="pulgadas_ancho" class="form-label">Pulgadas menos ancho</label>
                        <input type="number" class="form-control" id="pulgadas_ancho" name="pulgadas_ancho" value="0" step="0.1">
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-primary" onclick="verificarInputs();">Agregar</button>
                    </div>
                </form>
            </div>
           
            
            
        <!-- Tabla -->
        <div>
            <table id="paquetas" class="table table-bordered table-striped dt-responsive">
                <thead>
                    <tr>
                        <th>Paqueta</th> 
                        <th>Bloque</th>  
                        <th>Largo</th>
                        <th>Alto</th>         
                        <th>Ancho</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
    
                <tbody id="listarPaquetas" >
                    
                </tbody>
            </table>
        </div>
        
    </div>
    <!--- Boton terminar paqueta -->
    <div class="d-grid gap-2 mt-3">
        <button class="btn btn-primary" type="button" id="terminarPaqueta" onclick="terminarPaqueta()" >Terminar Paqueta</button>
    </div>
</div>

@endsection

@section('js')
<script src="/js/modulos/cubicaje.js"></script>
@endsection