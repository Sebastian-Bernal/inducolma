@extends('layouts.web')
@section('title', ' Entradas entrada | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content') 
<div class="div container h-content ">        
    <div class="row">            
        <div class="col-12 col-sm-10 col-lg-6 mx-auto">
            
           
            <h1 class="display-6" >Entrada de madera</h1>
            <hr>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary mb-3" id="registrar" data-bs-toggle="modal" data-bs-target="#creaUsuario">
                Registrar nueva entrada de madera
            </button>
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        - {{ $error }} <br>
                    @endforeach
                </div>
                
            @endif
            <!-- Modal Crea maquina-->
            <form id="formRegistro">
                @csrf
                <div class="modal fade" id="creaUsuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-fullscreen">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Registrar entrada</h5>
                                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                            </div>
                            <div class="modal-body m-3">
                                
                                <div class="row g-3" id="formEntradaMaderas">

                                    <div class="col-md-4">
                                        <div class="row mb-3">
                                            <label for="mes" class="col-md-3 col-form-label text-md-end px-0 pt-7 pb-7 ">{{ __('Mes') }}</label>
                                            <div class="col-md-8">
                                                <select name="mes" id="mes" class="form-control @error('mes') is-invalid @enderror" required autocomplete="mes" autofocus>
                                                    <option value="" selected>Seleccione..</option>
                                                    <option value="Enero">Enero</option>
                                                    <option value="Febrero">Febrero</option>
                                                    <option value="Marzo">Marzo</option>
                                                    <option value="Abril">Abril</option>
                                                    <option value="Mayo">Mayo</option>
                                                    <option value="Junio">Junio</option>
                                                    <option value="Julio">Julio</option>
                                                    <option value="Agosto">Agosto</option>
                                                    <option value="Septiembre">Septiembre</option>
                                                    <option value="Octubre">Octubre</option>
                                                    <option value="Noviembre">Noviembre</option>
                                                    <option value="Diciembre">Diciembre</option>
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
                                                {{ $ano = date('Y') }}
                                                    @while ($ano >= 1990)
                                                        <option value="{{ $ano }}">{{ $ano }}</option>
                                                        {{ $ano = $ano - 1 }}                                                    
                                                    @endwhile
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
                                                <input type="time" name="hora" id="hora" class="form-control @error('hora') is-invalid @enderror" required autocomplete="hora" autofocus> 
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
                                                <input type="date" name="fecha" id="fecha" class="form-control @error('fecha') is-invalid @enderror" required autocomplete="fecha" autofocus> 
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
                                                <input type="text" name="actoAdministrativo" id="actoAdministrativo" class="form-control @error('actoAdministrativo') is-invalid @enderror text-uppercase" required autocomplete="actoAdministrativo"> 
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
                                                <input type="number" name="salvoconducto" step="0.1" id="salvoconducto" class="form-control @error('salvoconducto') is-invalid @enderror text-uppercase" required autocomplete="salvoconducto" autofocus> 
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
                                                <input type="text" name="titularSalvoconducto" id="titularSalvoconducto" class="form-control @error('titularSalvoconducto') is-invalid @enderror text-uppercase" required autocomplete="titularSalvoconducto" autofocus> 
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
                                                <input type="text" name="procedencia" id="procedencia" class="form-control @error('procedencia') is-invalid @enderror text-uppercase" required autocomplete="procedencia" autofocus> 
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
                                                <input type="text" name="entidadVigilante" id="entidadVigilante" class="form-control @error('entidadVigilante') is-invalid @enderror text-uppercase" required autocomplete="entidadVigilante" autofocus> 
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
                                                    <option value="" selected>Seleccione...</option>
                                                    @foreach ($proveedores as $proveedor)
                                                    <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>                                                  
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
                                    <div class="col-md-4">
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
                                    </div>

                                    <div class="col-md-2">
                                        <div>
                                            <button type="button" class="btn btn-primary" onclick="agregarMadera()">Agregar madera</button>
                                        </div>                     
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <table  class="table table-bordered table-striped dt-responsive">
                                            <thead>
                                                <th>Madera</th>
                                                <th>Condicion madera</th>
                                                <th>Metros cubicos</th>
                                                <th>Acciones</th>
                                            </thead>
                                            <tbody id="listaMaderas">
        
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                    
                                
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="borrarMaderas()" >Cancelar</button>
                                <button type="button" class="btn btn-primary" onclick="validarCampos()">Guardar entrada</button>
                            </div>
                        </div>
                    </div>
                </div>   
            </form>
            <div class="justify-content-left"  >
                <button class="btn btn-warning"  id="editarUltimo" onclick="editarUltimo()">Editar ultima entrada</button>               
            </div>
            
        </div>
        <!-- Tabla -->

        <table id="listaEntradas" class="table table-bordered table-striped dt-responsive">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Mes</th>   
                    <th>Año</th>
                    <th>Hora</th>         
                    <th>Fecha</th>
                    <th>Acto administrativo</th>
                    <th>Salvoconducto remision</th>
                    <th>Titular salvoconducto</th>
                    <th>Procedencia de madera</th>
                    <th>proveedor</th>                   
                    <th>Entidad vigilante</th>
                    {{-- <th>Maderas</th> --}}
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($entradas as $entrada)
                    <tr>
                        <td>{{ $entrada->id }}</td>
                        <td>{{ $entrada->mes }}</td>                      
                        <td>{{ $entrada->ano }}</td>
                        <td>{{ $entrada->hora }}</td>
                        <td>{{ $entrada->fecha }}</td>
                        <td>{{ $entrada->acto_administrativo }}</td>
                        <td>{{ $entrada->salvoconducto_remision }}</td>
                        <td>{{ $entrada->titular_salvoconducto }}</td>
                        <td>{{ $entrada->procedencia_madera }}</td>
                        <td>{{ $entrada->proveedor->nombre }}</td>
                        <td>{{ $entrada->entidad_vigilante }}</td>
                        {{-- <td>
                            @foreach ($entrada->entradas_madera_maderas as $entrada_madera)
                                @foreach ($entrada->maderas as $madera)  
                                    <ul>
                                        <li>{{ $madera->nombre }}</li>
                                        <li>{{ $madera->nombre_cientifico }}</li>
                                        <li>{{ $entrada_madera->condicion_madera }}</li>
                                        <li>{{ $entrada_madera->m3entrada }}</li>
                                    </ul>
                                    <hr>                              
                                @endforeach                                   
                            @endforeach
                        </td> --}}
                        <td>
                            @can('update-delete-entrada')
                            <div class="d-flex align-items-center ">
                                    
                                    <button class="btn btn-sm btn-danger" onclick="eliminarMadera({{ $entrada}})">
                                        <i class="fa-regular fa-trash-can fa-lg" style="color: black"></i>
                                    </button>
                                    <a href="{{ route('entradas-maderas.show',$entrada) }}" class="btn btn-sm btn-warning">
                                        <i class="fa-solid fa-pen-to-square fa-lg"></i>
                                    </a>
                                
                                </div>
                            @endcan
                            @can('ver-entrada')
                                <a href="{{ route('entradas-maderas.show',$entrada->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            @endcan
                            
                        </td>
                    </tr> 
                @endforeach
                
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('js')
<script src="/js/modulos/entrada-madera.js"></script>
@endsection