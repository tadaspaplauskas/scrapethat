@extends('layouts.app', ['charts' => true])

@section('title', $filter->name . ' - ' . $filter->snapshot->name)

@section('content')

<p>
    <a href="{{ route('snapshots.show', $filter->snapshot) }}">
        Back to {{ $filter->snapshot->name }}
    </a>
</p>

<canvas id="chart"></canvas>
<script type="text/javascript">
    drawChart('chart', [{!! $filter->toJson() !!}]);
</script>

<ul class="center">
    <li class="inline-block mr3">Count: {{ $filter->values->count() }}</li>
    <li class="inline-block mr3">Avg: {{ $filter->values->avg() }}</li>
    <li class="inline-block mr3">Median: {{ $filter->values->median() }}</li>
    <li class="inline-block mr3">Sum: {{ $filter->values->sum() }}</li>
    <li class="inline-block mr3">Min: {{ $filter->values->min() }}</li>
    <li class="inline-block mr3">Max: {{ $filter->values->max() }}</li>
</ul>

<h5>Conditions</h5>
<form method="POST" action="{{ route('filters.update', $filter) }}">
    {{ method_field('PUT') }}
    {{ csrf_field() }}
    <label>
        <input type="hidden" name="selected" value="0">
        <input type="checkbox" name="selected" value="1" {{ $filter->selected ? 'checked' : '' }} onchange="submit()">
        {{ $filter->name }}
    </label>
</form>

<h5>Danger zone</h5>
<p>
    <a href="#delete">Delete filter</a>
</p>
<div id="delete" class="hidden">
    <form action="{{ route('filters.destroy', $filter) }}" method="POST">
        
        {{ method_field('DELETE') }}
        {{ csrf_field() }}

        <p>
            Are you sure you want to delete this filter?
        </p>

        <button class="bg-pink">Delete</button>
    </form>
</div>

@endsection
