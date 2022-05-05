@extends('layout')

@section('title', 'Editar costo de  infraestructura | Inducolma')

@section('content')
<form action="{{ route('costos-de-infraestructura.update', $costosInfraestructura->id) }}" method="POST" >
    @csrf
    @method('PATCH')

        
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Editar costo de infraestructura</h5>
            <a href="{{ route('costos-de-infraestructura.index') }}" class="btn-close" ></a>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3"> 
                    <span class="input-group-text">Valor operativo:</span>                            
                    <input type="number" class="form-control" placeholder="Valor operativo" step="0.01" name="valorOperativo" id="valorOperativo" required value="{{ $costosInfraestructura->valor_operativo }}" >
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text">Tipo de material</span>                               
                    <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="tipoMaterial" id="tipoMaterial">
                        
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}"
                                {{ $costosInfraestructura->tipo_material == $item->id ? 'selected' : '' }}
                                >{{ $item->descripcion }}</option>
                        @endforeach 
                    </select>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">Tipo madera:</span>                               
                    <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="tipoMadera" id="tipoMadera">
                        
                        @foreach ($maderas as $madera)
                            <option value="{{ $madera->id }}"
                                {{ $costosInfraestructura->tipo_madera == $madera->id ? 'selected' : '' }}
                                >{{ $madera->nombre }}</option>
                        @endforeach 
                    </select>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">Proceso madera:</span>                               
                    <input type="text" class="form-control" placeholder="Proceso madera"  name="procesoMadera" id="procesoMadera" required value="{{ $costosInfraestructura->proceso_madera }}">
                </div>
                
                <div class="input-group mb-3"> 
                    <span class="input-group-text">Promedio piezas:</span>                            
                    <input type="number" class="form-control" placeholder="Promedio piezas" step="0.01" name="promedioPiezas" id="promedioPiezas" required value="{{ $costosInfraestructura->promedio_piezas }}">
                </div>
                <div class="input-group mb-3"> 
                    <span class="input-group-text">Minimo piezas:</span>                            
                    <input type="number" class="form-control" placeholder="Minimo piezas" step="0.01" name="minimoPiezas" id="minimoPiezas" required value="{{ $costosInfraestructura->minimo_piezas }}">
                </div>
                <div class="input-group mb-3"> 
                    <span class="input-group-text">Maximo piezas:</span>                            
                    <input type="number" class="form-control" placeholder="Maximo piezas" step="0.01" name="maximoPiezas" id="maximoPiezas" required value="{{ $costosInfraestructura->maximo_piezas }}">
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text">Maquina:</span>                                 
                    <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="idMaquina" id="idMaquina">
                        
                        @foreach ($maquinas as $maquina)
                            <option value="{{ $maquina->id }}"
                                {{ $costosInfraestructura->id_maquina == $maquina->id ? 'selected' : '' }}
                                >{{ $maquina->maquina }}</option>
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
            <a href="{{ route('costos-de-infraestructura.index') }}" class="btn btn-secondary" >Volver</a>
            <button type="submit" class="btn btn-primary">Modificar costo de infraestructura</button>
            </div>
        </div>
        </div>
      
</form>
@endsection