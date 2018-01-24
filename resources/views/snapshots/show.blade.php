@extends('layouts.app', ['extra_dependencies' => true])

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
    <h5>Dataset</h5>

    <p>
        <label for="name">Mode</label>
        <select id="mode" name="mode" onchange="showMode()">
            <option value="simple">Simple</option>
            <option value="advanced">Advanced</option>
        </select>
    </p>

    <div id="simple" class="mode">
        <ul class="list-none">
            @foreach ($filters as $filter)
                <li>
                    <label class="inline-block">
                        <input type="checkbox" onclick="toggleFilter('{{ $filter->name }}')">
                        {{ $filter->name }}
                    </label>
                    <small>
                    <ul class="list-none inline-block ">
                        <li class="inline-block ml3">
                            <label>
                                <input type="checkbox" onclick="toggleAggregation('{{ $filter->name }}', 'AVG')">
                                Average
                            </label>
                        </li>
                        <li class="inline-block ml3">
                            <label>
                                <input type="checkbox" onclick="toggleAggregation('{{ $filter->name }}', 'MEDIAN')">
                                Median
                            </label>
                        </li>
                        <li class="inline-block ml3">
                            <label>
                                <input type="checkbox" onclick="toggleAggregation('{{ $filter->name }}', 'SUM')">
                                Sum
                            </label>
                        </li>
                        <li class="inline-block ml3">
                            <label>
                                <input type="checkbox" onclick="toggleAggregation('{{ $filter->name }}', 'COUNT')">
                                Count
                            </label>
                        </li>
                        <li class="inline-block ml3">
                            <label>
                                <input type="checkbox" onclick="toggleAggregation('{{ $filter->name }}', 'MIN')">
                                Min
                            </label>
                        </li>
                        <li class="inline-block ml3">
                            <label>
                                <input type="checkbox" onclick="toggleAggregation('{{ $filter->name }}', 'MAX')">
                                Max
                            </label>
                        </li>
                    </ul>
                </small>
                </li>
            @endforeach
        </ul>
    </div>

    <p id="advanced" class="mode">
        <textarea id="query" class="full-width">SELECT AVG(Price) FROM ?</textarea>

        <button onclick="runQuery()">Run query</button>
    </p>

    <p id="sql-output"></p>
@endif

<h5>Danger zone</h5>
<p>
    <a href="#delete">Delete snapshot</a>
</p>
<div id="delete" class="hidden">
    <form action="{{ route('snapshots.destroy', $snapshot) }}" method="POST">
        
        {{ method_field('DELETE') }}
        {{ csrf_field() }}

        <p>
            Are you sure you want to delete this snapshot?
        </p>

        <button class="bg-pink">Delete</button>
    </form>
</div>


<script>
    var dataset = {!! $dataset->toJson() !!};
    var shownFilters = [];

    var queryElement = document.getElementById('query');
    var outputElement = document.getElementById('sql-output');
    var currentModeElement = document.getElementById('mode');
    var modeElements = document.getElementsByClassName('mode');

    function makeQuery() {
        var sql;

        sql = 'SELECT ';

        // sql += shownFilters.join(', ');

        sql += ' FROM ?';

        queryElement.value = sql;

        runQuery();
    }

    function toggle(array, item, compareFn) {
        var updated = array.filter(compareFn);

        // not found, so add
        if (updated.length === array.length) {
            updated.push(item);
        }

        return updated;
    }

    function toggleFilter(filter) {
        shownFilters = toggle(shownFilters, { name: filter }, function (item) {
            return item.name === filter;
        });

        makeQuery();
    }

    function toggleAggregation(filter, aggregation) {
        // shownFilters = shownFilters.map(function (item) {
        //     if (filter == item.name) {
        //         return toggle(item.aggregations, aggregation);
        // });

        // shownFilters = updated;

        // makeQuery();
    }

    function showMode() {
        var element;

        for (var i = 0; i < modeElements.length; i++) {
            element = modeElements[i];
            console.log(element);
            if (element.id === currentModeElement.value) {
                element.style.display = 'block';
            }
            else {
                element.style.display = 'none';
            }
        }
    }

    function runQuery() {
        var results, html;

        if (!queryElement.value) {
            return false;
        }

        

        try {
            var results = alasql(queryElement.value, [window.dataset]);
        }
        catch (exception) {
            outputElement.innerText = exception;
            outputElement.className = 'red';

            return false;
        }

        // all good
        var html = '<table>';

        // add header row
        html += '<tr><th>' + Object.keys(results[0]).join('</th><th>') + '</th></tr>';

        // add data
        for (var i = 0; i < results.length; i++) {
            html += '<tr><td>' + Object.values(results[i]).join('</td><td>') + '</td></tr>';
        }

        html += '<table>';

        // output
        outputElement.className = '';
        outputElement.innerHTML = html;

        return true;
    }

    showMode();
    runQuery();
</script>

@endsection
