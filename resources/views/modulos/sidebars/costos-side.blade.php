<div class="offcanvas offcanvas-start box-shadow-lg" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1"
    id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
    <div class="offcanvas-header">
        @guest
        <h5 class="offcanvas-title" id="offcanvasScrollingLabel">Invitado</h5>
        @else
        <h5 class="offcanvas-title" id="offcanvasScrollingLabel">{{ Auth::user()->name }}</h5>
        @endguest

        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        @can('admin')
        <div class="accordion accordion-flush " id="accordionFlushExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingOne">
                    <button class="accordion-button collapsed seleccionado" type="button" data-bs-toggle="collapse"
                        data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                        Administrar costos
                    </button>
                </h2>
                <div id="flush-collapseOne" class="accordion-collapse collapse " aria-labelledby="flush-headingOne"
                    data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        <div class="mt-3">
                            <a href="{{ route('maquinas.index') }}" class="btn btn-primary seleccionado ">Maquinas</a>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('operaciones.index') }}"
                                class="btn btn-primary seleccionado">Operaciones</a>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('descripciones.index') }}"
                                class="btn btn-primary seleccionado">Descripciones</a>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('costos-de-operacion.index') }}"
                                class="btn btn-primary seleccionado">Costos operaci&oacute;n</a>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('costos-de-infraestructura.index') }}"
                                class="btn btn-primary seleccionado">Costos infraestructura</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingTwo">
                    <button class="accordion-button collapsed seleccionado" type="button" data-bs-toggle="collapse"
                        data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                        Usuarios - roles - contratistas
                    </button>
                </h2>
                <div id="flush-collapseTwo" class="accordion-collapse collapse " aria-labelledby="flush-headingTwo"
                    data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        <p>
                            <a class="btn btn-primary seleccionado" href="{{ route('usuarios.index') }}">
                                Usuarios
                            </a>
                        </p>
                        <p>
                            <a class="btn btn-primary seleccionado" href="{{ route('roles.index') }}">
                                Roles
                            </a>
                        </p>
                        <p>
                            <a class="btn btn-primary seleccionado" href="{{ route('contratistas.index') }}">
                                Contratistas
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingThree">
                    <button class="accordion-button collapsed seleccionado" type="button" data-bs-toggle="collapse"
                        data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                        Proveedores, cliente
                    </button>
                </h2>
                <div id="flush-collapseThree" class="accordion-collapse collapse " aria-labelledby="flush-headingThree"
                    data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        <p>
                            <a class="btn btn-primary seleccionado" href="{{ route('proveedores.index') }}">
                                Proveedores
                            </a>
                        </p>
                        <p>
                            <a class="btn btn-primary seleccionado" href="{{ route('clientes.index') }}">
                                Clientes
                            </a>
                        </p>

                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingFour">
                    <button class="accordion-button collapsed seleccionado" type="button" data-bs-toggle="collapse"
                        data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                        Eventos y estados
                    </button>
                </h2>
                <div id="flush-collapseFour" class="accordion-collapse collapse " aria-labelledby="flush-headingFour"
                    data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        <p>
                            <a class="btn btn-primary seleccionado" href="{{ route('tipo-eventos.index') }}">
                                Tipo eventos
                            </a>
                        </p>
                        <p>
                            <a class="btn btn-primary seleccionado" href="{{ route('eventos.index') }}">
                                Eventos
                            </a>
                        </p>
                        <p>
                            <a class="btn btn-primary seleccionado" href="{{ route('estados.index') }}">
                                Estados
                            </a>
                        </p>

                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingFive">
                    <button class="accordion-button collapsed seleccionado" type="button" data-bs-toggle="collapse"
                        data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive">
                        Insumos almacen, items, maderas
                    </button>
                </h2>
                <div id="flush-collapseFive" class="accordion-collapse collapse " aria-labelledby="flush-headingFive"
                    data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        <p>
                            <a class="btn btn-primary seleccionado" href="{{ route('insumos-almacen.index') }}">
                                Insumos almacen
                            </a>
                        </p>
                        <p>
                            <a class="btn btn-primary seleccionado" href="{{ route('items.index') }}">
                                Items
                            </a>
                        </p>
                        <p>
                            <a class="btn btn-primary seleccionado" href="{{ route('tipos-maderas.index') }}">
                                Tipos de madera
                            </a>
                        </p>
                        <p>
                            <a class="btn btn-primary seleccionado" href="{{ route('maderas.index') }}">
                                Maderas
                            </a>
                        </p>

                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingSix">
                    <button class="accordion-button collapsed seleccionado" type="button" data-bs-toggle="collapse"
                        data-bs-target="#flush-collapseSix" aria-expanded="false" aria-controls="flush-collapseSix">
                        Pedidos y diseños de items
                    </button>
                </h2>
                <div id="flush-collapseSix" class="accordion-collapse collapse " aria-labelledby="flush-headingSix"
                    data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        <p>
                            <a class="btn btn-primary seleccionado" href="{{ route('pedidos.index') }}">
                                Pedidos
                            </a>
                        </p>
                        <p>
                            <a class="btn btn-primary seleccionado" href="{{ route('disenos.index') }}">
                                Diseños de productos para clientes
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingSeven">
                    <button class="accordion-button collapsed seleccionado" type="button" data-bs-toggle="collapse"
                        data-bs-target="#flush-collapseSeven" aria-expanded="false" aria-controls="flush-collapseSeven">
                        Producción
                    </button>
                </h2>
                <div id="flush-collapseSeven" class="accordion-collapse collapse " aria-labelledby="flush-headingSeven"
                    data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        <p>
                            <a class="btn btn-primary seleccionado" href="{{ route('programaciones.index') }}">
                                Programaciones de órdenes de producción
                            </a>
                        </p>

                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingEight">
                    <button class="accordion-button collapsed seleccionado" type="button" data-bs-toggle="collapse"
                        data-bs-target="#flush-collapseEight" aria-expanded="false" aria-controls="flush-collapseEight">
                        Reportes
                    </button>
                </h2>
                <div id="flush-collapseEight" class="accordion-collapse collapse " aria-labelledby="flush-headingEight"
                    data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        <p>
                            <a class="btn btn-primary seleccionado" href="{{ route('reportes-administrativos') }}">
                                Reportes administrativos
                            </a>
                        </p>
                        <p>
                            <a class="btn btn-primary seleccionado" href="{{ route('reportes-ventas') }}">
                                Reportes ventas
                            </a>
                        </p>
                        <p>
                            <a class="btn btn-primary seleccionado" href="{{ route('reportes-procesos') }}">
                                Reportes procesos
                            </a>
                        </p>

                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('entrada-maderas')
        <p>
            <a class="btn btn-primary seleccionado" href="{{ route('recepcion.index') }}">
                Recepci&oacute;n de personal
            </a>
        </p>
        <p>
            <a class="btn btn-primary seleccionado" href="{{ route('entradas-maderas.index') }}">
                Entrada de madera
            </a>
        </p>
        <p>
            <a class="btn btn-primary seleccionado" href="{{ route('recepcion-reporte') }}">
                Reporte ingreso de personas a las instalaciones
            </a>
        </p>
        @endcan

        @can('cubicaje')
        <p>
            <a href="{{ route('cubicaje.index') }}" class="btn btn-primary seleccionado">{{ __('Cubicaje') }}</a>
        </p>
        <p>
            <a href="{{ route('calificaciones.index') }}" class="btn btn-primary seleccionado">{{ __('Calificaciones')
                }}</a>
        </p>
        </li>
        @endcan

        @guest
        @else
        <hr>
        <li class="nav nav-pills">
            <a class="nav-link link-light" href="#" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();"
                style="background-color: var(--bs-warning); box-sizing:border-box; width: 100%">
                <i class="fa-solid fa-person-walking-dashed-line-arrow-right"></i>
                {{ __('Cerrar sesión') }}
            </a>
        </li>
        @endguest
    </div>


</div>
