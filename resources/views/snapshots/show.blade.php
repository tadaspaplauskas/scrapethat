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

    <p id="simple" class="mode">
        <ul class="list-none">
            @foreach ($filters as $filter)
                <li>
                    <label>
                        <input type="checkbox" value="{{ $filter->name }}"
                        onclick="toggleFilter(this.value)">
                        {{ $filter->name }}
                    </label>
                </li>
            @endforeach
        </ul>
        TODO
    </p>

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
    var useFilters = [];

    var queryElement = document.getElementById('query');
    var outputElement = document.getElementById('sql-output');

    function makeQuery() {
        var sql;

        sql = 'SELECT ';

        sql += useFilters.join(', ');

        sql += ' FROM ?';

        queryElement.value = sql;

        runQuery();
    }

    function toggleFilter(filter) {
        var updated = useFilters.filter(function (item) {
            return filter !== item;
        });

        // was not removed, so add now
        if (updated.length === useFilters.length) {
            updated.push(filter);
        }

        useFilters = updated;

        makeQuery();
    }

    function showMode() {
        var mode, modes, element;

        mode = document.getElementById('mode').value;

        modes = document.getElementsByClassName('mode');

        for (var i = 0; i < modes.length; i++) {
            element = modes[i];

            if (element.id === mode) {
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
