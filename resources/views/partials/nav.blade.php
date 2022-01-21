<nav class="navbar navbar-light navbar-expand-lg bg-white shadow-sm"> 
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            {{ config('app.name') }}
        </a>
    
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end  " id="navbarSupportedContent">            
            <ul class="nav nav-pills ">
                <li class="nav-item">
                    <a class="nav-link {{ setActive('maquinas') }}" href="{{ route('maquinas') }}">{{ __('Maquinas') }}</a>
                </li>
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
                        <a class="nav-link {{ setActive('login') }}" href="{{ route('login') }}">Login</a>
                    </li> 
                    {{-- el metodo guest(invitado), solo muestra el login si el usuario no esta autenticado, tambien s puede usar @auth que es lo xcontrario --}}
                @else 
                    <li class="nav nav-pills">
                        <a class="nav-link" href="#" onclick="event.preventDefault();
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