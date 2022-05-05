@extends('layouts.web')
@section('title', ' Items | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content') 
    <div class="div container h-content ">        
        <div class="row">            
            <div class="col-12 col-sm-10 col-lg-6 mx-auto">
                
            
                <h1 class="display-6" >Items almacen</h1>
                <hr>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#creaitem">
                    Crear item
                </button>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            - {{ $error }} <br>
                        @endforeach
                    </div>
                    
                @endif
                <!-- Modal Crea maquina-->
                <form action="{{ route('items.store') }}" method="POST">
                    @csrf
                    <div class="modal fade" id="creaitem" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Crea item</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                
                                <div class="card-body">                                                
                                                    
                                    <div class="row mb-3">
                                        <label for="descripcion" class="col-md-4 col-form-label text-md-end">{{ __('Descripción') }}</label>
                                        <div class="col-md-6">
                                            <input id="descripcion" 
                                                    type="text" 
                                                    class="form-control @error('descripcion') is-invalid @enderror text-uppercase"
                                                      
                                                    name="descripcion" value="{{ old('descripcion') }}" 
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
                                                      
                                                    name="alto" value="{{ old('alto') }}" 
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
                                                      
                                                    name="largo" value="{{ old('largo') }}" 
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
                                                      
                                                    name="ancho" value="{{ old('ancho') }}" 
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
                                        <label for="existencias" class="col-md-4 col-form-label text-md-end">{{ __('Existencias') }}</label>
                                        <div class="col-md-6">
                                            <input id="existencias" 
                                                    type="number" 
                                                    class="form-control @error('existencias') is-invalid @enderror text-uppercase"
                                                      
                                                    name="existencias" value="{{ old('existencias') }}" 
                                                    required autocomplete="existencias" autofocus
                                                    onkeyup="mayusculas()">
            
                                            @error('existencias')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="tipo_madera" class="col-md-4 col-form-label text-md-end">{{ __('Tipo de Madera') }}</label>
                                        <div class="col-md-6">
                                            <input id="tipo_madera" 
                                                    type="text" 
                                                    class="form-control @error('tipo_madera') is-invalid @enderror text-uppercase"
                                                      
                                                    name="tipo_madera" value="{{ old('tipo_madera') }}" 
                                                    required autocomplete="tipo_madera" autofocus
                                                    onkeyup="mayusculas()">
            
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
                                                      
                                                    name="codigo_cg" value="{{ old('codigo_cg') }}" 
                                                    required autocomplete="codigo_cg" autofocus
                                                    onkeyup="mayusculas()">
            
                                            @error('codigo_cg')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="preprocesado" class="col-md-4 col-form-label text-md-end">{{ __('Preprocesado') }}</label>
                                        <div class="col-md-6">
                                            <select id="preprocesado" 
                                                    type="number" 
                                                    class="form-control @error('preprocesado') is-invalid @enderror"                                                    
                                                    name="preprocesado" 
                                                    required 
                                                    >
                                                <option selected>Seleccione una opción</option>
                                                <option value="1">SI</option>
                                                <option value="0">NO</option>
                                            </select>
            
                                            @error('preprocesado')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="carretos" class="col-md-4 col-form-label text-md-end">{{ __('Carretos') }}</label>
                                        <div class="col-md-6">
                                            <input id="carretos" 
                                                    type="number" 
                                                    class="form-control @error('carretos') is-invalid @enderror text-uppercase"
                                                      
                                                    name="carretos" value="{{ old('carretos') }}" 
                                                    required autocomplete="carretos" autofocus
                                                    onkeyup="mayusculas()">
            
                                            @error('carretos')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>


                            </div>
                                    
                                
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar item</button>
                            </div>
                        </div>
                        </div>
                    </div>   
                </form>               
            </div>
            <!-- Tabla -->

            <table id="listaitems" class="table table-bordered table-striped dt-responsive">
                <thead>
                    <tr>
                        <th>Descripci&oacute;n</th>
                        <th>C&oacute;digo CG</th>   
                        <th>Alto</th>
                        <th>Largo</th>
                        <th>Ancho</th>
                        <th>Existencias</th>
                        <th>Tipo de Madera</th>
                        <th>Preprocesado</th>
                        <th>Carretos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ $item->descripcion }}</td>
                            <td>{{ $item->codigo_cg }}</td>
                            <td>{{ $item->alto }}</td> 
                            <td>{{ $item->largo }}</td> 
                            <td>{{ $item->ancho }}</td>    
                            <td>{{ $item->existencias }}</td>
                            <td>{{ $item->tipo_madera }}</td>                            
                            <td>@if ($item->preprocesado == true)
                                    {{ 'SI' }}
                                @else
                                    {{ 'NO' }}
                                @endif
                                
                            </td> 
                            <td>{{ $item->carretos }}</td>                   
                            
                            
                            <td>
                                <div class="d-flex align-items-center ">
                                    
                                    <button class="btn btn-sm btn-danger" onclick="eliminarItem({{ $item }})">
                                        <i class="fa-regular fa-trash-can fa-lg" style="color: black"></i>
                                    </button>
                                    <a href="{{ route('items.show',$item) }}" class="btn btn-sm btn-warning">
                                        <i class="fa-solid fa-pen-to-square fa-lg"></i>
                                    </a>
                                
                                </div>
                            </td>
                        </tr> 
                    @endforeach
                    
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('js')
<script src="/js/modulos/items.js"></script>
@endsection