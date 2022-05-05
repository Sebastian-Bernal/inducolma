@extends('layouts.web')
@section('title', ' Estados | Inducolma')

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
                <form action="{{ route('estados.update', $estado) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Editar estado</h5>
                        </div>
                        <div class="modal-body">
                            
                            <div class="card-body">                                                
                                                
                                <div class="row mb-3">
                                    <label for="descripcion" class="col-md-4 col-form-label text-md-end">{{ __('Descripción') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="descripcion" 
                                                type="text" 
                                                class="form-control @error('descripcion') is-invalid @enderror text-uppercase" 
                                                name="descripcion" 
                                                value="{{ $estado->descripcion }}" 
                                                required 
                                                autocomplete="descripcion" 
                                                autofocus>
        
                                        @error('descripcion')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Volver</button>
                            <button type="submit" class="btn btn-primary">Actualizar estado</button>
                        </div>
                    </div> 
                </form>               
            </div>
            

           
        </div>
    </div>

@endsection

@section('js')
<script src="/js/modulos/estados.js"></script>
@endsection