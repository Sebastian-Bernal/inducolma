
    <div class="offcanvas offcanvas-start box-shadow-lg" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
        <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasScrollingLabel"></h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            @can('admin')
            <p>
                <a class="btn btn-primary" data-bs-toggle="collapse" href="#collapseCostos" role="button" aria-expanded="false" aria-controls="collapseExample">
                    Costos
                </a>                
            </p>
            <div class="collapse" id="collapseCostos">
                <div class="card card-body">
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
            
                <p>
                    <a class="btn btn-primary" href="{{ route('usuarios.index') }}">
                        Usuarios
                    </a>
                </p>
            
            
            <p>
                <a class="btn btn-primary" href="{{ route('proveedores.index') }}">
                    Proveedores
                </a>
            </p>

            <p>
                <a class="btn btn-primary" href="{{ route('maderas.index') }}">
                    Maderas
                </a>
            </p>
            
            <p>
                <a class="btn btn-primary" href="{{ route('roles.index') }}">
                    Roles
                </a>
            </p>
            @endcan
        </div>
  </div>
