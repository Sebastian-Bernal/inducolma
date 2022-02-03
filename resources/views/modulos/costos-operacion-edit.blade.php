@extends('layout')

@section('title', 'Editar operaci&oacute;n | Inducolma')
@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content')
<form action="{{ route('costos-de-operacion.update', $costosOperacion->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

        
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Editar costo de operaci&oacute;n</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3"> 
                    <span class="input-group-text">Cantidad:</span>                            
                    <input type="number" class="form-control" placeholder="Cantidad" step="0.01" name="cantidad" id="cantidad" required value="{{ $costosOperacion->cantidad }}">
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text">Valor mes:</span>                               
                    <input type="number" class="form-control" placeholder="valor mes" step="0.01" name="valorMes" id="valorMes" required value="{{ $costosOperacion->valor_mes }}">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">Valor dia:</span>                               
                    <input type="number" class="form-control" placeholder="valor dia" step="0.01" name="valorDia" id="valorDia" required value="{{ $costosOperacion->valor_dia }}">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">Costo Kw/h:</span>                               
                    <input type="number" class="form-control" placeholder="costo kw/h" step="0.01" name="costokwh" id="costokwh" required value="{{ $costosOperacion->costo_kwh }}">
                </div>
                
                <div class="input-group mb-3"> 
                    <span class="input-group-text">Maquina:</span>                                
                    <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="idMaquina" id="idMaquina">
                        <option value="{{ $costosOperacion->maquina_id }}" selected>{{ $costosOperacion->maquina->maquina }}</option>
                        @foreach ($maquinas as $maquina)
                            <option value="{{ $maquina->id }}">{{ $maquina->maquina }}</option>
                        @endforeach 
                    </select>
                </div>
                <div class="input-group mb-3"> 
                    <span class="input-group-text">Proceso:</span>                               
                    <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="idOperacion" id="idOperacion">
                        <option selected>Seleccione...</option>
                        @foreach ($operaciones as $operacion)
                            <option value="{{ $operacion->id }}">{{ $operacion->operacion }}</option>
                        @endforeach 
                    </select>
                </div>
                <div class="input-group mb-3">  
                    <span class="input-group-text">Descripci&oacute;n</span>                              
                    <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="idDescripcion" id="idDescripcion">
                        
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
            <a href="{{ route('costos-de-operacion.index') }}" class="btn btn-secondary" >Volver</a>
            <button type="submit" class="btn btn-primary">Modificar costo de operaci&oacute;n</button>
            </div>
        </div>
        </div>
      
</form>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script>
       
    $('#idOperacion').change(function () {
        $('#idDescripcion').empty();
        $.ajax({
            url:'/descripciones',
            data:{
                idOperacion: document.getElementById('idOperacion').value,
                _token: $('input[name="_token"]').val()
            },
            type:'post',
            success: function (descripciones) {
                
                if (descripciones.length > 0 ) {
                    $.each(descripciones, function (i, desc) {
                    $('#idDescripcion').append($('<option>', { 
                        value: desc.id,
                        text : desc.descripcion 
                    }));
    
                });
                } else {
                    alert('Este proceso no tiene descripciones, por favor cree las desripciones');
                }
                
    
    
            } 
        });
    });
    </script>
@endsection