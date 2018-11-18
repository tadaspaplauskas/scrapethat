@extends('layouts.app', ['title' => $variable->name . ' - ' . $variable->snapshot->name])

@section('content')

<p>
    <a href="{{ route('variables.index', $variable->snapshot) }}">
        Back to {{ $variable->snapshot->name }} variables
    </a>
</p>

<p>
    Use CSS selectors to get data out of the pages. We will parse downloaded pages and fill the dataset.
</p>

<form method="POST" action="{{ route('variables.update', $variable) }}">

    {{ csrf_field() }}

    {{ method_field('PUT') }}

    @include('variables.form')

    <button type="submit" class="block">Save</button>

</form>
@endsection
