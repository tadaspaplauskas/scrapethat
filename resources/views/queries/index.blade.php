@extends('layouts.app', ['title' => 'Queries'])

@section('content')

    <h5>Queries</h5>

    @if ($queries->isEmpty())

        <p>
            No queries yet.
        </p>

    @else
        <table class="u-full-width">
            <tr>
                <th>Name</th>
                <th>Created</th>
                <th class="right">Actions</th>
            </tr>

            @foreach ($queries as $query)

                <tr>
                    <td>
                        {{ $query->name }}
                    </td>
                    <td>
                        {{ $query->created_at->diffForHumans() }}
                    </td>
                    <td class="right">
                        <a href="{{ route('queries.editor', ['query' => $query]) }}" title="Open in query editor" class="mr1">Query Editor</a>

                        <a href="{{ route('queries.delete.confirm', $query) }}" class="mr1">Delete</a>

                    </td>
                </tr>

            @endforeach

    @endif

@endsection
