@extends('layouts.app')

@section('title', 'Crawled websites')

@section('content')

    @forelse ($websites as $website)
        <article class="m0 center column one-half">
            <h5>
                <a href="{{ route('snapshots.index', $website) }}">{{ $website->name }}</a>
            </h5>
            <p>
                <small>Created at <time>{{ $website->created_at }}</time></small>
            </p>
        </article>
    @empty
        <p>
            Nothing crawled yet!
            <a href="{{ route('websites.create') }}">Wanna try?</a>
        </p>
    @endforelse

@endsection
