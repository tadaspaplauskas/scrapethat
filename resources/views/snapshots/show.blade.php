@extends('layouts.app')

@section('title', $snapshot->name)

@section('content')







<h5>Danger zone</h5>
<form action="{{ route('snapshots.destroy', $snapshot) }}" method="POST">
    
    {{ method_field('DELETE') }}
    {{ csrf_field() }}

    <p>
        Are you sure?
    </p>

    <button class="bg-pinkish">Delete</button>
</form>

@endsection
