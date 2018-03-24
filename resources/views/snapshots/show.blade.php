@extends('layouts.app', ['data_explorer' => true])

@section('title', $snapshot->name)

@section('content')

<h5>New filter</h5>
<p>
    Select data out of snapshots using CSS selectors.
</p>

<form method="POST" action="{{ route('filters.store') }}">
    {{ csrf_field() }}
    <input type="hidden" name="snapshot_id" value="{{ $snapshot->id }}">
    
    <label for="name">Name</label>
    <input type="text" name="name" id="name" placeholder="Name" value="{{ old('name') }}" required>

    <label for="selector">CSS selector</label>
    <input type="text" name="selector" id="selector" placeholder=".selector" value="{{ old('selector') }}" required>
    <button type="submit" class="block">Fetch</button>
</form>

@if (!$variables->isEmpty())

<h5>Chart</h5>
<canvas id="chart"></canvas>

<h5>Query</h5>
<p>
    <label for="name">Mode</label>
    <select onchange="showOneOfMany(this.value, qsa('.mode'))">
        <option value="simple">Simple</option>
        <option value="advanced">Advanced</option>
    </select>
</p>

{{-- SIMPLE QUERY --}}
<div id="simple" class="mode">
    
    <button onclick="
        // clone with all children
        var rule = document.getElementById('rule-template').cloneNode(true);

        // make visible
        rule.style.display = 'table-row';

        document.getElementById('query-editor').appendChild(rule);
    ">+ Add a rule</button>

    <table class="full-width" id="query-editor">
        <tr>
            <th>Type</th>
            <th>Column</th>
            <th>Arguments</th>
            <th>Remove</th>
        </tr>
        <tr id="rule-template" style="display: none">
            <td>
                <select onclick="
                    this.parentNode.parentNode
                        .querySelector('.' + this.value).style.display = 'none';
                ">
                    <option value="select">Show column</option>
                    <option value="condition">Condition</option>
                    <option value="aggregation">Aggregation</option>
                    <option value="order">Order by</option>
                    <option value="group">Group by</option>
            </td>
            <td>
                <select>
                    @foreach ($variables as $variable)
                        <option value="{{ $variable->name }}">{{ $variable->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <div class="args condition" style="display: none">
                    <select class="operator" onchange="
                        var valueField = this.parentNode.querySelector('.value');

                        if (this.value.length) {
                            valueField.style.display = 'inline';

                            setCondition('{{ $variable->name }}', this.value, valueField.value);
                        }
                        else {
                            valueField.style.display = 'none';

                            clearCondition('{{ $variable->name }}');
                        }
                    ">
                        <option value="">anything</option>
                        <option value="=">=</option>
                        <option value="<"><</option>
                        <option value=">">></option>
                        <option value="BETWEEN">BETWEEN</option>
                    </select>

                    <input type="text" class="value" style="display: none" onchange="
                        var operatorField = this.parentNode.querySelector('.operator');
                        setCondition('{{ $variable->name }}', operatorField.value, this.value);
                    ">
                </div>

                <div class="args aggregations" style="display: none">
                    <select class="operator" onchange="">
                        <option value="AVG">Average</option>
                        <option value="MEDIAN">Median</option>
                        <option value="SUM">Sum</option>
                        <option value="MIN">Min</option>
                        <option value="MAX">Max</option>
                    </select>
                </div>

                <div class="args order" style="display: none">
                    <select>
                        <option value="DESC">Descending</option>
                        <option value="ASC">Ascending</option>
                    </select>
                </div>

            </td>
            <td>
                <button onclick="
                this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);
                ">- Remove</button>
            </td>
        </tr>
    </table>
</div>

{{-- ADVANDED QUERY --}}
<div id="advanced" class="mode" style="display: none">
    <textarea id="query" class="full-width">SELECT * FROM ?</textarea>

    <button onclick="
        runQuery(qs('#query').value);
    ">Run query</button>
</div>

<h5>Dataset</h5>

<p id="sql-output"></p>
</div>
@endif
</div>

<h5>Danger zone</h5>
<p>
    <a href="{{ route('snapshots.delete', $snapshot) }}">Delete snapshot</a>
</p>

<script>
    var dataset = {!! $dataset->toJson() !!};
</script>

@endsection
