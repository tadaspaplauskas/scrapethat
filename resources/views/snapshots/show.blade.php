@extends('layouts.app')

@section('title', $snapshot->name)

@section('content')

<p>
    <a href="{{ route('variables.index', $snapshot) }}">Manage variables</a>
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

<script>
    var dataset = {!! $dataset->toJson() !!};
</script>

@endsection
