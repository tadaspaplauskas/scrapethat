@extends('layouts.app', ['title' => 'Delete variable'])

@section('content')

<p>
    <a href="{{ route('snapshots.show', $variable->snapshot) }}">
        Back to {{ $variable->snapshot->name }} variables
    </a>
</p>

<p>
    Delete variable named <strong>{{ $variable->name }}</strong>.
</p>

<form method="POST" action="{{ route('variables.destroy', $variable) }}" class="m0">
    {{ csrf_field() }}
    {{ method_field('DELETE') }}
    <button>Delete</button>
</form>

@endsection
