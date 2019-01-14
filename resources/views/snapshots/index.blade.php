@extends('layouts.app', ['title' => 'Snapshots'])

@section('content')

    <h5>Snapshots</h5>

    <p>
        <a href="{{ route('snapshots.create') }}">Make a new one</a>
    </p>

    @if ($snapshots->isEmpty())

        <p>
            No snapshots yet.
        </p>

    @else
        <table class="u-full-width">
            <tr>
                <th>Name</th>
                <th>Pages</th>
                <th>Created</th>
                <th>Last updated</th>
                <th>Actions</th>
            </tr>

            @foreach ($snapshots as $snapshot)

                <tr>
                    <td>
                        <a href="{{ route('snapshots.show', $snapshot) }}">{{ $snapshot->name }}</a>
                    </td>
                    <td>
                        {{ (int) $snapshot->pages()->count() }} pages
                    </td>
                    <td>
                        {{ $snapshot->created_at->diffForHumans() }}
                    </td>
                    <td>
                        {{ $snapshot->updated_at->diffForHumans() }}
                    </td>
                    <td>
                        <a href="{{ route('snapshots.refresh.confirm', $snapshot) }}" title="Scans all the pages again" class="mr1">Refresh</a>

                        <a href="{{ route('snapshots.edit', $snapshot) }}" class="mr1">Edit</a>

                        <a href="{{ route('snapshots.delete.confirm', $snapshot) }}" class="mr1">Delete</a>

                    </td>
                </tr>

            @endforeach

    @endif

@endsection
