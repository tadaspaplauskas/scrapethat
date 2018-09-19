@extends('layouts.app', ['title' => 'Create a new snapshot'])

@section('content')

<p>
    Turn a website into a database.
</p>

<form method="POST" action="{{ route('snapshots.store') }}">

    {{ csrf_field() }}

    @include('snapshots.form')

    <button type="submit" class="block button-primary">Save</button>

</form>
@endsection
