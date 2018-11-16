@extends('layouts.app', ['title' => $snapshot->name])

@section('content')

@if (!$snapshot->isCompleted())
    <h5 class="center">
        Snapshot is still in progress, please wait until it is completed.
    </h5>
@elseif (!$snapshot->variables->filter(function ($v) { return !$v->isCompleted(); } )->isEmpty())
    <h5 class="center">
        Variables are being processed, please wait until it is completed.
    </h5>
@else

    <h5>Variables</h5>

    <p>
        Use CSS selectors to get data out of the pages. We will parse downloaded pages and fill the dataset.
    </p>

        <form method="POST" action="{{ route('variables.store', $snapshot) }}">

            {{ csrf_field() }}

            @include('variables.form')

            <div class="row">
                <div class="six columns">
                    <button type="submit">Save</button>
                </div>
            </div>

        </form>

    @if ($variables->isEmpty())
        <p>
            You have not yet defined any variables.
        </p>
    @else

        <table class="u-full-width">
            <tr>
                <th>Name</th>
                <th>Selector</th>
                <th>Type</th>
                <th>Created</th>
                <th>Last refresh</th>
                <th>Actions</th>
            </tr>

            @foreach ($variables as $variable)
                <tr>
                    <td>
                        {{ $variable->name }}
                    </td>
                    <td>
                        {{ $variable->selector }}
                    </td>
                    <td>
                        {{ $variable->type }}
                    </td>
                    <td>
                        {{ $variable->created_at->diffForHumans() }}
                    </td>
                    <td>
                        {{ $variable->updated_at->diffForHumans() }}
                    </td>
                    <td>
                        <form method="POST" action="{{ route('variables.destroy', $variable) }}" class="m0">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <button>Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach

        </table>


    <h5 class="mt5">Chart</h5>
    <canvas id="chart"></canvas>

    <h5 class="mt5">Query</h5>

    @include('snapshots/query_editor_component')

    @endif

@endif

@endsection
