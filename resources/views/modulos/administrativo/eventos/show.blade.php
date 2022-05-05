@extends('layouts.web')
@section('title', ' eventos | Inducolma')

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
                <form action="{{ route('eventos.update', $evento) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Editar evento</h5>
                        </div>
                        <div class="modal-body">
                            
                            <div class="card-body">                                                
                                                
                                <div class="row mb-3">
                                    <label for="descripcion" class="col-md-4 col-form-label text-md-end">{{ __('Descripción') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="descripcion" type="text" class="form-control @error('descripcion') is-invalid @enderror" name="descripcion" value="{{ $evento->descripcion }}" required autocomplete="descripcion" autofocus>
        
                                        @error('descripcion')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="tipo_evento" class="col-md-4 col-form-label text-md-end">{{ __('Tipo Evento') }}</label>
        
                                    <div class="col-md-6">
                                        <select id="tipoEvento" 
                                                
                                                class="form-control @error('tipoEvento') is-invalid @enderror" 
                                                name="tipoEvento" 
                                                required 
                                                autocomplete="tipoEvento" 
                                                autofocus>
                                           
                                                @foreach ($tipo_eventos as $tipo_evento)
                                                    <option value="{{ $tipo_evento->id }}"
                                                        {{ $tipo_evento->id == $evento->tipo_evento_id ? 'selected' : '' }}
                                                        >{{ $tipo_evento->tipo_evento }}</option>
                                                @endforeach
                                        </select>
                                        @error('tipoEvento')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <a href="{{ route('tipo-eventos.index') }}" class="btn btn-secondary">Volver</a>
                            <button type="submit" class="btn btn-primary">Actualizar evento</button>
                        </div>
                    </div> 
                </form>               
            </div>
            

           
        </div>
    </div>

@endsection

@section('js')
<script src="/js/modulos/eventos.js"></script>
@endsection