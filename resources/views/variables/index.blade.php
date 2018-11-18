@extends('layouts.app', ['title' => $snapshot->name . ' variables'])

@section('content')

@if (!$snapshot->isCompleted())
    <h5 class="center">
        Snapshot is still in progress, please wait until it is completed.
    </h5>
@else

    <p>
        <a href="{{ route('snapshots.show', $snapshot) }}">Back to {{ $snapshot->name }}</a>
    </p>

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
                        <a href="{{ route('variables.edit', $variable) }}" class="mr1">Edit</a>

                        <a href="{{ route('variables.delete.confirm', $variable) }}" class="mr1">Delete</a>
                    </td>
                </tr>
            @endforeach

        </table>

    @endif

@endif

@endsection
