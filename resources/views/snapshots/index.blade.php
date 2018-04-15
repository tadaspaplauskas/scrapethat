@extends('layouts.app')

@section('title', 'Snapshots')

@section('content')

    <p>
        <a href="{{ route('snapshots.create') }}">Make a new one</a>
    </p>

    @if ($snapshots->isEmpty())

        <p>
            No snapshots yet.
        </p>

    @else
        <table class="full-width">
            <tr>
                <th>Name</th>
                <th>Downloaded pages</th>
                <th>Created at</th>
                <th>Delete</th>
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
                        <form action="{{ route('snapshots.destroy', $snapshot) }}" method="POST" class="m0">
    
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}

                            <button>Delete</button>
                        </form>
                    </td>
                </tr>

            @endforeach

    @endif

@endsection
