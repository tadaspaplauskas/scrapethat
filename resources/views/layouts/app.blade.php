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
                <li class="inline mr1"><a href="{{ route('websites.index') }}">Websites</a></li>
                <li class="inline mr1"><a href="{{ route('logout') }}">Log out</a></li>
            @endauth
            </ul>
        </nav>
    </header>

    <main class="container">
        <h1>@yield('title')</h1>

        @yield('content')
    </main>

    <footer class="container"><!-- TODO?.. --></footer>    

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
