@extends('layouts.app', ['extra_dependencies' => true])

@section('title', 'Delete ' . $snapshot->name)

@section('content')

<form action="{{ route('snapshots.destroy', $snapshot) }}" method="POST">
    
    {{ method_field('DELETE') }}
    {{ csrf_field() }}

    <p>
        Are you sure you want to delete <strong>{{ $snapshot->name }}</strong> snapshot?
    </p>

    <button class="bg-pink">Delete</button>
</form>

@endsection
