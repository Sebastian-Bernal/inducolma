@extends('layouts.web')
@section('title', ' Tipo evento | Inducolma')

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
                <form action="{{ route('tipo-eventos.update',$tipo_evento) }}" method="POST">
                    @csrf
                    @method('PUT')
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Crea tipo evento</h5>
                            <a href="{{ route('tipo-eventos.index') }}" class="btn-close"></a>
                            </div>
                            <div class="modal-body">
                                
                                <div class="card-body">                                                
                                                    
                                    <div class="row mb-3">
                                        <label for="tipo_evento" class="col-md-4 col-form-label text-md-end">{{ __('Descripción') }}</label>
            
                                        <div class="col-md-6">
                                            <input id="tipo_evento" 
                                                type="text" 
                                                class="form-control @error('tipo_evento') is-invalid @enderror text-uppercase" 
                                                name="tipo_evento" 
                                                value="{{ old('tipo_evento',$tipo_evento->tipo_evento) }}" 
                                                required autocomplete="tipo_evento" 
                                                autofocus
                                                onkeyup="mayusculas()">
            
                                            @error('tipo_evento')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                </div>
                                
                            </div>
                            <div class="modal-footer">
                            <a href="{{ route('tipo-eventos.index') }}" class="btn btn-secondary">volver</a>
                            <button type="submit" class="btn btn-primary">Actualizar tipo evento</button>
                            </div>
                        </div>
                        </div>
                </form>               
            </div>
         
        </div>
    </div>

@endsection

@section('js')
<script src="/js/modulos/tipo_eventos.js"></script>
@endsection