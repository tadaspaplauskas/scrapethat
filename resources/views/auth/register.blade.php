@extends('layouts.app')

@section('content')
<h1>Register</h1>

<form method="POST" action="{{ route('register') }}">

    {{ csrf_field() }}

    @if ($errors->has('name'))
        <p class="red">
            <strong>{{ $errors->first('name') }}</strong>
        </p>
    @endif
    @if ($errors->has('email'))
        <p class="red">
            <strong>{{ $errors->first('email') }}</strong>
        </p>
    @endif

    <label for="name">Name</label>
    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>

    <label for="email">E-Mail Address</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required>

    <label for="password">Password</label>
    <input id="password" type="password" name="password" required>

    <label for="password-confirm">Confirm Password</label>
    <input id="password-confirm" type="password" name="password_confirmation" required>

    <button type="submit" class="block">Register</button>
</form>
@endsection
