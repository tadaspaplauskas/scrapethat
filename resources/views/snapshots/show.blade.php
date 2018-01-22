@extends('layouts.app', ['charts' => true])

@section('title', $snapshot->name)

@section('content')

@if (!$filters->isEmpty())
    <canvas id="chart" height="100"></canvas>
    <script type="text/javascript">
        // whenReady(function () {
            var ctx = document.getElementById('chart');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {{ $filters->first()->values->toJson() }},
                    datasets: [{
                        label: '{{ $filters->first()->name }}',
                        data: {{ $filters->first()->values->toJson() }}
                    }]
                },
                options: {
                    // maintainAspectRatio: false
                }
            });
        // });
    </script>

    <ul class="list-none">
        @foreach ($filters as $filter)
            <li>
                <form method="POST" action="{{ route('filters.update', $filter) }}">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <label>
                        <input type="hidden" name="selected" value="0">
                        <input type="checkbox" name="selected" value="1" {{ $filter->selected ? 'checked' : '' }} onchange="submit()">
                        {{ $filter->name }}
                    </label>
                </form>
            </li>
        @endforeach
    </ul>
@endif

<h5>Create a filter</h5>
<p>
    Select data out of snapshots using CSS selectors.
</p>

<form method="POST" action="{{ route('filters.store') }}">
    {{ csrf_field() }}
    <input type="hidden" name="snapshot_id" value="{{ $snapshot->id }}">
    <label for="password" class="">CSS selector</label>
    <input type="text" name="selector" id="selector" placeholder=".selector" value="{{ old('css_selector') }}" required>
    <button type="submit" class="block">Fetch</button>
</form>





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
