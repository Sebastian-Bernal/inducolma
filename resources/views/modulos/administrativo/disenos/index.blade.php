@extends('layouts.web')
@section('title', ' Clientes | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content') 
    <div class="div container h-content ">        
        <div class="row">            
            <div class="col-12 col-sm-10 col-lg-6 mx-auto">
                
            
                <h1 class="display-6" >Diseños</h1>
                <hr>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#creadiseno">
                    Crear nuevo diseño
                </button>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            - {{ $error }} <br>
                        @endforeach
                    </div>
                    
                @endif
                <!-- Modal Crea maquina-->
                <form action="{{ route('disenos.store') }}" method="POST">
                    @csrf
                    <div class="modal fade" id="creadiseno" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Crea diseno</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                                    value="{{ old('descripcion') }}" 
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
                                            <select  id="madera_id" 
                                                    type="text" 
                                                    class="form-control @error('madera_id') is-invalid @enderror text-uppercase" 
                                                    name="madera_id" 
                                                    value="{{ old('madera_id') }}" 
                                                    required 
                                                    autocomplete="madera_id" 
                                                    autofocus>
                                                <option value="" selected>Seleccione...</option>
                                                @foreach ($maderas as $madera)
                                                    <option value="{{ $madera->id }}">{{ $madera->nombre }}</option>
                                                @endforeach
                                            </select>
                                            @error('madera_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="cliente_id" class="col-md-4 col-form-label text-md-end">{{ __('Cliente') }}</label>
            
                                        <div class="col-md-6">
                                            <select  id="cliente_id" 
                                                    type="text" 
                                                    
                                                    class="form-control @error('cliente_id') is-invalid @enderror text-uppercase" 
                                                    name="cliente_id" 
                                                    value="{{ old('cliente_id') }}" 
                                                    required 
                                                    autocomplete="cliente_id" 
                                                    autofocus>
                                                <option value="" selected>Seleccione...</option>
                                                @foreach ($clientes as $cliente)
                                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                                @endforeach
                                            </select>
                                            @error('cliente_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                            </div>
                                    
                                
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar diseno</button>
                            </div>
                        </div>
                        </div>
                    </div>   
                </form>               
            </div>
            <!-- Tabla -->

            <table id="listadisenos" class="table table-bordered table-striped dt-responsive">
                <thead>
                    <tr>
                        <th>Descripci&oacute;n</th>
                        <th>Tipo Madera</th>  
                        {{-- <th>Creado por</th> --}}
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($disenos as $diseno)
                        <tr>
                            <td>{{ $diseno->descricion }}</td>
                            <td>{{ $diseno->madera->madera_id }}</td>
                            {{-- <td>{{ $diseno->user->name }}</td>    --}}
                            
                            <td>
                                <div class="d-flex align-items-center ">
                                    
                                    <a  href="{{ route('disenos.show',$diseno->id) }}" 
                                                class="btn btn-primary btn-sm m-1" 
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="top" title="Ver diseno">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    
                                    <button class="btn btn-sm btn-danger" onclick="eliminarCliente({{ $diseno }})">
                                        <i class="fa-regular fa-trash-can fa-lg" style="color: black"></i>
                                    </button>
                                    <a href="{{ route('disenos.edit',$diseno) }}" class="btn btn-sm btn-warning">
                                        <i class="fa-solid fa-pen-to-square fa-lg"></i>
                                    </a>
                                
                                </div>
                            </td>
                        </tr> 
                    @endforeach
                    
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('js')
<script src="/js/modulos/disenos.js"></script>
@endsection