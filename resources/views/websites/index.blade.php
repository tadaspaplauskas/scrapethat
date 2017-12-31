@extends('layouts.app')

@section('content')
<h1>Crawled websites</h1>

    @forelse ($websites as $website)
        <article>
            <a>{{ $website->name }}</a>
            <a>{{ $website->url }}</a>
            <p>
                <small>Created at {{ $website->created_at }}</small>
            </p>
        </article>
    @empty
        <p>
            Nothing crawled yet!
            <a href="{{ route('websites.create') }}">Wanna try?</a>
        </p>
    @endforelse

@endsection
