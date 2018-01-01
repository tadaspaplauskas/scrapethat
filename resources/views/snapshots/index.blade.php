@extends('layouts.app')

@section('title', 'Snapshots')

@section('content')

    @forelse ($snapshots as $snapshot)
        <article class="m0 center column one-half">
            <h5>
                <a href="{{ route('snapshots.show', $snapshot) }}">{{ $snapshot->created_at }}</a>
            </h5>
            <p>
                <small>{{ $snapshot->pages }} pages</small>
            </p>
        </article>
    @empty
        <p>
            No snapshots yet.
            <a href="{{ route('snapshots.create') }}">Make a first one.</a>
        </p>
    @endforelse

@endsection
