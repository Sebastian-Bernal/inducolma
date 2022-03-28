<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @yield('css')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="{{ mix('js/app.js') }}" defer=""></script>
    <!-- LIBRERIAS -->

    <!-- jquery -->
    <script src="/js/jquery-3.6.0.min.js"></script>

    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="/DataTables/datatables.min.css"/> 
    <script src="/DataTables/datatables.min.js"></script>
    
    <!-- select2 -->
    <link rel="stylesheet" href="/select2/dist/css/select2.min.css">
    <script src="/select2/dist/js/select2.js" defer=""></script>
    
    <!-- sweetalert -->
    <script src="/sweetalert/sweetalert2.all.min.js"></script>

    
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
<body  >

    <div id="app" class="d-flex flex-column h-screen justify-content-between  ">
        
        {{-- <!-- Bienvenido {{$nombre ?? "invitado"}} con blade--> --}}
        <header>
            @include('partials.nav')
            @yield('submenu')
            @include('partials.status')
        </header>
        
        <main class="py-3  ">
            
            @yield('content')
            @yield('submenu')
        </main>

        <footer class="bg-white text-center text-black-50 py-3 shadow">
            Inducolma | Copyright @ {{ date('Y') }}
        </footer>
        
        
       
    </div>
   @yield('js') 
   
</body>
</html>