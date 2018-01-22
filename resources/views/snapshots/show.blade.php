@extends('layouts.app', ['charts' => true])

@section('title', $snapshot->name)

@section('content')

@if (!$filters->isEmpty())

    @if ($datasets->isEmpty())
        <p class="center">Select at least one filter to display.</p>
    @else
        <canvas id="chart"></canvas>
        <script type="text/javascript">
            drawChart('chart', {!! $datasets->toJson() !!});
        </script>
    @endif

    <ul class="list-none center">
        @foreach ($filters as $filter)
            <li class="inline-block mr3">
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
    
    <label for="name">Name</label>
    <input type="text" name="name" id="name" placeholder="Name" value="{{ old('name') }}" required>

    <label for="selector" class="">CSS selector</label>
    <input type="text" name="selector" id="selector" placeholder=".selector" value="{{ old('selector') }}" required>
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
