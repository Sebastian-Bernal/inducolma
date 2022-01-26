@extends('layout')

@section('title', 'Editar operaci&oacute;n | Inducolma')

@section('content')
<form action="{{ route('descripciones.update', $descripcion->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

        
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Editar Operaci&oacute;n</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">                               
                    <input type="text" class="form-control" placeholder="Nombre descripcion" name="descripcion" id="descripcion" required value="{{ $descripcion->descripcion }}">
                </div>
                <div class="input-group mb-3">                               
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