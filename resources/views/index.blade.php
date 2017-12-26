<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link rel="stylesheet" href="css/app.css">
  </head>
  <body>
    <div class="container">
      <nav>
        <a href="#">Home</a>
        <span>|</span>
        <a href="{{ route('login') }}">Log in</a>
        <span>|</span>
        <a href="{{ route('signup') }}">Sign up</a>
      </nav>
    </div>
  </body>
</html>
