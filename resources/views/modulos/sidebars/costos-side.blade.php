<div class="offcanvas offcanvas-start box-shadow-lg" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
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
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                  Administrar costos
                </button>
              </h2>
              <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                  <div class="mt-3">                
                    <a href="{{ route('maquinas.index') }}" class="btn btn-primary ">Maquinas</a>
                  </div>
                  <div class="mt-3">
                      <a href="{{ route('operaciones.index') }}" class="btn btn-primary">Operaciones</a>
                  </div>
                  <div class="mt-3">
                      <a href="{{ route('descripciones.index') }}" class="btn btn-primary ">Descripciones</a>
                  </div>
                  <div class="mt-3">
                      <a href="{{ route('costos-de-operacion.index') }}" class="btn btn-primary ">Costos operaci&oacute;n</a>
                  </div>
                  <div class="mt-3">
                      <a href="{{ route('costos-de-infraestructura.index') }}" class="btn btn-primary ">Costos infraestructura</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                  Usuarios y roles
                </button>
              </h2>
              <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                  <p>
                    <a class="btn btn-primary" href="{{ route('usuarios.index') }}">
                        Usuarios
                    </a>
                  </p>
                  <p>
                    <a class="btn btn-primary" href="{{ route('roles.index') }}">
                        Roles
                    </a>
                  </p>
                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                  Proveedores y clientes
                </button>
              </h2>
              <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                  <p>
                    <a class="btn btn-primary" href="{{ route('proveedores.index') }}">
                        Proveedores
                    </a>
                  </p>
                  <p>
                    <a class="btn btn-primary" href="{{ route('clientes.index') }}">
                        Clientes
                    </a>
                  </p>

                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingFour">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                  Eventos y estados
                </button>
              </h2>
              <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                  <p>
                    <a class="btn btn-primary" href="{{ route('tipo-eventos.index') }}">
                        Tipo eventos
                    </a>
                  </p>
                  <p>
                    <a class="btn btn-primary" href="{{ route('eventos.index') }}">
                        Eventos
                    </a>
                  </p>
                  <p>
                    <a class="btn btn-primary" href="{{ route('estados.index') }}">
                        Estados
                    </a>
                  </p>

                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingFive">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive">
                  Insumos almacen, items, maderas
                </button>
              </h2>
              <div id="flush-collapseFive" class="accordion-collapse collapse" aria-labelledby="flush-headingFive" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                  <p>
                    <a class="btn btn-primary" href="{{ route('insumos-almacen.index') }}">
                        Insumos almacen
                    </a>
                  </p>
                  <p>
                    <a class="btn btn-primary" href="{{ route('items.index') }}">
                        Items
                    </a>
                  </p>
                  <p>
                    <a class="btn btn-primary" href="{{ route('maderas.index') }}">
                        Maderas                        
                    </a>
                  </p>

                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingSix">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSix" aria-expanded="false" aria-controls="flush-collapseSix">
                  Pedidos y diseños de items
                </button>
              </h2>
              <div id="flush-collapseSix" class="accordion-collapse collapse" aria-labelledby="flush-headingSix" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                  <p>
                    <a class="btn btn-primary" href="{{ route('pedidos.index') }}">
                        Pedidos
                    </a>
                  </p>
                  <p>
                    <a class="btn btn-primary" href="#">
                        Diseños de productos para clientes 
                    </a>
                  </p>
                </div>
              </div>
            </div>

            

          </div>    
         
          
         
        @endcan
        
        @can('entrada-maderas')
          <p>
            <a class="btn btn-primary" href="#">
                Recepci&oacute;n de personal
            </a>
          </p>
          <p>
            <a class="btn btn-primary" href="{{ route('entradas-maderas.index') }}">
                Entrada de madera
            </a>
          </p>
        @endcan

        @can('cubicaje')
        <p>
          <a href="{{ route('cubicaje.index') }}" class="btn btn-primary">{{ __('Cubicaje') }}</a>
        </p>
            
        </li> 
        @endcan

        @guest
        @else
            <li class="nav nav-pills">
              <a class="nav-link link-light"  href="#" onclick="event.preventDefault();
                  document.getElementById('logout-form').submit();">
                  <i class="fa-solid fa-person-walking-dashed-line-arrow-right"></i>
                  {{ __('Cerrar sesión') }}
              </a>
            </li>
    @endguest
    </div>

    
</div>
