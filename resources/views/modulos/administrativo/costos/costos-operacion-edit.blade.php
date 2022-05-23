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
            <a href="{{ route('costos-de-operacion.index') }}" class="btn-close"></a>
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
                        @foreach ($maquinas as $maquina)
                            <option value="{{ $maquina->id }}"
                                {{ $costosOperacion->pluck('maquina_id')->contains($maquina->id) ? 'selected' : ''}}
                                >{{ $maquina->maquina }}</option>
                        @endforeach 
                    </select>
                </div>
                <div class="input-group mb-3"> 
                    <span class="input-group-text">Proceso:</span>                               
                    <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="idOperacion" id="idOperacion">
                        @foreach ($operaciones as $operacion)
                            <option value="{{ $operacion->id }}"
                                {{ $operacion->id == $costosOperacion->descripcion->operacion->id ? 'selected' : ''}}
                                >{{ $operacion->operacion }}</option>
                        @endforeach 
                    </select>
                </div>
                <div  id="spiner"></div>
                <div class="input-group mb-3">  
                    <span class="input-group-text">Descripci&oacute;n</span>                              
                    <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="idDescripcion" id="idDescripcion">
                        @foreach ($descripciones as $descripcion)
                        <option value="{{ $descripcion->id }}" 
                            {{ $costosOperacion->pluck('descripcion_id')->contains($descripcion->id) ? 'selected' : ''}}>
                            {{ $descripcion->descripcion }}                                                
                        </option> 
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
            <a href="{{ route('costos-de-operacion.index') }}" class="btn btn-secondary" >Volver</a>
            <button type="submit" class="btn btn-primary">Modificar costo de operaci&oacute;n</button>
            </div>
        </div>
        </div>
      
</form>

<script>
       
    $('#idOperacion').change(function () {
        $('#idDescripcion').empty();
        $('#spiner').append(
            '<div class="spinner-border spinner-border-sm text-primary" id="spiner" role="status">'+
                '<span class="visually-hidden">Loading...</span>'+
            '</div>'
                
        );
        $.ajax({
            url:'/descripciones',
            data:{
                idOperacion: document.getElementById('idOperacion').value,
                _token: $('input[name="_token"]').val()
            },
            type:'post',
            success: function (descripciones) {
                
                if (descripciones.length > 0 ) {
                    $('#spiner').empty();
                    $.each(descripciones, function (i, desc) {
                    $('#idDescripcion').append($('<option>', { 
                        value: desc.id,
                        text : desc.descripcion 
                    }));
    
                });
                } else {
                    $('#spiner').empty();
                    alert('Este proceso no tiene descripciones, por favor cree las desripciones');
                }
                
    
    
            } 
        });
    });
    </script>
@endsection