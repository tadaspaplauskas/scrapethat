@extends('layouts.app', ['title' => 'Delete query'])

@section('content')

<p>
    Be careful - this is not reversible!
</p>

<form action="{{ route('queries.destroy', $query) }}" method="POST" class="m0">

    {{ csrf_field() }}
    {{ method_field('DELETE') }}

    <button class="bg-pink">Delete</button>
</form>

@endsection
