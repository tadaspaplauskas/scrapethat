@extends('layouts.app', ['title' => $snapshot->name])

@section('content')

@if (!$snapshot->isCompleted())
    <p class="center">
        Snapshot is still in progress, please wait until it has completed.
    </p>

@else

    <h5>Variables</h5>

    <p>
        Use CSS selectors to get data out of the pages. We will parse downloaded pages and fill the dataset.
    </p>

        <form method="POST" action="{{ route('variables.store', $snapshot) }}">

            {{ csrf_field() }}

            <div class="row">
                <div class="six columns">
                    <label for="name">Name</label>
                    <input type="text" class="u-full-width" name="name" id="name" placeholder="Name" value="{{ old('name') }}" required>

                    @if ($errors->has('name'))
                        <p class="red">
                            <strong>{{ $errors->first('name') }}</strong>
                        </p>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="six columns">
                    <label for="selector">CSS selector</label>
                    <input type="text" class="u-full-width" name="selector" id="selector" placeholder=".selector" value="{{ old('selector') }}" required>

                    @if ($errors->has('selector'))
                        <p class="red">
                            <strong>{{ $errors->first('selector') }}</strong>
                        </p>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="six columns">
                    <button type="submit">Add</button>
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
                        {{ $variable->created_at->diffForHumans() }}
                    </td>
                    <td>
                        {{ $snapshot->updated_at->diffForHumans() }}
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
