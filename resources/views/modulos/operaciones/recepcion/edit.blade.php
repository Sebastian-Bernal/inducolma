@extends('layouts.web')
@section('title', ' Recepcion | Inducolma')

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
                <!-- Modal Crea maquina-->
                <form action="{{ route('recepcion.update',$recepcion) }}" method="POST" id="formRecepcion">
                    @csrf
                    @method('PUT')
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Recepcion</h5>
                            <a href="{{ route('recepcion.index') }}" class="btn-close"></a>
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
                                                    value="{{ old('cc',$recepcion->cc) }}" 
                                                    required 
                                                    autocomplete="cc" 
                                                    autofocus
                                                    disabled
                                                    >
            
                                            @error('cc')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="primer_apellido" class="col-md-4 col-form-label text-md-end">{{ __('Nombre Completo') }}</label>
            
                                        <div class="col-md-8">
                                            <input  id="primer_apellido" 
                                                    type="text" 
                                                    class="form-control @error('primer_apellido') is-invalid @enderror text-uppercase" 
                                                    name="primer_apellido" 
                                                    value="{{ old('primer_apellido',$recepcion->nombre_completo) }}" 
                                                    required 
                                                    autocomplete="primer_apellido" 
                                                    autofocus
                                                    disabled>
                                            @error('primer_apellido')
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
                                <a href="{{ route('recepcion.index') }}" class="btn btn-secondary" >Volver</a>
                                <button type="button" class="btn btn-primary" onclick="consultaUsuario();">Guardar recepcion</button>
                            </div>
                        </div>
                        </div>
                      
                </form>               
            </div>
            
        </div>
    </div>

@endsection

@section('js')
<script src="/js/modulos/recepcion.js"></script>
@endsection