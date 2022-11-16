@extends('layouts.web')
@section('title', ' Transformacion de trozas | Inducolma')

@section('submenu')
@include('modulos.sidebars.costos-side')
@endsection
@section('content')
<div class="div container h-content ">
    <input type="hidden" id="userId" value="{{ Auth::user()->id }}">
    <div class="row">
        <div class="col-12  mx-auto ">
            <div class="col-12 text-primary">

                <h1 class="text-center">Transformacion de trozas</h1>
                
            </div>
            <hr>
            <div class="col-12 col-md-12 text-secondary">
                <h3 class="text-center fw-bolder">Entrada a transformar No.</h3>
                <div class="col-md-6 col-12 mx-auto">
                <input type="number" class="form-control" id="entrada" name="entrada" step="0.1">
                </div>
            </div>
            
            <div class="col-12 mx-auto bg-primary pb-1 pt-2 mt-3 rounded-3 text-white">
                <h2 class="text-center">Transformación del Bloque principal</h2>
            </div>
            <div class="col-12 col-md-12 mt-3 text-secondary">
                <h3 class="text-center fw-bolder">Transformación del bloque No. {{-- numero del bloque --}}</h3>
                
            </div>
            <form class=" row g-3 mt-3" id="agregarCubicaje">

                <div class="col-md-6 col-12">
                    <label for="alto" class="form-label">Alto</label>
                    <input type="number" class="form-control" id="alto" name="alto" step="0.1">
                </div>
                <div class="col-md-6 col-12">
                    <label for="ancho" class="form-label">Ancho</label>
                    <input type="number" class="form-control" id="ancho">
                </div>
            </form>
            <div class="col-12 mx-auto bg-warning pb-1 pt-2 mt-5 rounded-3 text-white">
                <h2 class="text-center">Transformación del material sobrante</h2>
            </div>
            
            <form class=" row g-3 mt-3" id="agregarCubicaje">
                <div class="col-md-4 col-12">
                    <label for="largo" class="form-label">Largo</label>
                    <input type="number" class="form-control" id="largo" name="largo" step="0.1">
                </div>
                <div class="col-md-4 col-12">
                    <label for="alto" class="form-label">Alto</label>
                    <input type="number" class="form-control" id="alto" name="alto" step="0.1">
                </div>
                <div class="col-md-4 col-12">
                    <label for="ancho" class="form-label">Ancho</label>
                    <input type="number" class="form-control" id="ancho">
                </div>
            </form>
            <div class="col-12 col-md-12 mt-4 text-secondary">
                <div class="text-center text-warning">
                    <h2>Transformaciones por guardar</h2>
                    <br>                 
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Entrada</th>
                            <th scope="col">Bloque No.</th>
                            <th scope="col">Alto</th>
                            <th scope="col">Ancho</th>
                            <th scope="col">Largo</th>
                        </tr>
                    </thead>
                    <tbody id="listarTransformaciones">
    
                    </tbody>
                </table>
            </div>
            <div>
                <button type="button" class="btn btn-secondary container-fluid" onclick="GuardarTransformaciones()">Guardar ruta de
                    Transformaciones</button>
            </div>
        </div>
    </div>    
        
</div>

@endsection

@section('js')
<script src="/js/modulos/transforma_troza.js"></script>

@endsection
