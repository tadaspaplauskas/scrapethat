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
    <h5>Defined filters</h5>

    <ul>
        @foreach ($filters as $filter)
            <li>
                <a href="{{ route('filters.show', $filter) }}">{{ $filter->name }}</a>
            </li>
        @endforeach
    </ul>

    <textarea id="query" class="full-width">SELECT AVG(Price) FROM ?</textarea>

    <button onclick="runQuery()">Run query</button>

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
    window.dataset = {!! $dataset->toJson() !!};

    function runQuery() {
        var query = document.getElementById('query').value;
        var outputElement = document.getElementById('sql-output');

        try {
            var results = alasql(query, [window.dataset]);
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
</script>

@endsection
