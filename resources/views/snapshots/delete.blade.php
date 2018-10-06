@extends('layouts.app', ['title' => 'Delete snapshot'])

@section('content')

<p>
    Delete all data associated with <strong>{{ $snapshot->name }}</strong>.
</p>

<form action="{{ route('snapshots.destroy', $snapshot) }}" method="POST" class="m0">

    {{ csrf_field() }}
    {{ method_field('DELETE') }}

    <button class="bg-pink">Delete</button>
</form>

@endsection
