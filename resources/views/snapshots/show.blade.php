@extends('layouts.app', ['data_explorer' => true])

@section('title', $snapshot->name)

@section('content')

<h5>New filter</h5>
<p>
    Select data out of snapshots using CSS selectors.
</p>

<form method="POST" action="{{ route('filters.store') }}">
    {{ csrf_field() }}
    <input type="hidden" name="snapshot_id" value="{{ $snapshot->id }}">
    
    <label for="name">Name</label>
    <input type="text" name="name" id="name" placeholder="Name" value="{{ old('name') }}" required>

    <label for="selector">CSS selector</label>
    <input type="text" name="selector" id="selector" placeholder=".selector" value="{{ old('selector') }}" required>
    <button type="submit" class="block">Fetch</button>
</form>

@if (!$variables->isEmpty())

<h5>Chart</h5>
<canvas id="chart"></canvas>

<h5>Query</h5>
<p>
    <label for="name">Mode</label>
    <select onchange="showOneOfMany(this.value, qsa('.mode'))">
        <option value="simple">Simple</option>
        <option value="advanced">Advanced</option>
    </select>
</p>

{{-- SIMPLE QUERY --}}
<div id="simple" class="mode">
    @include('components/query_editor')
</div>

{{-- ADVANDED QUERY --}}
<div id="advanced" class="mode" style="display: none">
    <textarea id="query" class="full-width">SELECT * FROM ?</textarea>

    <button onclick="
        runQuery(qs('#query').value);
    ">Run query</button>
</div>

<h5>Dataset</h5>

<p id="sql-output"></p>
</div>
@endif
</div>

<h5>Danger zone</h5>
<p>
    <a href="{{ route('snapshots.delete', $snapshot) }}">Delete snapshot</a>
</p>

<script>
    var dataset = {!! $dataset->toJson() !!};
</script>

@endsection
