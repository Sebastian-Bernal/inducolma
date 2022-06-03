@extends('layouts.web')
@section('title', ' Maderas | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content') 
<div class="div container h-content ">        
    <div class="row">            
        <div class="col-12 col-sm-10 col-lg-6 mx-auto">
            
           
            
            <form action="{{ route('tipos-maderas.update',$tipoMadera) }}" method="POST">
                @csrf
                @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modificar madera</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        
                        <div class="modal-body">
                            <div class="card-body">                                                
                                <div class="row mb-3">
                                    <label for="descripcion" class="col-md-4 col-form-label text-md-end">{{ __('Tipo de madera') }}</label>
                                    <div class="col-md-6">
                                        <input id="descripcion" 
                                                type="text" 
                                                class="form-control @error('descripcion') is-invalid @enderror text-uppercase" 
                                                name="descripcion" value="{{ old('descripcion',$tipoMadera->descripcion) }}" 
                                                required 
                                                autocomplete="descripcion" 
                                                autofocus
                                                onkeyup="mayusculas()">
                                        @error('descripcion')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('tipos-maderas.index') }}" type="button" class="btn btn-secondary" >Volver</a>
                        <button type="submit" class="btn btn-primary">Modificar madera</button>
                        </div>
                    </div>
                 
            </form>               
        </div>
        

      
    </div>
</div>

@endsection

@section('js')
<script src="/js/modulos/maderas.js"></script>
@endsection