@extends('layouts.web')
@section('title', ' Calificacion | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content') 
<div class="div container h-content ">
        
    <div class="row"> 
       
             
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        - {{ $error }} <br>
                    @endforeach
                </div>
            @endif
        
   
    
    
    <!-- Modal calificacion paqueta -->
    
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Modificar calificaci&oacute;n</h5>
            
            <a href="{{ route('calificaciones.index') }}" class="btn-close" ></a>
            
        </div>
        
        <div class="modal-body">
            <h5>
                Entrada de madera: <b>{{ $calificacion->entrada_madera_id }}</b>
                Paqueta: <b>{{ $calificacion->paqueta }}</b>
                Estado: <b>{{ $calificacion->aprobado == true ? 'APROBADO' : 'NO APROBADO' }}</b>
            </h5>
            <hr>
            <form id="formCalificacion" action="{{ route('calificaciones.update', $calificacion) }}" method="POST">
                @csrf
                @method('PUT')
                <h5>1. LONGITUD DE LA MADERA</h5>
                <div class="row g-2 mb-4">
                    <div class="col-auto">
                        <select class="form-select" 
                                id="longitudMadera"
                                name="longitudMadera"
                                onchange="sumarPuntos()">
                        <option selected value="0">Seleccione...</option>
                        <option value="25" {{ $calificacion->longitud_madera == 25 ? 'selected' : '' }}>5 centimetros mayor a la solicitada</option>
                        <option value="19" {{ $calificacion->longitud_madera == 19 ? 'selected' : '' }}>3 centimetros mayor a la solicitada</option>
                        <option value="12" {{ $calificacion->longitud_madera == 12 ? 'selected' : '' }}>igual a la solicitada</option>
                        <option value="5"  {{ $calificacion->longitud_madera == 5 ? 'selected' : '' }}>menor a la solicitada</option>
                        </select>
                    </div>
                    
                </div>

                <h5>2. APARIENCIA FISICA</h5>
                <div class="row g-2 mb-4">
                    <label for="cantonera" class="form-label">Existencia de cantonera</label>
                    <div class="col-auto">
                        <select class="form-select" 
                                id="cantonera"
                                name="cantonera"
                                onchange="sumarPuntos()">
                            <option selected value="0">Seleccione...</option>
                            <option value="6.25" {{ $calificacion->cantonera == 6.25 ? 'selected' : '' }}>0%</option>
                            <option value="5" {{ $calificacion->cantonera == 5 ? 'selected' : '' }}>5%</option>
                            <option value="3.75" {{ $calificacion->cantonera == 3.75 ? 'selected' : '' }}>10%</option>
                            <option value="2.5" {{ $calificacion->cantonera == 2.5 ? 'selected' : '' }}>20%</option>
                            <option value="1.25" {{ $calificacion->cantonera == 1.25 ? 'selected' : '' }}>mayor a 20%</option>
                        </select>
                    </div>
                    <label for="hongos" class="form-label">Presencia de hogos</label>
                    <div class="col-auto">
                        <select class="form-select" 
                                id="hongos"
                                name="hongos"
                                onchange="sumarPuntos()">
                                <option selected value="0">Seleccione...</option>
                                <option value="6.25" {{ $calificacion->hongos == 6.25 ? 'selected' : '' }}>0%</option>
                                <option value="5" {{ $calificacion->hongos == 5 ? 'selected' : '' }}>5%</option>
                                <option value="3.75" {{ $calificacion->hongos == 3.75 ? 'selected' : '' }}>10%</option>
                                <option value="2.5" {{ $calificacion->hongos == 2.5 ? 'selected' : '' }}>20%</option>
                                <option value="1.25" {{ $calificacion->hongos == 1.25 ? 'selected' : '' }}>mayor a 20%</option>
                        </select>
                    </div>
                    <label for="rajadura" class="form-label">Presencia de rajaduras</label>
                    <div class="col-auto">
                        <select class="form-select" 
                                id="rajadura"
                                name="rajadura"
                                onchange="sumarPuntos()">
                                <option selected value="0">Seleccione...</option>
                                <option value="6.25" {{ $calificacion->rajadura == 6.25 ? 'selected' : '' }}>0%</option>
                                <option value="5" {{ $calificacion->rajadura == 5 ? 'selected' : '' }}>5%</option>
                                <option value="3.75" {{ $calificacion->rajadura == 3.75 ? 'selected' : '' }}>10%</option>
                                <option value="2.5" {{ $calificacion->rajadura == 2.5 ? 'selected' : '' }}>20%</option>
                                <option value="1.25" {{ $calificacion->rajadura == 1.25 ? 'selected' : '' }}>mayor a 20%</option>
                        </select>
                    </div>
                    <label for="bichos" class="form-label">Perforaci&oacute;n por bichos</label>
                    <div class="col-auto">
                        <select class="form-select" 
                                id="bichos"
                                name="bichos"
                                onchange="sumarPuntos()">
                                <option selected value="0">Seleccione...</option>
                                <option value="6.25" {{ $calificacion->bichos == 6.25 ? 'selected' : '' }}>0%</option>
                                <option value="5" {{ $calificacion->bichos == 5 ? 'selected' : '' }}>5%</option>
                                <option value="3.75" {{ $calificacion->bichos == 3.75 ? 'selected' : '' }}>10%</option>
                                <option value="2.5" {{ $calificacion->bichos == 2.5 ? 'selected' : '' }}>20%</option>
                                <option value="1.25" {{ $calificacion->bichos == 1.25 ? 'selected' : '' }}>mayor a 20%</option>
                        </select>
                    </div>
                </div>
                    
                <h5>3. ORGANIZACI&Oacute;N DE LA MADERA</h5>
                <div class="row g-2 mb-4">
                    <div class="col-auto">
                        <select class="form-select" 
                                id="organizacion"
                                name="organizacion"
                                onchange="sumarPuntos()">
                            <option selected value="0">Seleccione...</option>
                            <option value="25" {{ $calificacion->organizacion == 25 ? 'selected' : '' }}>Excelente</option>
                            <option value="19" {{ $calificacion->organizacion == 19 ? 'selected' : '' }}>Bueno</option>
                            <option value="12" {{ $calificacion->organizacion == 12 ? 'selected' : '' }}>Aceptable</option>
                            <option value="5"  {{ $calificacion->organizacion == 5 ? 'selected' : '' }}>Deficiente</option>
                        </select>
                    </div>
                                            
                </div>

                <h5>4. &Aacute;REA TRANSVERSAL</h5>
                <div class="row g-2 mb-4">
                    <label for="bichos" class="form-label">Bloques que salen del rango maximo y minimo establecido</label>
                    <div class="col-auto">
                        <select class="form-select" 
                                id="rangoMaxMin"
                                name="rangoMaxMin"
                                onchange="sumarPuntos()">
                            <option selected value="0">Seleccione...</option>
                            <option value="12.5"    {{ $calificacion->areas_transversal_max_min == 12.5 ? 'selected' : '' }}  >0%</option>
                            <option value="10"      {{ $calificacion->areas_transversal_max_min == 10 ? 'selected' : '' }}>5%</option>
                            <option value="7.5"     {{ $calificacion->areas_transversal_max_min == 7.5 ? 'selected' : '' }}>10%</option>
                            <option value="5"       {{ $calificacion->areas_transversal_max_min == 5 ? 'selected' : '' }}>20%</option>
                            <option value="2.5"     {{ $calificacion->areas_transversal_max_min == 2.5 ? 'selected' : '' }}>mayor a 20%</option>
                        </select>
                    </div>

                    <label for="bichos" class="form-label">Bloques con &aacute;reas no convencionales</label>
                    <div class="col-auto">
                        <select class="form-select" 
                                id="areas"
                                name="areas"
                                onchange="sumarPuntos()">
                            <option selected value="0">Seleccione...</option>
                            <option value="12.5"    {{ $calificacion->areas_no_conveniente == 12.5 ? 'selected' : '' }}  >0%</option>
                            <option value="10"      {{ $calificacion->areas_no_conveniente == 10 ? 'selected' : '' }}>5%</option>
                            <option value="7.5"     {{ $calificacion->areas_no_conveniente == 7.5 ? 'selected' : '' }}>10%</option>
                            <option value="5"       {{ $calificacion->areas_no_conveniente == 5 ? 'selected' : '' }}>20%</option>
                            <option value="2.5"     {{ $calificacion->areas_no_conveniente== 2.5 ? 'selected' : '' }}>mayor a 20%</option>
                        </select>
                    </div>
                                            
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">CALIFICACI&Oacute;N TOTAL</span>
                    <input type="text" 
                            class="form-control" 
                            readonly 
                            id="puntos" 
                            name= "puntos"
                            value="{{ $calificacion->total }}">
                </div>
            </form>
                
        </div>

        <div class="modal-footer">
            <a href="{{ route('calificaciones.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="button" class="btn btn-primary" onclick="validarFormulario()">Guardar calificaci&oacute;n</button>
        </div>
    </div>
      

</div>

@endsection

@section('js')
<script src="/js/modulos/cubicaje.js"></script>
<script src="/js/modulos/calificacion.js"></script>
@endsection