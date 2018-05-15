@extends('layouts.app')

@section('content')
<h1>Reset Password</h1>

<form method="POST" action="{{ route('password.request') }}">
    
    {{ csrf_field() }}

    <input type="hidden" name="token" value="{{ $token }}">

    @if ($errors->has('email'))
        <p class="red">
            <strong>{{ $errors->first('email') }}</strong>
        </p>
    @endif

    <label for="email">E-Mail Address</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>

    <label for="password">Password</label>
    <input id="password" type="password" name="password" required>

    <label for="password-confirm">Confirm Password</label>
    <input id="password-confirm" type="password" name="password_confirmation" required>

    <button type="submit" class="block">Reset Password</button>
</form>
@endsection
