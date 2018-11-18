@extends('layouts.app', ['title' => 'Delete variable'])

@section('content')

<p>
    Delete variable named <strong>{{ $variable->name }}</strong>.
</p>

<form method="POST" action="{{ route('variables.destroy', $variable) }}" class="m0">
    {{ csrf_field() }}
    {{ method_field('DELETE') }}
    <button>Delete</button>
</form>

@endsection
