<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link rel="stylesheet" href="css/app.css">
  </head>
  <body>
    <!--[if lte IE 9]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->
    <section class="container mt1">
      <nav>
        <ul>
          <li class="inline mr1"><a href="#">Home</a></li>
          <li class="inline mr1"><a href="{{ route('login') }}">Log in</a></li>
          <li class="inline mr1"><a href="{{ route('signup') }}">Sign up</a></li>
        </ul>
      </nav>
    </section>

    <section class="container mt1">
      @yield('content')
    </section>
  </body>
</html>
