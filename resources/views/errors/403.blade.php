@extends('layouts.web')

@section('content')

    <div class="div container h-content">
        <div class="row">            
            <div class="col-12 col-sm-10 col-lg-6 mx-auto">        
                <h1 class=" display-4 ">Esta acci&oacute;n no esta autorizada!! </h1>
                <a href="{{ route('home') }}">Volver al inicio</a>
            </div>
        </div>
    </div>
@endsection