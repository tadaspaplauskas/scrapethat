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
        <select onchange="showMode(this.value)">
            <option value="simple">Simple</option>
            <option value="advanced">Advanced</option>
        </select>
    </p>

    <div id="simple" class="mode">
    <ul class="list-none">
        @foreach ($filters as $filter)
            <li id="{{ $filter->name }}">
                <label class="inline-block">
                    <input type="checkbox" onclick="toggleFilter('{{ $filter->name }}', this.checked)">
                    {{ $filter->name }}
                </label>
                <small>
                <ul class="aggregations list-none inline-block" style="visibility: hidden">
                    @foreach ($aggregations as $key => $value)
                        <li class="inline-block ml3">
                            <label>
                                <input type="checkbox" onclick="toggleAggregation('{{ $filter->name }}', '{{ $key }}', this.checked)">
                                {{ $value }}
                            </label>
                        </li>
                    @endforeach
                </ul>
                </small>
            </li>
        @endforeach
    </ul>
    </div>

    <p id="advanced" class="mode">
        <textarea id="query" class="full-width">SELECT * FROM ?</textarea>

        <button onclick="runQuery()">Run query</button>
    </p>

    <p id="sql-output"></p>
@endif

<h5>Danger zone</h5>
<p>
    <a href="{{ route('snapshots.delete', $snapshot) }}">Delete snapshot</a>
</p>



<script>
    var dataset = {!! $dataset->toJson() !!};
    var shownFilters = [];

    // staticly selected DOM elements go there
    var queryElement = document.getElementById('query');
    var outputElement = document.getElementById('sql-output');
    var modeElements = document.getElementsByClassName('mode');

    function makeQuery() {
        var sql;

        sql = 'SELECT ';

         sql += shownFilters
            .map(function(item) {
                if (item.aggregations.length) {
                    return item.aggregations
                        .map(function (agg) {
                            return agg + '(`' + item.name + '`)';
                        })
                        .join('`, `');
                }

                // just the name by default
                return item.name;
            })
            .join('`, `');

        sql += ' FROM ?';

        queryElement.value = sql;

        runQuery();
    }

    function toggleFilter(filterName, checked) {
        var aggs = document.querySelector('#' + filterName + ' .aggregations');

        // add
        if (checked) {
            shownFilters.push({ name: filterName, aggregations: [] });

            // show aggs
            aggs.style.visibility = 'visible';
        }
        // remove
        else {
            shownFilters = shownFilters.filter(function (item) {
                return item.name !== filterName;
            });
            
            // hide and reset aggs
            aggs.style.visibility = 'hidden';

            Array.from(aggs.querySelectorAll('input'))
                .map(function (agg) {
                    agg.checked = false;
                });
        }

        makeQuery();
    }

    function toggleAggregation(filterName, aggregation, checked) {
        // select correct filter
        var filter = shownFilters.filter(function (shownFilter) {
            return filterName == shownFilter.name;
        })[0];

        if (checked) {
            filter.aggregations.push(aggregation);
        }
        else {
            filter.aggregations.splice(filter.aggregations.indexOf(aggregation));
        }

        makeQuery();
    }

    function showMode(currentMode) {
        var element;

        for (var i = 0; i < modeElements.length; i++) {
            element = modeElements[i];

            if (element.id === currentMode) {
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
            var results = alasql(queryElement.value, [dataset]);
        }
        catch (exception) {
            outputElement.innerText = exception;
            outputElement.className = 'red';

            return false;
        }

        // all good
        var html = '<table>';

        // add header row
        html += '<tr><th>'
            + Object.keys(results[0]).join('</th><th>')
            + '</th></tr>';

        // add data
        for (var i = 0; i < results.length; i++) {
            html += '<tr><td>'
                + Object.values(results[i]).join('</td><td>')
                + '</td></tr>';
        }

        html += '<table>';

        // output
        outputElement.className = '';
        outputElement.innerHTML = html;

        return true;
    }

    showMode('simple');
    runQuery();
</script>

@endsection
