@extends('layouts.app', ['title' => optional($snapshot)->name])

@section('content')

@if (!$snapshot)

<p>
    No data yet.
</p>


@else

    <h5>Chart</h5>
    <canvas id="chart"></canvas>

    <h5 class="mt5">Query</h5>

    <script>
        api_token = "{{ $user->api_token }}";
    </script>

    {{-- builder template cannot work until scripts are fully loaded --}}
    <script src="{{ mix('js/data_explorer.js') }}"></script>

    <div>
        <textarea id="query" class="u-full-width" style="height: 15rem"
        >SELECT * FROM `{{ $snapshot->key }}`</textarea>

        <button
            onclick="runQuery(document.querySelector('#query').value, this)"
            accesskey="r"
            title="Keyboard shortcut: [Alt]+r or [Control]+[Alt]+r"
            class="button-primary"
        >Run query</button>

        <button
            onclick="window.location=
                '{{ route('queries.create') }}' +
                '?query=' +
                encodeURIComponent(document.querySelector('#query').value)"
        >Save query</button>
    </div>

    <p id="sql-output"></p>

    <h5>Export results</h5>
    <p>
        <button
            onclick="exportTableToCSV(
                document.querySelector('#results'),
                'query_results.csv'
            )">
            CSV file
        </button>
    </p>

@endif

@endsection
