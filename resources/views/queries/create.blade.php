@extends('layouts.app', ['title' => 'Store a query'])

@section('content')

<form method="POST" action="{{ route('queries.store') }}">

    {{ csrf_field() }}

    <div class="row">
        <div class="six columns">
            <label for="name">Name</label>
            <input type="text"
                id="name"
                name="name"
                class="u-full-width"
                value="{{ old('name', $snapshot->name ?? '') }}"
                required>
        </div>
    </div>

    @if ($errors->has('name'))
        <div class="row">
            <div class="twelve columns">
                <p class="red">
                    <strong>{{ $errors->first('name') }}</strong>
                </p>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="six columns">
            <label for="query">Query</label>
            <textarea
                id="query"
                name="query"
                class="u-full-width"
                style="height: 15rem"
            >{{ old('query', $query ?? '') }}</textarea>
        </div>
    </div>

    @if ($errors->has('query'))
        <div class="row">
            <div class="twelve columns">
                <p class="red">
                    <strong>{{ $errors->first('query') }}</strong>
                </p>
            </div>
        </div>
    @endif

    <button type="submit" class="block button-primary">Save</button>

</form>
@endsection
