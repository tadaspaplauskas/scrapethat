@extends('layouts.app')

@section('content')
<h1>Crawl new website</h1>

<p>
    Before runing any analysis, we have to crawl website for data. Let's do it right now.
</p>

<form method="POST" action="{{ route('login') }}">
    
    {{ csrf_field() }}

    {{--
    
    @if ($errors->has('email'))
        <p class="red">
            <strong>{{ $errors->first('email') }}</strong>
        </p>
    @endif
    --}}

    <label for="url" class="">URL to pages</label>
    <input type="url" id="url" name="url" class="full-width" value="{{ old('url') }}" required placeholder="https://news.ycombinator.com/news?p=">

    @if ($errors->has('url'))
        <p class="red">
            <strong>{{ $errors->first('url') }}</strong>
        </p>
    @endif

    <label for="to" class="">Pages to scan</label>
    From <input type="number" id="from" name="from" required value="0">
    to <input type="number" id="to" name="to" required value="10">

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
    
    <button type="submit" class="block">Crawl</button>

</form>
@endsection
