@extends('layouts.app')

@section('title', 'Create a new snapshot')

@section('content')

<p>
    Before runing any analysis, we have to crawl website for data. Let's do it right now.
</p>

<form method="POST" action="{{ route('snapshots.store') }}">
    
    {{ csrf_field() }}
    
    @include('snapshots.form')
    
    <button type="submit" class="block">Save</button>

</form>
@endsection
