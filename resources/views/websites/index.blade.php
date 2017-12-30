@extends('layouts.app')

@section('content')
<h1>Crawled websites</h1>

    @forelse ($websites as $website)
        .
    @empty
        <p>
            Nothing crawled yet!
            <a href="{{ route('websites.create') }}">Wanna try?</a>
        </p>
    @endforelse

@endsection
