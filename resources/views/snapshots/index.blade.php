@extends('layouts.app')

@section('title', $website->name . ' snapshots')

@section('content')

    @forelse ($website->snapshots as $snapshot)
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
            No snapshots.
            <a href="{{ route('snapshots.create', $website) }}">Make a first one.</a>
        </p>
    @endforelse

@endsection
