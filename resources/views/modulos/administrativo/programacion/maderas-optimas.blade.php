@extends('layouts.web')
@section('title', ' optimas | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content') 
    <div class="div container h-content ">        
        <div class="row">            
            <div class="col-12 col-sm-10 col-lg-6 mx-auto">
                
            
                <h4>Maderas disponibles para la creacion del item</h4>
                <p>
                    <strong>Descripción: </strong> {{ $optimas['item']->descripcion }} <br>
                    <strong>Cantidad:</strong> {{  $optimas['item']->cantidad }} <br>
                    
                    <a href="{{ route('programaciones.index') }}" class="btn btn-outline-secondary btn-sm">volver</a>
                </p>
                <hr>                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            - {{ $error }} <br>
                        @endforeach
                    </div>
                    
                @endif
            </div>

            <div class="row row-cols-1 row-cols-md-3 g-4">
                    @forelse ($optimas['sobrantes_usar'] as $sobrante)
                        <div class="col">
                            <div class="card ">
                            <div class="card-header bg-sucsses"><strong>Sobrante</strong> </div>
                            <div class="card-body " >
                                <h5 class="card-title">Cantidad items: {{ $sobrante->cantidad_items }}</h5>
                                <p class="card-text mb-1">
                                    Porcentaje de uso: {{ $sobrante->porcentaje_uso }} <br>
                                    Desperdicio: {{ $sobrante->desperdicio }} <br> 
                                                                       
                                </p>                                
                                <a href="#" class="btn btn-primary btn-sm">Seleccionar</a>                                
                            </div>
                            <div class="card-footer text-warning ">
                                <small class="text-muted"></small>
                                </div>
                            </div>
                        </div>
                    @empty
                        
                    @endforelse
                
                    @forelse ($optimas['maderas_usar'] as $optima)
                        
                        <div class="col">
                            <div class="card ">
                            <div class="card-header {{ $optima['color'] }}"><strong>Viaje #{{ $optima['entrada_madera_id'] }}</strong> </div>
                            <div class="card-body " >
                                <h5 class="card-title">Paqueta: {{ $optima['paqueta'] }}</h5>
                                <p class="card-text mb-1">
                                    <strong>Cantidad Items: </strong>{{ $optima['cantidad_items'] }} <br> 
                                    <strong>Porcentaje de uso: </strong>{{ $optima['porcentaje_uso'] }}% <br>
                                    <strong>Margen de error: </strong>{{ $optima['margen_error'] }}% <br> 
                                    <strong>Veces largo: </strong>{{ $optima['veces_largo'] }} <br> 
                                </p>                                 
                                <a href="#" class="btn btn-primary btn-sm">Seleccionar</a>
                                <button class="btn btn-outline-secondary btn-sm" onclick="verPaqueta({{ $optima['entrada_madera_id'] .','. $optima['paqueta'] }})">ver paqueta</button>
                            </div>
                            <div class="card-footer text-warning ">
                                <small class="text-muted"></small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <span class="text-muted">No hay maderas disponibles</span>
                    @endforelse
                    
            </div>
        </div>
        
    </div>

@endsection

@section('js')
<script src="/js/modulos/optimas.js"></script>
@endsection