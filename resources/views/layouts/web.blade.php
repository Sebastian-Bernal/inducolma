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
    <link rel="stylesheet" type="text/css" href="/DataTables/datatables.min.css" />
    <script src="/DataTables/datatables.min.js"></script>

    <!-- select2 -->
    <link rel="stylesheet" href="/select2/dist/css/select2.min.css">
    <script src="/select2/dist/js/select2.js" defer=""></script>
    <link rel="stylesheet" href="/select2/theme/select2-bootstrap-5-theme.min.css" />

    <!-- sweetalert -->
    <script src="/sweetalert/sweetalert2.all.min.js"></script>

    <!-- fontawesome -->
    <link rel="stylesheet" href="/fontawesome/css/all.css">
    <script src="/fontawesome/js/all.js"></script>

    <title>@yield('title')</title>

    <style>
        .h-screen {
            height: 100vh;
        }

        .h-content {
            height: calc(100vh - 100px);
        }
    </style>
</head>

<body>

    <div id="app" class="d-flex flex-column h-screen justify-content-between  ">

        {{--
        <!-- Bienvenido {{$nombre ?? "invitado"}} con blade--> --}}
        <header>
            @include('partials.nav')
            @yield('submenu')
            @include('partials.status')
        </header>

        <main class="py-3 h-content ">

            @yield('content')
            @yield('submenu')
        </main>
    </div>
    {{-- <footer class="bg-white text-center text-black-50 py-3 shadow">
        Inducolma | Copyright @ {{ date('Y') }}
    </footer> --}}
    <script src="/js/helpers/AlertSimpleRequestManager.js"></script>
    <script src="/js/helpers/RequestAjax.js"></script>
    @yield('js')

    <script>
        function verificarLocalStorage() {

            if (localStorage.length == 0) {
                document.getElementById('logout-form').submit();
            } else{
                Swal.fire({
                    title: '¿Está seguro de cerrar sesión?',
                    text: "¡Tiene datos en memoria que no se han guardado, No podrá revertir esta acción!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#597504',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '¡Si, salir!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        localStorage.clear();
                        document.getElementById('logout-form').submit();
                    }
                })
            }

    }
    </script>
</body>

</html>
