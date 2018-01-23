<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @if (isset($extra_dependencies))
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/alasql/0.4.4/alasql.min.js"></script>
    @endif
</head>
<body>
    <header class="container mt1">
        <nav>
            <ul>
            <li class="inline mr1"><a href="{{ route('index') }}">/</a></li>
            @guest
                <li class="inline mr1"><a href="{{ route('login') }}">Log in</a></li>
                <li class="inline mr1"><a href="{{ route('register') }}">Sign up</a></li>
            @endguest
            @auth
                <li class="inline mr1"><a href="{{ route('snapshots.index') }}">Snapshots</a></li>
                <li class="inline mr1"><a href="{{ route('logout') }}">Log out</a></li>
            @endauth
            </ul>
        </nav>
    </header>

    <main class="container">
        <h2>@yield('title')</h2>

        @if (session('message'))
            <h5 class="center">
                {!! session('message') !!}
            </h5>
        @endif

        @yield('content')
    </main>

    <footer class="container"><!-- TODO?.. --></footer>    

</body>
</html>
