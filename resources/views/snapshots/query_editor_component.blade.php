{{-- builder template cannot work until scripts are fully loaded --}}
<script>
    snapshot_id = "{{ $snapshot->id }}";
    api_token = "{{ $user->api_token }}";
</script>

<script src="{{ mix('js/data_explorer.js') }}"></script>

<div>
    <textarea id="query" class="u-full-width" style="height: 15rem">SELECT * FROM dataset</textarea>

    <button onclick="runQuery(document.querySelector('#query').value, this)" accesskey="r" title="Keyboard shortcut: [Alt]+r or [Control]+[Alt]+r">Run query</button>
</div>

<p id="sql-output"></p>

<h5>Export results</h5>
<p>
    <button onclick="exportTableToCSV(document.querySelector('#results'), 'query_results.csv')">
        CSV file
    </button>
</p>