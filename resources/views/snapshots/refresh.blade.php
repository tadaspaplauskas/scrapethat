@extends('layouts.app', ['title' => 'Refresh snapshot'])

@section('content')

<p>
    Delete pages and download them again. Variables will remain untouched, but data will change.
</p>

<form action="{{ route('snapshots.refresh', $snapshot) }}" method="POST" class="m0">

    {{ csrf_field() }}

    <button>Refresh</button>
</form>

@endsection
