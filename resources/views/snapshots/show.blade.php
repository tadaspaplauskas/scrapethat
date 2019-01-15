@extends('layouts.app', ['title' => $snapshot->name])

@section('content')

@if (!$snapshot->isCompleted())

    <h5 class="center">
        Snapshot is still in progress, please wait.
    </h5>

@else

    <p>
        <a href="{{ route('variables.index', $snapshot) }}">Manage variables</a>
    </p>

    @if ($variables->isEmpty())

        <p>
            Please define some variables first.
        </p>

    @elseif (!$snapshot->variables->filter(function ($v) { return !$v->isCompleted(); } )->isEmpty())

        <h5 class="center">
            Variables are being processed, please wait.
        </h5>

    @else

        <h5>Chart</h5>
        <canvas id="chart"></canvas>

        <h5 class="mt5">Query</h5>

        @include('snapshots/query_editor_component')

    @endif

@endif

@endsection
