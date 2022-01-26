<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @yield('css')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="{{ mix('js/app.js') }}" defer=""></script>
    <title>@yield('title')</title>
    
    <style>
        .h-screen{
            height: 100vh;
        }

        .h-content{
            height: calc(100vh - 100px);
        }
    </style>
</head>
<body>

    <div id="app" class="d-flex flex-column h-screen justify-content-between ">
        {{-- <!-- Bienvenido {{$nombre ?? "invitado"}} con blade--> --}}
        <header>
            @include('partials.nav')
            @yield('submenu')
            @include('partials.status')
        </header>
        
        <main class="py-3">
            @yield('content')
            @yield('submenu')
        </main>

        <footer class="bg-white text-center text-black-50 py-3 shadow">
            {{ config('app.name') }} | Copyright @ {{ date('Y') }}
        </footer>
        
        
       
    </div>
   @yield('js') 
   
</body>
</html>