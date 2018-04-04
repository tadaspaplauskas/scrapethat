@extends('layouts.app')

@section('title', 'Create a new snapshot')

@section('content')

<p>
    Before runing any analysis, we have to crawl website for data. Let's do it right now.
</p>

<form method="POST" action="{{ route('snapshots.store') }}">
    
    {{ csrf_field() }}

    <label for="name" class="">Name</label>
    <input type="text" id="name" name="name" value="{{ old('name') }}">

    <label for="url" class="">Page URL template</label>
    <input type="url" id="url" name="url" class="full-width" value="{{ old('url') }}" required placeholder="https://news.ycombinator.com/news?p=*">

    @if ($errors->has('url'))
        <p class="red">
            <strong>{{ $errors->first('url') }}</strong>
        </p>
    @endif

    <label for="to" class="">Pages to scan</label>
    From <input type="number" id="from" name="from" required value="1">
    to <input type="number" id="to" name="to" required value="100">

    @if ($errors->has('from'))
        <p class="red">
            <strong>{{ $errors->first('from') }}</strong>
        </p>
    @endif
    @if ($errors->has('to'))
        <p class="red">
            <strong>{{ $errors->first('to') }}</strong>
        </p>
    @endif
    
    <button type="submit" class="block">Save</button>

</form>
@endsection
