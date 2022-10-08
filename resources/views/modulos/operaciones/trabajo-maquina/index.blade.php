@extends('layouts.web')
@section('title', ' Trabajo maquina | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content')
     <div class="container h-content ">
        <div id="nombres">
        @forelse ($turno_usuarios as $turno)
            
        <input id="{{ $turno->user_id }}" style="display:none" value="{{ $turno->user->name }}">
                    
        @empty
            
        @endforelse
     </div>
        <button style="display:none" id="users" onclick="listaUser({{ $turno_usuarios }})"></button>
           {{--  <div class="row"></div>
            {{ $usuario }} <br>
            {{ $turno_usuarios }} <br>
            {{ $maquinas }} <br>
            {{ $eventos }} <br> 
        </div>      --}}
        <div class="d-flex flex-wrap justify-content-center container-fluid">
            {{-- PROCESO --}}
            <div id="maquinas" class="container card  bg-light shadow m-2 rounded-3">
                <div class=" card-header bg-primary text-white">
                    Proceso
                </div>
                <div class=" card-body">
                    
                    <div class="text-center">
                        <label> Maquina a trabajar </label>
                    </div>
                    
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
                        <div>
                            <button type="button" class="text-white btn btn-warning container-fluid" onclick="confirmaMaquina({{ $turno_usuarios }})">Confirma maquina a trabajar</button>
                        </div>
                     
               
                  
                </div>
    
    
            </div>
               {{-- AUXILIARES --}}
               <div class="container card  bg-light shadow m-3 rounded-3">
                <div class=" card-header bg-secondary text-white">
                    Auxiliares
                </div>
                <div class=" card-body">
    
                    <div class="text-center">
                        <label> Usuarios auxiliares en este proceso </label>
                    </div>
                  
                     
                        <div class="input-group mb-3 mt-3">
                            
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                       
                                        <th scope="col">Usuario</th>
                                       
                                        <th scope="col">Acciones</th>
                                        
                                    </tr>
                                </thead>
                                <tbody id="listarUsers">
                                   
                                </tbody>
                            </table>
                           
                        </div>
                     
                        <div>
                            <button type="button" class="text-white btn btn-danger container-fluid" onclick="cambiarUsuario()">Seleccionar otro auxiliar para esta maquina</button>
                        </div>
                   
                  
                </div>
    
    
            </div>
        </div>
        
         
        
    </div>
    
@endsection

@section('js')
<script src="/js/modulos/maquina_inicia.js"></script>
@endsection
