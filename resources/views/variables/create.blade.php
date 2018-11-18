@extends('layouts.app', ['title' => 'New variable - ' . $snapshot->name])

@section('content')

<p>
    <a href="{{ route('variables.index', $snapshot) }}">
        Back to {{ $snapshot->name }} variables
    </a>
</p>

<p>
    Use CSS selectors to get data out of the pages. We will parse downloaded pages and fill the dataset.
</p>

<form method="POST" action="{{ route('variables.store', $snapshot) }}">

    {{ csrf_field() }}

    @include('variables.form')

    <div class="row">
        <div class="six columns">
            <button type="submit">Save</button>
        </div>
    </div>

</form>

@endsection
