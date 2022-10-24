<nav class="navbar navbar-expand-lg navbar-light bg-primary  shadow-sm ">
    <div class="container-fluid ">
        @guest
        @else
        <button class="btn btn-outline-light" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">
            <span class="navbar-toggler-icon "></span>
        </button>
        @endguest
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="/img/logo.png" alt="" width="150" class="d-inline-block align-text-top">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse " id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 justify-content-end">

                @guest
                <li class="nav nav-pills">
                    <a class=" btn btn-light {{ setActive('login') }}" href="{{ route('login') }}">Login</a>
                </li>
                @else
                @can('admin')
                <li class="nav-item">
                    <a style="color: white" class="nav-link link-light  {{ setActive('maquinas.index') }}"
                        href="{{ route('maquinas.index') }}">{{ __('Costos') }}</a>
                </li>
                @endcan

                {{-- @can('entrada-maderas')
                <li class="nav-item">
                    <a style="color: white" class="nav-link link-light {{ setActive('entradas-maderas.index') }}"
                        href="{{ route('entradas-maderas.index') }}">{{ __('Entrada maderas') }}</a>
                </li>
                @endcan
                @can('cubicaje')
                <li class="nav-item">
                    <a style="color: white" class="nav-link link-light {{ setActive('cubicaje.index') }}"
                        href="{{ route('cubicaje.index') }}">{{ __('Cubicaje') }}</a>
                </li>
                @endcan --}}
                <li class="nav-item dropdown">

                    <a style="color: white" class="nav-link dropdown-toggle link-light" href="#" id="navbarDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">

                        <li class="nav nav-pills">
                            <a class="nav-link link-light" href="#" onclick="verificarLocalStorage();">
                                Cerrar sesion
                            </a>
                        </li>
                    </ul>
                </li>
                @endguest


            </ul>
        </div>
    </div>
</nav>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
