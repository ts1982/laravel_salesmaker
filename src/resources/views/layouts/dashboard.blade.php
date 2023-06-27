<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SalesMaker</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://kit.fontawesome.com/d5001603e3.js" crossorigin="anonymous"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" type="image/vnd.microsoft.icon">
    <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/vnd.microsoft.icon">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=BIZ+UDGothic:wght@400;700&family=Sawarabi+Gothic&display=swap');
    </style>
</head>

<body>
    <div id="app">
        <div id="dashboard">
            @component('components.dashboard.header')
            @endcomponent

            @if (Auth::guard('admins')->check())
                <main class="row">
                    <div class="sidebar col-md-2 p-0">
                        <div class="mt-5 py-5 shadow-sm h-100">
                            @component('components.dashboard.sidebar')
                            @endcomponent
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="mt-5 py-5">
                            @yield('content')
                        </div>
                    </div>
                </main>
            @endif

            @guest
                <div class="mt-5 py-5">
                    @yield('guest.content')
                </div>
            @endguest

        </div>
    </div>
</body>

</html>
