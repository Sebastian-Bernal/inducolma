@extends('layouts.web')
@section('title', ' Recepcion | Inducolma')

@section('submenu')
    @include('modulos.sidebars.costos-side')
@endsection
@section('content')
    <div class="div container h-content ">
        <div class="row">
            <div class="col-12 col-sm-10 col-lg-6 mx-auto">


                <h1 class="display-6" >Recepci&oacute;n</h1>
                <a href="{{ route('recepcion.index') }}" class="btn btn-secondary">Volver a ingreso de usuarios</a>
                <hr>
                <!-- Button trigger modal -->

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            - {{ $error }} <br>
                        @endforeach
                    </div>

                @endif
                <!-- Modal Crea maquina-->
                <form action="{{ route('recepcion-consulta') }}" method="POST" id="formRecepcion">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <label for="inputEmail4" class="form-label">Desde:</label>
                            <input type="date"
                                    class="form-control"
                                    placeholder="Desde"
                                    aria-label="Desde"
                                    required
                                    id="desde"
                                    name="desde">
                        </div>
                        <div class="col">
                            <label for="inputEmail4" class="form-label">Hasta:</label>
                          <input type="date"
                                class="form-control"
                                placeholder="Hasta"
                                aria-label="Hasta"
                                required
                                id="hasta"
                                name="hasta">
                        </div>

                    </div>
                    <div class="col mt-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                            Buscar
                        </button>
                    </div>
                </form>
            </div>
            <!-- Tabla -->

            <table id="listarecepcion" class="table table-bordered table-striped dt-responsive">
                <thead>
                    <tr>
                        <th>Identificacion</th>
                        <th>Nombres y appellidos</th>
                        <th>Tipo de persona</th>
                        <th>Fecha y hora de ingreso</th>
                        <th>Fecha y hora de salida</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($recepciones as $recepcion)
                        <tr>
                            <td>
                                @if (strlen($recepcion->cc) == 7)
                                {{ '000'.$recepcion->cc }}
                                @elseif (strlen($recepcion->cc) == 8)
                                    {{ '00'.$recepcion->cc }}
                                @elseif (strlen($recepcion->cc) == 9)
                                    {{ '0'.$recepcion->cc }}
                                @else
                                    {{ $recepcion->cc }}
                                @endif

                            </td>
                            <td>{{ $recepcion->nombre_completo }}</td>
                            <td>
                                @if ($recepcion->visitante == 1)
                                    {{ 'VISITANTE' }}
                                @else
                                    {{ $recepcion->type }}
                                @endif
                            </td>
                            <td>{{ $recepcion->created_at }}</td>
                            <td>{{ $recepcion->deleted_at }}</td>
                            <td>
                                <div class="d-flex align-items-center ">

                                    {{-- <button class="btn btn-sm btn-primary" onclick="horaSalida({{ $recepcion }})">
                                        <i class="fa-solid fa-arrow-right-from-bracket" style="color: black"></i>
                                    </button> --}}
                                    {{-- <button class="btn btn-sm btn-danger" onclick="eliminarRecepcion({{ $recepcion }})">
                                        <i class="fa-regular fa-trash-can fa-lg" style="color: black"></i>
                                    </button> --}}
                                    {{-- <a href="{{ route('recepcion.edit',$recepcion) }}" class="btn btn-sm btn-warning">
                                        <i class="fa-solid fa-pen-to-square fa-lg"></i>
                                    </a> --}}

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
<script src="/js/modulos/recepcion.js"></script>
@endsection
