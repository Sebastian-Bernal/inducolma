@extends('layouts.web')
@section('title', ' Calificaciones | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content') 
<div class="div container h-content ">        
    <div class="row">            
        <div class="col-12 col-sm-10 col-lg-6 mx-auto">
            
           
            <h1 class="display-6" >Calificaciones</h1>
            <hr>
            
            
        </div>
        <!-- Tabla -->

        <table id="listaCalificaciones" class="table table-bordered table-striped dt-responsive">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Entrada de madera</th>
                    <th>Paqueta</th> 
                    <th>Total calificaci&oacute;n</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                    
                </tr>
            </thead>

            <tbody>
                @foreach ($calificaciones as $calificacion)
                    <tr>
                        <td>{{ $calificacion->id }}</td>
                        <td>{{ $calificacion->entrada_madera_id }}</td>
                        <td>{{ $calificacion->paqueta }}</td>
                        
                        <td>{{ $calificacion->total }}</td>
                        <td>
                            @if ($calificacion->aprobado == true)
                               {{' APROBADO'}}
                            @else
                                {{ 'NO APROBADO' }}
                            @endif
                        </td>
                       
                        <td>
                           
                            <div class="d-flex align-items-center ">
                                    
                                <a href="{{ route('calificaciones.edit',$calificacion) }}" class="btn btn-sm btn-warning">
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
<script src="/js/modulos/calificacion.js"></script>
@endsection