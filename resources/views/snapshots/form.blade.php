<label for="name" class="">Name</label>
<input type="text" id="name" name="name" value="{{ old('name', $snapshot->name ?? '') }}">

@if ($errors->has('name'))
    <p class="red">
        <strong>{{ $errors->first('name') }}</strong>
    </p>
@endif

<label for="url" class="">Page URL template</label>
<input type="url" id="url" name="url" class="full-width" value="{{ old('url', $snapshot->url ?? '') }}" required placeholder="https://news.ycombinator.com/news?p=*">

@if ($errors->has('url'))
    <p class="red">
        <strong>{{ $errors->first('url') }}</strong>
    </p>
@endif

<label for="to" class="">Pages to scan</label>
From <input type="number" id="from" name="from" required value="{{ old('from', $snapshot->from ?? '') }}">
to <input type="number" id="to" name="to" required value="{{ old('to', $snapshot->to ?? '') }}">

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
