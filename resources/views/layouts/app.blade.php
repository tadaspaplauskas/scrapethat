<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <section class="container mt1">
        <nav>
            <ul>
            <li class="inline mr1"><a href="{{ route('index') }}">/</a></li>
            <li class="inline mr1"><a href="{{ route('home') }}">Home</a></li>
            @guest
                <li class="inline mr1"><a href="{{ route('login') }}">Log in</a></li>
                <li class="inline mr1"><a href="{{ route('register') }}">Sign up</a></li>
            @endguest
            </ul>
        </nav>
    </section>

    <section class="container mt1">
      @yield('content')
    </section>

    <footer class="container">
        todo
    </footer>    

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
