@extends('layouts.app')

@section('title', 'Crawled websites')

@section('content')

    @forelse ($websites as $website)
        <article class="">
            <a href="{{ route('snapshots.index', $website) }}"><h3>{{ $website->name }}</h3></a>
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
