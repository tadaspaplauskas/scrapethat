@extends('layouts.app')

@section('content')
<h1>Reset Password</h1>

<form method="POST" action="{{ route('password.email') }}">
    
    {{ csrf_field() }}

    @if ($errors->has('email'))
        <p class="red">
            <strong>{{ $errors->first('email') }}</strong>
        </p>
    @endif

    <label for="email" class="">E-Mail Address</label>
    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>    

    <button type="submit" class="block">Send Password Reset Link</button>

</form>
@endsection
