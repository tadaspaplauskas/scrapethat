<div class="row">
    <div class="six columns">
        <label for="name">Name</label>
        <input type="text" class="u-full-width" name="name" id="name" placeholder="Name" value="{{ old('name') }}" required>

        @if ($errors->has('name'))
            <p class="red">
                <strong>{{ $errors->first('name') }}</strong>
            </p>
        @endif
    </div>
</div>

<div class="row">
    <div class="six columns">
        <label for="selector">CSS selector</label>
        <input type="text" class="u-full-width" name="selector" id="selector" placeholder=".selector" value="{{ old('selector') }}" required>

        @if ($errors->has('selector'))
            <p class="red">
                <strong>{{ $errors->first('selector') }}</strong>
            </p>
        @endif
    </div>
</div>

<div class="row">
    <div class="six columns">
        <label for="selector">Data type</label>

        <select name="type">
            <option value="numeric">Numeric</option>
            <option value="text">Text</option>
        </select>

        @if ($errors->has('type'))
            <p class="red">
                <strong>{{ $errors->first('type') }}</strong>
            </p>
        @endif
    </div>
</div>