@extends('layouts.web')
@section('title', ' Ruta de programación | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content')
    <div class="div container h-content ">
        <div class="row">
            <h2 class="text-center text-primary"><strong>Ruta de Programación</strong></h2>
            <h4>
                <p class="text-center m-1">
                    <strong>Item a realizar: --------------</strong></br>
                    <label>Orden de pedido No.: ---- / Tipo de Madera: ---- </label>
                </p> 
            </h4>
            <div class="d-flex flex-wrap row-col-sm-1 row-col-md-2 container-fluid">
                <div class="container-sm col-sm-auto card  bg-light shadow m-3 rounded-3">
                    <div class=" card-header bg-primary text-white">
                        Corte Inicial
                    </div>
                    <div class=" card-body">
                        
                        <div class="text-center">
                            <label> Entrada de Madera </label>
                        </div>
                        <div class="input-group mb-3 mt-3">
                            
                            <label class="input-group-text" for="entraInicial">Entrada</label>
                            <select class="form-select" id="entraInicial">
                              <option selected>Choose...</option>
                              <option value="1">Bloque</option>
                              <option value="2">Punta</option>
                              <option value="3">Troza</option>
                            </select>
                          </div>
                          <div class="text-center">
                            <label> Salida de Madera </label>
                        </div>
                        <div class="input-group mb-3 mt-3">
                            
                            <label class="input-group-text" for="saleInicial">Salida</label>
                            <select class="form-select" id="saleInicial">
                              <option selected>Choose...</option>
                              <option value="1">Taco</option>
                              <option value="2">Punta</option>
                              <
                            </select>
                          </div>
                          <div class="text-center">
                            <label> Proceso </label>
                            
                        </div>
                        <div class="input-group mb-3 mt-3">
                            
                            <label class="input-group-text" for="maquinaInicial">Maquina</label>
                            <select class="form-select" id="maquinaInicial">
                              <option selected>Choose...</option>
                              <option value="1">One</option>
                              <option value="2">Two</option>
                              <option value="3">Three</option>
                            </select>
                          </div>
                          <div class="mb-3">
                            <label for="observacionInicial" class="form-label text-center">Observaciones</label>
                            <textarea class="form-control" id="observacionInicial" rows="3"></textarea>
                          </div>
                    </div>
                    
                    
                </div>
                <div class="container-sm col-sm-auto card  bg-light shadow m-3 rounded-3">
                    <div class=" card-header bg-warning text-white">
                        Corte Intermedio
                    </div>
                    <div class=" card-body">
                        
                        <div class="text-center">
                            <label> Entrada de Madera </label>
                        </div>
                        <div class="input-group mb-3 mt-3">
                            
                            <label class="input-group-text" for="entraInicial">Entrada</label>
                            <select class="form-select" id="entraInicial">
                              <option selected>Choose...</option>
                              <option value="1">Bloque</option>
                              <option value="2">Punta</option>
                              <option value="3">Troza</option>
                            </select>
                          </div>
                          <div class="text-center">
                            <label> Salida de Madera </label>
                        </div>
                        <div class="input-group mb-3 mt-3">
                            
                            <label class="input-group-text" for="saleInicial">Salida</label>
                            <select class="form-select" id="saleInicial">
                              <option selected>Choose...</option>
                              <option value="1">Taco</option>
                              <option value="2">Punta</option>
                              <
                            </select>
                          </div>
                          <div class="text-center">
                            <label> Proceso </label>
                            
                        </div>
                        <div class="input-group mb-3 mt-3">
                            
                            <label class="input-group-text" for="maquinaInicial">Maquina</label>
                            <select class="form-select" id="maquinaInicial">
                              <option selected>Choose...</option>
                              <option value="1">One</option>
                              <option value="2">Two</option>
                              <option value="3">Three</option>
                            </select>
                          </div>
                          <div class="mb-3">
                            <label for="observacionInicial" class="form-label text-center">Observaciones</label>
                            <textarea class="form-control" id="observacionInicial" rows="3"></textarea>
                          </div>
                    </div>
                    
                    
                </div>
                <div class="container-sm col-sm-auto card  bg-light shadow m-3 rounded-3">
                    <div class=" card-header bg-danger text-white">
                        Corte Final
                    </div>
                    <div class=" card-body">
                        
                        <div class="text-center">
                            <label> Entrada de Madera </label>
                        </div>
                        <div class="input-group mb-3 mt-3">
                            
                            <label class="input-group-text" for="entraInicial">Entrada</label>
                            <select class="form-select" id="entraInicial">
                              <option selected>Choose...</option>
                              <option value="1">Bloque</option>
                              <option value="2">Punta</option>
                              <option value="3">Troza</option>
                            </select>
                          </div>
                          <div class="text-center">
                            <label> Salida de Madera </label>
                        </div>
                        <div class="input-group mb-3 mt-3">
                            
                            <label class="input-group-text" for="saleInicial">Salida</label>
                            <select class="form-select" id="saleInicial">
                              <option selected>Choose...</option>
                              <option value="1">Taco</option>
                              <option value="2">Punta</option>
                              <
                            </select>
                          </div>
                          <div class="text-center">
                            <label> Proceso </label>
                            
                        </div>
                        <div class="input-group mb-3 mt-3">
                            
                            <label class="input-group-text" for="maquinaInicial">Maquina</label>
                            <select class="form-select" id="maquinaInicial">
                              <option selected>Choose...</option>
                              <option value="1">One</option>
                              <option value="2">Two</option>
                              <option value="3">Three</option>
                            </select>
                          </div>
                          <div class="mb-3">
                            <label for="observacionInicial" class="form-label text-center">Observaciones</label>
                            <textarea class="form-control" id="observacionInicial" rows="3"></textarea>
                          </div>
                    </div>
                    
                    
                </div>
                <div class="container-sm col-sm-auto card  bg-light shadow m-3 rounded-3">
                    <div class=" card-header bg-dark text-white">
                        Acabados
                    </div>
                    <div class=" card-body">
                        
                        <div class="text-center">
                            <label> Entrada de Madera </label>
                        </div>
                        <div class="input-group mb-3 mt-3">
                            
                            <label class="input-group-text" for="entraInicial">Entrada</label>
                            <select class="form-select" id="entraInicial">
                              <option selected>Choose...</option>
                              <option value="1">Bloque</option>
                              <option value="2">Punta</option>
                              <option value="3">Troza</option>
                            </select>
                          </div>
                          <div class="text-center">
                            <label> Salida de Madera </label>
                        </div>
                        <div class="input-group mb-3 mt-3">
                            
                            <label class="input-group-text" for="saleInicial">Salida</label>
                            <select class="form-select" id="saleInicial">
                              <option selected>Choose...</option>
                              <option value="1">Taco</option>
                              <option value="2">Punta</option>
                              <
                            </select>
                          </div>
                          <div class="text-center">
                            <label> Proceso </label>
                            
                        </div>
                        <div class="input-group mb-3 mt-3">
                            
                            <label class="input-group-text" for="maquinaInicial">Maquina</label>
                            <select class="form-select" id="maquinaInicial">
                              <option selected>Choose...</option>
                              <option value="1">One</option>
                              <option value="2">Two</option>
                              <option value="3">Three</option>
                            </select>
                          </div>
                          <div class="mb-3">
                            <label for="observacionInicial" class="form-label text-center">Observaciones</label>
                            <textarea class="form-control" id="observacionInicial" rows="3"></textarea>
                          </div>
                    </div>
                    
                    
                </div>
              
            </div>
            <div>
                <button type="button" class="btn btn-primary container-fluid">Guardar ruta de programación</button>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script src="/js/modulos/optimas.js"></script>
<script src="/js/modulos/cantidadUsar.js"></script>
@endsection

