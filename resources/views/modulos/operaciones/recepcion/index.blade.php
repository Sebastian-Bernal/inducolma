@extends('layouts.web')
@section('title', ' Recepcion | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content') 
    <div class="div container h-content ">        
        <div class="row">            
            <div class="col-12 col-sm-10 col-lg-6 mx-auto">
                
            
                <h1 class="display-6" >Recepci&oacute;n</h1>
                <hr>
                <!-- Button trigger modal -->
                <button type="button" 
                        class="btn btn-primary mb-3" 
                        data-bs-toggle="modal" 
                        data-bs-target="#crearecepcion"
                        onclick="focusCc()">
                    Registrar ingreso
                </button>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            - {{ $error }} <br>
                        @endforeach
                    </div>
                    
                @endif
                <!-- Modal Crea maquina-->
                <form action="{{ route('recepcion.store') }}" method="POST" id="formRecepcion">
                    @csrf
                    <div class="modal fade" id="crearecepcion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Recepcion</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="limpiarForm()"></button>
                            </div>
                            <div class="modal-body">
                                
                                <div class="card-body">                                                
                                                    
                                    <div class="row mb-3">
                                        <label for="cc" class="col-md-4 col-form-label text-md-end">{{ __('Numero de cédula') }}</label>
            
                                        <div class="col-md-6">
                                            <input  id="cc" 
                                                    type="text" 
                                                    class="form-control @error('cc') is-invalid @enderror text-uppercase" 
                                                    name="cc" 
                                                    value="{{ old('cc') }}" 
                                                    required 
                                                    autocomplete="cc" 
                                                    autofocus
                                                    >
            
                                            @error('cc')
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
                                                    value="{{ old('primer_apellido') }}" 
                                                    required 
                                                    autocomplete="primer_apellido" 
                                                    autofocus>
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
                                                    value="{{ old('segundo_apellido') }}" 
                                                    required 
                                                    autocomplete="segundo_apellido" 
                                                    autofocus>
                                            @error('segundo_apellido')
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
                                                    value="{{ old('primer_nombre') }}" 
                                                    required 
                                                    autocomplete="primer_nombre">
            
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
                                            <input  id="segundo_nombre " 
                                                    type="text" 
                                                    class="form-control @error('segundo_nombre ') is-invalid @enderror text-uppercase" 
                                                    name="segundo_nombre" 
                                                    value="{{ old('segundo_nombre') }}" 
                                                    required 
                                                    autocomplete="segundo_nombre">
                                            @error('segundo_nombre ')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <label for="sexo" class="col-md-4 col-form-label text-md-end">{{ __('Sexo') }}</label>
            
                                        <div class="col-md-6">
                                            <input  id="sexo " 
                                                    type="text" 
                                                    class="form-control @error('sexo ') is-invalid @enderror text-uppercase" 
                                                    name="sexo" 
                                                    value="{{ old('sexo') }}" 
                                                    required 
                                                    autocomplete="sexo">
                                            @error('sexo ')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <label for="fn" class="col-md-4 col-form-label text-md-end">{{ __('Fecha nacimiento') }}</label>
            
                                        <div class="col-md-6">
                                            <input  id="fn " 
                                                    type="text" 
                                                    class="form-control @error('fn ') is-invalid @enderror text-uppercase" 
                                                    name="fn" 
                                                    value="{{ old('fn') }}" 
                                                    required 
                                                    autocomplete="fn">
                                            @error('fn ')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="rh" class="col-md-4 col-form-label text-md-end">{{ __('RH') }}</label>
            
                                        <div class="col-md-6">
                                            <input  id="rh " 
                                                    type="text" 
                                                    class="form-control @error('rh ') is-invalid @enderror text-uppercase" 
                                                    name="rh" 
                                                    value="{{ old('rh') }}" 
                                                    required 
                                                    autocomplete="rh">
                                            @error('rh ')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="visitante" class="col-md-4 col-form-label text-md-end">{{ __('Visitante ?') }}</label>
            
                                        <div class="col-md-6">
                                            <select  id="visitante" 
                                                    class="form-control @error('visitante') is-invalid @enderror " 
                                                    name="visitante" 
                                                    required 
                                                    autocomplete="visitante">
                                                <option value="1">Si</option>
                                                <option value="0" selected>No</option>
                                            </select>
                                            @error('visitante')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                   
                                                   
                            </div>
                                    
                                
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="limpiarForm()">Cerrar</button>
                            <button type="button" class="btn btn-primary" onclick="consultaUsuario();">Guardar recepcion</button>
                            </div>
                        </div>
                        </div>
                    </div>   
                </form>               
            </div>
            <!-- Tabla -->

            <table id="listarecepcion" class="table table-bordered table-striped dt-responsive">
                <thead>
                    <tr>
                        <th>Identificacion</th>
                        <th>Nombres y appellidos</th>  
                        <th>Visitante</th> 
                        <th>Fecha y hora de ingreso</th>         
                        <th>Fecha y hora de salida</th>                        
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($recepciones as $recepcion)
                        <tr>
                            <td>
                                @if (strlen($recepcion->cc) == 7)
                                {{ '000'.$recepcion->cc }}
                                @elseif (strlen($recepcion->cc) == 8)
                                    {{ '00'.$recepcion->cc }}
                                @elseif (strlen($recepcion->cc) == 9)
                                    {{ '0'.$recepcion->cc }}
                                @else
                                    {{ $recepcion->cc }}
                                @endif
                                
                            </td>
                            <td>{{ $recepcion->nombre_completo }}</td>   
                            <td>
                                @if ($recepcion->visitante == 1)
                                    {{ 'Si' }}
                                @else
                                    {{ 'No' }}
                                @endif    
                            </td>                   
                            <td>{{ $recepcion->created_at }}</td>
                            <td>{{ $recepcion->deleted_at }}</td>                            
                            <td>
                                <div class="d-flex align-items-center ">

                                    <button class="btn btn-sm btn-primary" onclick="horaSalida({{ $recepcion }})">
                                        <i class="fa-solid fa-arrow-right-from-bracket" style="color: black"></i>
                                    </button>
                                    {{-- <button class="btn btn-sm btn-danger" onclick="eliminarRecepcion({{ $recepcion }})">
                                        <i class="fa-regular fa-trash-can fa-lg" style="color: black"></i>
                                    </button> --}}
                                    <a href="{{ route('recepcion.edit',$recepcion) }}" class="btn btn-sm btn-warning">
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
<script src="/js/modulos/recepcion.js"></script>
@endsection