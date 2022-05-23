@extends('layouts.web')

@section('title', 'Editar maquina | Inducolma')
@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content')
<form action="{{ route('maquinas.update', $maquina->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

        <div class="container ">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel">Editar Maquina</h4>
                    
                    </div>
                    <div class="modal-body">
                        <div class="input-group mb-3"> 
                            <span class="input-group-text">Maquina:</span>                               
                            <input type="text" 
                                    class="form-control text-uppercase" 
                                    placeholder="Nombre maquina" 
                                    name="maquina" 
                                    id="maquina" 
                                    required 
                                    value="{{ $maquina->maquina }}">
                        </div>
                    </div>
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            - {{ $error }} <br>
                        @endforeach
                    </div>
                    
                @endif
                    <div class="modal-footer">
                    <a href="{{ route('maquinas.index') }}" class="btn btn-secondary" >Volver</a>
                    <button type="submit" class="btn btn-primary">Modificar maquina</button>
                    </div>
                </div>
            </div>
        </div>
        
      
</form>
@endsection