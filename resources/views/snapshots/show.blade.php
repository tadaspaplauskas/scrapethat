@extends('layouts.app')

@section('title', $snapshot->name)

@section('content')

<p>
    <a href="{{ route('variables.index', $snapshot) }}">Manage variables</a>.
</p>

@if ($variables->isEmpty())

<p>
    You have not yet defined any variables.
</p>

@else

<h5>Chart</h5>
<canvas id="chart"></canvas>

<h5>Query</h5>

@include('snapshots/query_editor_component')

@endif

<h5>Danger zone</h5>
<p>
    <a href="{{ route('snapshots.delete', $snapshot) }}">Delete snapshot</a>.
    We will ask you to confirm the action.
</p>

<script>
    var dataset = {!! $dataset->toJson() !!};
</script>

@endsection
