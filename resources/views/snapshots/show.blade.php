@extends('layouts.app', ['title' => $snapshot->name])

@section('content')

<h5>Variables</h5>

    <p>
        <a href="{{ route('variables.create', $snapshot) }}">Make a new one</a>
    </p>

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
                <th>Refreshed</th>
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

@endsection
