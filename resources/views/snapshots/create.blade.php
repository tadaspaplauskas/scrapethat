@extends('layouts.app', ['title' => 'Create a new snapshot'])

@section('content')

<p>
    Let's crawl some websites, so you can play with numbers 🤓
</p>

<form method="POST" action="{{ route('snapshots.store') }}">

    {{ csrf_field() }}

    @include('snapshots.form')

    <button type="submit" class="block">Save</button>

</form>
@endsection
