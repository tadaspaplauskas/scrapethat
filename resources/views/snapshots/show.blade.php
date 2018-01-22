@extends('layouts.app', ['charts' => true])

@section('title', $snapshot->name)

@section('content')

<h5>New filter</h5>
<p>
    Start analyzing snapshot data with CSS selectors.
</p>

<form method="GET">
    <label for="password" class="">CSS selector</label>
    <input type="text" name="selector" id="selector" placeholder=".selector" value="{{ old('css_selector') }}" required>
    <button type="submit" class="block">Fetch</button>
</form>

<h5>Available filters</h5>
@forelse ($filters as $filter)
    <li>{{ $filter->name }} {{ $filter->values->toJson() }}</li>
@empty
    <p>No filters created yet.</p>
@endforelse

<h5>Graph</h5>
<p>
    <canvas id="chart"></canvas>
</p>

<script async src="{{ asset('js/charts.js') }}"></script>
<script type="text/javascript">
    window.onload = function() {
        var ctx = document.getElementById('chart');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {{ $filters->first()->values->toJson() }},
                datasets: [{
                    label: '{{ $filters->first()->name }}',
                    data: {{ $filters->first()->values->toJson() }}
                }]
            }
        });
    };
</script>

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

@endsection
