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

@if (!$filters->isEmpty())

<h5>Chart</h5>
<canvas id="chart"></canvas>

<div class="row">
<div class="column one-half">

<h5>Query</h5>
<p>
<label for="name">Mode</label>
<select onchange="
    showOneOfMany(this.value, qsa('.mode'));
">
    <option value="simple">Simple</option>
    <option value="advanced">Advanced</option>
</select>
</p>

{{-- SIMPLE QUERY --}}
<div id="simple" class="mode">
@foreach ($filters as $filter)
<article id="{{ $filter->name }}">
    <label>
    <h5>
        <input type="checkbox" onclick="toggleFilter('{{ $filter->name }}', this.checked)">
        {{ $filter->name }}
    </h5>
    </label>
    <div class="options" style="display: none">
        <label>Condition</label>
        <select onchange="
            var e = this.parentNode.querySelector('.value');

            if (this.value.length)
                e.style.display = 'inline';
            else
                e.style.display = 'none';
        ">
            <option value="">anything</option>
            <option value="=">=</option>
            <option value="<"><</option>
            <option value=">">></option>
            <option value="BETWEEN">BETWEEN</option>
        </select>
        <input type="text" class="value" style="display: none" onchange="
            var operator = this.parentNode.querySelector('.operator');
            setCondition("{{ $filter->name }}", operator, this.value);
        ">
        
        <br>
        
        <label>Aggregations</label>
        <ul class="aggregations list-none inline">
            @foreach ($aggregations as $key => $value)
                <li class="inline-block mr2">
                    <label class="normal-text">
                        <input type="checkbox" onclick="
                            toggleAggregation('{{ $filter->name }}', '{{ $key }}', this.checked)
                        ">
                        {{ $value }}
                    </label>
                </li>
            @endforeach
        </ul>
    </div>
</article>
@endforeach

{{-- ORDER BY --}}
<article>
    <label>
    <h5>
        <input type="checkbox" onclick="
            setOrderBy(this.checked ? qs('#order_field').value : null, qs('#order_value').value);
        "> Order by
    </h5>
    </label>
    <ul id="order_by" class="inline list-none" style="display: none">
        <li class="inline-block">
            <select id="order_field" onchange="setOrderBy(this.value, qs('#order_value').value)">
                @foreach ($filters as $filter)
                    <option value="{{ $filter->name }}">{{ $filter->name }}</option>
                @endforeach
            </select>
        </li>
        <li class="inline-block">
            <select id="order_value" onchange="
                setOrderBy(qs('#order_field').value, this.value);
            ">
                <option value="DESC">descending</option>
                <option value="ASC">ascending</option>
            </select>
        </li>
    </ul>
</article>

{{-- GROUP BY --}}
<article>
    <label>
    <h5>
        <input type="checkbox" onclick="
            setGroupBy(this.checked ? qs('#group_by_field').value : null);
        "> Group by
    </h5>
    </label>
    <div id="group_by" class="list-none inline" style="display: none">
        <select id="group_by_field" onchange="
            setGroupBy(this.value);
        ">
            @foreach ($filters as $filter)
                <option value="{{ $filter->name }}">{{ $filter->name }}</option>
            @endforeach
        </select>
    </div>
</article>

{{-- ADVANDED QUERY --}}
<div id="advanced" class="mode" style="display: none">
    <textarea id="query" class="full-width">SELECT * FROM ?</textarea>

    <button onclick="
        runQuery(qs('#query').value);
    ">Run query</button>
</div>
</div>
</div>

<div class="column one-half">
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
