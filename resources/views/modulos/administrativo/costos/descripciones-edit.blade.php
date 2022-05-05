@extends('layout')

@section('title', 'Editar descripción | Inducolma')
@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content')
<form action="{{ route('descripciones.update', $descripcion->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

        
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Editar descripción</h5>
            <a href="{{ route('descripciones.index') }}" class="btn-close"></a>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3"> 
                    <span class="input-group-text">Descripci&oacute;n:</span>                               
                    <input type="text" class="form-control" placeholder="Nombre descripcion" name="descripcion" id="descripcion" required value="{{ $descripcion->descripcion }}">
                </div>
                <div class="input-group mb-3"> 
                    <span class="input-group-text">Operaci&oacute;n:</span>                               
                    <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="idOperacion" id="idOperacion">
                        @foreach ($operaciones as $operacion)
                            <option value="{{ $operacion->id }}">{{ $operacion->operacion }}</option>
                        @endforeach
                    </select>
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
            <a href="{{ route('descripciones.index') }}" class="btn btn-secondary" >Volver</a>
            <button type="submit" class="btn btn-primary">Modificar Operaci&oacute;n</button>
            </div>
        </div>
        </div>
      
</form>
@endsection