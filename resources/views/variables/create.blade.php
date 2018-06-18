@extends('layouts.app')

@section('title', 'Create a new variable')

@section('content')

<h5>New variable</h5>
<p>
    Use CSS selectors to get data out of the pages.
</p>

@if (!$snapshot->isCompleted())

    <p>
        Please wait until snapshot is completed.
    </p>

@else
    <form method="POST" action="{{ route('variables.store', $snapshot) }}">

        {{ csrf_field() }}

        <label for="name">Name</label>
        <input type="text" name="name" id="name" placeholder="Name" value="{{ old('name') }}" required>

        @if ($errors->has('name'))
            <p class="red">
                <strong>{{ $errors->first('name') }}</strong>
            </p>
        @endif

        <label for="selector">CSS selector</label>
        <input type="text" name="selector" id="selector" placeholder=".selector" value="{{ old('selector') }}" required>


        @if ($errors->has('selector'))
            <p class="red">
                <strong>{{ $errors->first('selector') }}</strong>
            </p>
        @endif

        <button type="submit" class="block">Save</button>
    </form>

@endif

@endsection
