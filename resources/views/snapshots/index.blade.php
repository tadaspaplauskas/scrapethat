@extends('layouts.app')

@section('title', 'Snapshots')

@section('content')

    <p>
        <a href="{{ route('snapshots.create') }}">Make a new one</a>
    </p>

    @forelse ($snapshots as $snapshot)
        <article class="m0 center column one-half">
            <h5>
                <a href="{{ route('snapshots.show', $snapshot) }}">{{ $snapshot->name }}</a>
            </h5>
            <p>
                <small>
                    {{ $snapshot->created_at->diffForHumans() }},
                    crawled {{ (int) $snapshot->crawled }}/{{ (int) $snapshot->total }} pages
                </small>
            </p>
        </article>
    @empty
        <p>
            No snapshots yet.
            <a href="{{ route('snapshots.create') }}">Make a first one.</a>
        </p>
    @endforelse

@endsection
