@extends('layouts.web')
@section('title', ' Trabajo maquina | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content')
    <div class="div container h-content ">
          <div class="row">
            {{ $usuario }} <br>
            {{ $turno_usuarios }} <br>
            {{ $maquinas }} <br>
            {{ $eventos }} <br> 
        </div>  
        <div class="d-flex flex-wrap row-col-sm-1 row-col-md-2 container-fluid">
            {{-- corte inicial tarjeta --}}
            <div class="container-sm col-sm-auto card  bg-light shadow m-3 rounded-3">
                <div class=" card-header bg-primary text-white">
                    Corte Inicial
                </div>
                <div class=" card-body">
    
                    <div class="text-center">
                        <label> Maquina a trabajar </label>
                    </div>
                    <form id="confirmarMaquina">
                     
                        <div class="input-group mb-3 mt-3">
                            
                            <label class="input-group-text" for="maquina">Maquina</label>
                            <select class="form-select" id="maquina">
                                <option value="0">Seleccionar...</option>
                                @forelse ($maquinas as $maquina)
                                <option value="{{ $maquina->id }}" name="{{ $maquina->maquina }}" {{ $maquina->id == $turno_usuarios[0]->maquina_id ? 'selected' : '' }}>{{ $maquina->maquina
                                    }}</option>
    
                                @empty
                                <option>No existen maquinas creadas</option>
                                @endforelse
                            </select>
                           
                        </div>
                     
                        
                    </form>
                    <div>
                        <button class=" bg-warning text-center text-white container p-2 rounded"
                            onClick="confirmarMaquinaBoton()">Confirmar maquina</button>
                    </div>
                </div>
    
    
            </div>
        </div>
    </div>
    
@endsection

@section('js')
<script src="/js/modulos/maquina_inicia.js"></script>
@endsection
