<nav class="navbar navbar-light navbar-expand-lg bg-primary shadow-sm"> 
    <div class="container">
        @guest
            @else
            <button class="btn btn-outline-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">
                <span class="navbar-toggler-icon " ></span>
            </button>
            
        @endguest
        
        <a class="navbar-brand" href="{{ route('home') }}">
            {{ config('app.name') }}
        </a>
    
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>


        
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="/img/logo.png" alt="" width="150" class="d-inline-block align-text-top">
        </a>
        

        <div class="collapse navbar-collapse justify-content-end  " id="navbarSupportedContent">            
            <ul class="nav nav-tabs font-color">
                @guest
                    @else
                    @can('admin')
                    <li class="nav-item">
                        <a class="nav-link link-light {{ setActive('maquinas.index') }}" href="{{ route('maquinas.index') }}">{{ __('Costos') }}</a>
                    </li>
                    @endcan
                    @can('entrada-maderas')
                        <li class="nav-item">
                            <a class="nav-link link-light {{ setActive('entradas-maderas.index') }}" href="{{ route('entradas-maderas.index') }}">{{ __('Entrada maderas') }}</a>
                        </li> 
                    @endcan
                    
                @endguest
                
                {{-- <li class="nav-item">
                    <a class="nav-link {{ setActive('about') }}" href="{{ route('about') }}">{{ __('About') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ setActive('contact') }}" href="{{ route('contact') }}">{{ __('Contact') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ setActive('projects.*') }}" href="{{ route('projects.index') }}">{{ __('Projects') }}</a>
                </li> --}}
                <!-- .* para apuntar a todasl las rutas que inicien con projects--->
                @guest
                    <li class="nav nav-pills">
                        <a class="nav-link  link-light {{ setActive('login') }}" href="{{ route('login') }}">Login</a>
                    </li> 
                    {{-- el metodo guest(invitado), solo muestra el login si el usuario no esta autenticado, tambien s puede usar @auth que es lo xcontrario --}}
                @else 
                    <li class="nav nav-pills">
                        <a class="nav-link link-light"  href="#" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            Cerrar sesion
                        </a>
                    </li>
                @endguest                   
            </ul>           
        </div>
               
    </div>         
</nav>
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>