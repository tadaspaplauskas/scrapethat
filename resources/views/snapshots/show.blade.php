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

    <label for="selector" class="">CSS selector</label>
    <input type="text" name="selector" id="selector" placeholder=".selector" value="{{ old('selector') }}" required>
    <button type="submit" class="block">Fetch</button>
</form>

@if (!$filters->isEmpty())

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
<ul class="list-none">
@foreach ($filters as $filter)
    <li id="{{ $filter->name }}" class="mb0">
        <label class="inline-block min-width-10">
            <input type="checkbox" onclick="toggleFilter('{{ $filter->name }}', this.checked)">
            {{ $filter->name }}
        </label>
        <ul class="aggregations list-none inline" style="visibility: hidden">
            @foreach ($aggregations as $key => $value)
                <li class="inline-block ml3 mb0">
                    <label>
                        <input type="checkbox" onclick="toggleAggregation('{{ $filter->name }}', '{{ $key }}', this.checked)">
                        {{ $value }}
                    </label>
                </li>
            @endforeach
        </ul>
    </li>
@endforeach

{{-- ORDER BY --}}
<li class="mb0">
    <label class="inline">
        <input type="checkbox" onclick="setOrderBy(this.checked ? qs('#order_field').value : null, qs('#order_value').value);"> Order by</label>

    <ul id="order_by" class="list-none inline" style="visibility: hidden">
        <li class="inline-block ml3 mb0">
            <select id="order_field" onchange="setOrderBy(this.value, qs('#order_value').value)">
                @foreach ($filters as $filter)
                    <option value="{{ $filter->name }}">{{ $filter->name }}</option>
                @endforeach
            </select>
        </li>
        <li class="inline-block ml3 mb0">
            <select id="order_value" onchange="setOrderBy(qs('#order_field').value, this.value)">
                <option value="DESC">descending</option>
                <option value="ASC">ascending</option>
            </select>
        </li>
    </ul>
</li>

{{-- GROUP BY --}}
<li class="mb0">
    <label class="inline">
        <input type="checkbox" onclick="setGroupBy(this.checked ? qs('#group_by_field').value : null);"> Group by</label>

    <ul id="group_by" class="list-none inline" style="visibility: hidden">
        <li class="inline-block ml3 mb0">
            <select id="group_by_field" onchange="setGroupBy(this.value)">
                @foreach ($filters as $filter)
                    <option value="{{ $filter->name }}">{{ $filter->name }}</option>
                @endforeach
            </select>
        </li>
    </ul>
</li>
</ul>
</div>

{{-- ADVANDED QUERY --}}
<div id="advanced" class="mode" style="display: none">
    <textarea id="query" class="full-width">SELECT * FROM ?</textarea>

    <button onclick="runQuery(qs('#query').value)">Run query</button>
</div>


<h5>Dataset</h5>

<p id="sql-output"></p>

@endif

<h5>Danger zone</h5>
<p>
    <a href="{{ route('snapshots.delete', $snapshot) }}">Delete snapshot</a>
</p>

<script>
    var dataset = {!! $dataset->toJson() !!};
</script>

@endsection
