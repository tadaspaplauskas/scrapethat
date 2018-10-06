<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' - ScrapeThat' : 'ScrapeThat' }}</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <header class="container mt1">
        <nav>
            <ul>
                <li class="inline mr1"><a href="{{ route('home') }}">Home</a></li>
            @guest
                <li class="inline mr1"><a href="{{ route('login') }}">Log in</a></li>
                <li class="inline mr1"><a href="{{ route('register') }}">Sign up</a></li>
            @endguest
            @auth
                <li class="inline mr1"><a href="{{ route('snapshots.index') }}">Snapshots</a></li>
                <li class="inline mr1"><a href="{{ route('api') }}">API docs</a></li>
                <li class="inline mr1"><a href="{{ route('subscription') }}">Subscription</a></li>
                <li class="inline mr1"><a href="{{ route('logout') }}">Log out</a></li>
            @endauth
            </ul>
        </nav>
    </header>

    <main class="container">
        @include('layouts.components.notifications')

        <h2>@yield('title')</h2>

        @if (session('message'))
            <h5 class="center">
                {!! session('message') !!}
            </h5>
        @endif

        @if (session('status'))
            <h5 class="center">
                {!! session('status') !!}
            </h5>
        @endif

        @yield('content')
    </main>

    {{-- <footer class="container">2018</footer>     --}}
</body>
</html>
