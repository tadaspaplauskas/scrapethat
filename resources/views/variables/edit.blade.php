@extends('layouts.app', ['title' => $variable->name . ' - ' . $variable->snapshot->name])

@section('content')

<p>
    <a href="{{ route('snapshots.show', $variable->snapshot) }}">
        Back to {{ $variable->snapshot->name }}
    </a>
</p>

<form method="POST" action="{{ route('variable.update', $variable) }}">

    {{ csrf_field() }}

    {{ method_field('PUT') }}

    @include('variables.form')

    <button type="submit" class="block">Save</button>

</form>
@endsection
