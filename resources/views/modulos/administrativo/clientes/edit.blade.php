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
                <!-- Modal Crea maquina-->
                <form action="{{ route('clientes.update', $cliente) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Editar cliente</h5>
                        </div>
                        <div class="modal-body">
                            
                            <div class="card-body">                                                
                                                
                                <div class="row mb-3">
                                    <label for="nit" class="col-md-4 col-form-label text-md-end">{{ __('Nit') }}</label>
        
                                    <div class="col-md-6">
                                        <input  id="nit" 
                                                type="text" 
                                                class="form-control @error('nit') is-invalid @enderror" 
                                                name="nit" 
                                                value="{{ old('nit', $cliente->nit) }}"
                                                required 
                                                autocomplete="nit" 
                                                autofocus>
        
                                        @error('nit')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="nombre" class="col-md-4 col-form-label text-md-end">{{ __('Representante legal') }}</label>
        
                                    <div class="col-md-6">
                                        <input id="nombre" 
                                                type="text" 
                                                class="form-control @error('nombre') is-invalid @enderror" 
                                                name="nombre" 
                                                value="{{ old('nombre', $cliente->nombre) }}"
                                                required 
                                                autocomplete="nombre" 
                                                autofocus >
                                        @error('nombre')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="razon_social" class="col-md-4 col-form-label text-md-end">{{ __('Razon social') }}</label>
        
                                    <div class="col-md-6">
                                        <input  id="razon_social" 
                                                type="text" 
                                                class="form-control @error('razon_social') is-invalid @enderror text-uppercase" 
                                                name="razon_social" 
                                                value="{{ old('razon_social', $cliente->razon_social) }}" 
                                                required 
                                                autocomplete="razon_social" 
                                                autofocus>
                                        @error('razon_social')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
        
                                <div class="row mb-3">
                                    <label for="direccion" class="col-md-4 col-form-label text-md-end">{{ __('Dirección') }}</label>
                                    <div class="col-md-6">
                                        <input  id="direccion" 
                                                type="text" 
                                                class="form-control @error('direccion') is-invalid @enderror" 
                                                name="direccion" 
                                                value="{{ old('direccion', $cliente->direccion) }}" 
                                                required 
                                                autocomplete="direccion" >
        
                                        @error('direccion')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div> 

                                <div class="row mb-3">
                                    <label for="telefono" class="col-md-4 col-form-label text-md-end">{{ __('Teléfono') }}</label>
        
                                    <div class="col-md-6">
                                        <input  id="telefono" 
                                                type="number" 
                                                class="form-control @error('telefono') is-invalid @enderror" 
                                                name="telefono" 
                                                value="{{ old('telefono', $cliente->telefono) }}" 
                                                required 
                                                autocomplete="telefono" >
                                        @error('telefono')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Correo electrónico') }}</label>
        
                                    <div class="col-md-6">
                                        <input  id="email" 
                                                type="email" 
                                                class="form-control @error('email') is-invalid @enderror" 
                                                name="email" 
                                                value="{{ $cliente->email }}" 
                                                required 
                                                autocomplete="email">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>                                               
                            </div>
                                
                            
                        </div>
                        <div class="modal-footer">
                            <a href="{{ route('clientes.index') }}" class="btn btn-secondary" >Volver</a>
                            <button type="submit" class="btn btn-primary">Actualizar cliente</button>
                        </div>
                    </div> 
                </form>               
            </div>
            

           
        </div>
    </div>

@endsection

@section('js')
<script src="/js/modulos/clientes.js"></script>
@endsection