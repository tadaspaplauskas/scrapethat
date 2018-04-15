@extends('layouts.app')

@section('title', 'Create a new variable')

@section('content')

<h5>New variable</h5>
<p>
    Select data out of snapshots using CSS selectors.
</p>

<form method="POST" action="{{ route('variables.store', $snapshot) }}">

    {{ csrf_field() }}

    <label for="name">Name</label>
    <input type="text" name="name" id="name" placeholder="Name" value="{{ old('name') }}" required>

    <label for="selector">CSS selector</label>
    <input type="text" name="selector" id="selector" placeholder=".selector" value="{{ old('selector') }}" required>
    
    <button type="submit" class="block">Fetch</button>
</form>

@endsection
