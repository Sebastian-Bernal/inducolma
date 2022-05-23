@extends('layouts.web')
@section('title', ' Proveedores | inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content') 
<div class="row">            
    <div class="col-12 col-sm-10 col-lg-6 mx-auto">
        <form action="{{ route('proveedores.update',$proveedor) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modificar Proveedor</h5>
                </div>
                <div class="modal-body">
                    
                    <div class="card-body">                                                
                                        
                        <div class="row mb-3">
                            <label for="identificacion" class="col-md-4 col-form-label text-md-end">{{ __('Nit o cedula') }}</label>
                            <div class="col-md-6">
                                <input  id="identificacion" 
                                        type="text" 
                                        class="form-control @error('identificacion') is-invalid @enderror" 
                                        name="identificacion" value="{{ old('identificacion',$proveedor->identificacion) }}" 
                                        required 
                                        autocomplete="identificacion" 
                                        autofocus>

                                @error('identificacion')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        
                        <div class="row mb-3">
                            <label for="nombre" class="col-md-4 col-form-label text-md-end">{{ __('Representante legal') }}</label>

                            <div class="col-md-6">
                                <input  id="nombre" 
                                        type="text" 
                                        class="form-control @error('nombre') is-invalid @enderror text-uppercase" 
                                        name="nombre" 
                                        value="{{ old('name', $proveedor->nombre) }}" 
                                        required 
                                        autocomplete="nombre" 
                                        autofocus>

                                @error('nombre')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="razon_social" class="col-md-4 col-form-label text-md-end">{{ __('Razón social') }}</label>
                            <div class="col-md-6">
                                <input  id="razon_social" 
                                        type="text" 
                                        class="form-control @error('razon_social') is-invalid @enderror text-uppercase" 
                                        name="razon_social" value="{{ old('razon_social', $proveedor->razon_social) }}" 
                                         
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
                            <label for="direccion" class="col-md-4 col-form-label text-md-end">{{ __('Address') }}</label>

                            <div class="col-md-6">
                                <input  id="direccion" 
                                        type="text" 
                                        class="form-control @error('direccion') is-invalid @enderror text-uppercase" 
                                        name="direccion" 
                                        value="{{ old('direccion', $proveedor->direccion) }}" 
                                        required 
                                        autocomplete="direccion">

                                @error('direccion')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> 

                        <div class="row mb-3">
                            <label for="telefono" class="col-md-4 col-form-label text-md-end">{{ __('Teléfono ') }}</label>

                            <div class="col-md-6">
                                <input id="telefono" 
                                        type="number" 
                                        class="form-control @error('telefono') is-invalid @enderror" 
                                        name="telefono" 
                                        value="{{ old('telefono', $proveedor->telefono) }}" 
                                        required 
                                        autocomplete="telefono">

                                @error('telefono')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> 
                        
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email') }}</label>

                            <div class="col-md-6">
                                <input  id="email" 
                                        type="email" 
                                        class="form-control @error('email') is-invalid @enderror " 
                                        name="email" 
                                        value="{{ old('email', $proveedor->email) }}" 
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
                <a href="{{ route('proveedores.index') }}" type="button" class="btn btn-secondary" >volver</a>
                <button type="submit" class="btn btn-primary">Modificar proveedor</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection