
    <div class="offcanvas offcanvas-start box-shadow-lg" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
        <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasScrollingLabel">Costos</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div >
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
