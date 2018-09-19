<div class="row">
    <div class="six columns">
        <label for="name" class="">Name</label>
        <input type="text" id="name" name="name" class="u-full-width" value="{{ old('name', $snapshot->name ?? '') }}" required>
    </div>
</div>

@if ($errors->has('name'))
    <div class="row">
        <div class="twelve columns">
                <p class="red">
                    <strong>{{ $errors->first('name') }}</strong>
                </p>
        </div>
    </div>
@endif

<div class="row">
    <div class="six columns">
        <label for="url" class="">Page URL template</label>
        <small>Please mark page number with an asterisk (*)</small>
        <input type="url" id="url" name="url" class="u-full-width" value="{{ old('url', $snapshot->url ?? '') }}" required placeholder="https://news.ycombinator.com/news?p=*">
    </div>
</div>

@if ($errors->has('url'))
    <div class="row">
        <div class="twelve columns">
                <p class="red">
                    <strong>{{ $errors->first('url') }}</strong>
                </p>
        </div>
    </div>
@endif

<div class="row">
    <div class="three columns">
        <input type="number" id="from" name="from" required value="{{ old('from', $snapshot->from ?? '') }}" class="u-full-width" placeholder="Start page number">
    </div>

    <div class="three columns">
        <input type="number" id="to" name="to" required value="{{ old('to', $snapshot->to ?? '') }}" class="u-full-width" placeholder="Finish page number">
    </div>
</div>

<div class="row">
    <div class="twelve columns">
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
    </div>
</div>