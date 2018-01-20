@extends('layouts.app')

@section('title', $snapshot->name)

@section('content')

<h5>Analyze</h5>
<p>
    Here you
</p>

<form method="GET">
    <label for="password" class="">CSS selector</label>
    <input type="text" name="css_selector" id="css_selector" placeholder="body > p" required>
    <button type="submit" class="block">Fetch</button>
</form>


<p>
    <a href="#delete">Delete snapshot</a>
</p>

<div id="delete" class="hidden">
    <form action="{{ route('snapshots.destroy', $snapshot) }}" method="POST">
        
        {{ method_field('DELETE') }}
        {{ csrf_field() }}

        <p>
            Are you sure you want to delete this snapshot?
        </p>

        <button class="bg-pink">Delete</button>
    </form>
</div>
@endsection
