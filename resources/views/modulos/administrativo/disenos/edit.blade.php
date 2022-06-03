@extends('layouts.web')
@section('title', ' Clientes | Inducolma')

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
                <!-- Modal modifica diseño-->
                <form action="{{ route('disenos.update',$diseno) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Modificar diseño</h5>
                                <a href="{{ route('disenos.index') }}" class="btn-close" ></a>
                            </div>
                            <div class="modal-body">
                                
                                <div class="card-body">                                                
                                                    
                                    <div class="row mb-3">
                                        <label for="descripcion" class="col-md-4 col-form-label text-md-end">{{ __('Descripción') }}</label>
            
                                        <div class="col-md-6">
                                            <input  id="descripcion" 
                                                    type="text" 
                                                    class="form-control @error('descripcion') is-invalid @enderror text-uppercase" 
                                                    name="descripcion" 
                                                    value="{{ old('descripcion',$diseno->descripcion) }}" 
                                                    required 
                                                    autocomplete="descripcion" 
                                                    autofocus
                                                    >
            
                                            @error('descripcion')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="madera_id" class="col-md-4 col-form-label text-md-end">{{ __('Madera') }}</label>
            
                                        <div class="col-md-6">
                                            <select  id="madera_id_edit" 
                                                    type="text" 
                                                    class="form-control @error('madera_id') is-invalid @enderror text-uppercase" 
                                                    name="madera_id" 
                                                    value="{{ old('madera_id') }}" 
                                                    required 
                                                    autocomplete="madera_id" 
                                                    autofocus>
                                                <option value="" selected>Seleccione...</option>
                                                @foreach ($tipos_maderas as $tipo_madera)
                                                    <option value="{{ $tipo_madera->id }}" {{ $tipo_madera->id == $diseno->tipo_madera_id ? 'selected' : '' }}>{{ $tipo_madera->descripcion }}</option>
                                                @endforeach
                                            </select>
                                            @error('madera_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>    
                            </div>
                            <div class="modal-footer">
                                <a href="{{ route('disenos.show',$diseno) }}" class="btn btn-outline-secondary" >Volver</a>
                                <button type="submit" class="btn btn-primary">Modificar diseno</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
          
        </div>
    </div>

@endsection

@section('js')
<script src="/js/modulos/disenos.js"></script>
@endsection