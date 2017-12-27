@extends('layouts.app')

@section('content')
<h1>Login</h1>

<form method="POST" action="{{ route('login') }}">
    
    {{ csrf_field() }}

    @if ($errors->has('password'))
        <p class="red">
            <strong>{{ $errors->first('password') }}</strong>
        </p>
    @endif
    @if ($errors->has('email'))
        <p class="red">
            <strong>{{ $errors->first('email') }}</strong>
        </p>
    @endif

    <label for="email" class="">E-Mail Address</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>

    <label for="password" class="">Password</label>
    <input id="password" type="password" class="" name="password" required>
    
    <label>
        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
    </label>

    <button type="submit" class="btn btn-primary">Log in</button>

    <p>
        <a href="{{ route('password.request') }}">Forgot Your Password?</a>
    </p>
</form>
@endsection
