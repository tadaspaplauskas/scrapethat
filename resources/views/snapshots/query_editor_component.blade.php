{{-- builder template cannot work until scripts are fully loaded --}}
<script src="{{ asset('js/data_explorer.js') }}"></script>

<p>
    <label for="name">Mode</label>
    <select onchange="showOneOfMany('#' + this.value, '.mode')">
        <option value="simple">Simple</option>
        <option value="advanced">Advanced</option>
    </select>
</p>

{{-- SIMPLE QUERY --}}
<div id="simple" class="mode">
    <button onclick="addElementTo('#rule-template', '#query-editor').style.display = 'table-row'" accesskey="n" title="Keyboard shortcut: [Alt]+n or [Control]+[Alt]+n">+ Add a rule</button>

    {{-- this enables to refresh query on enter --}}
    <form onsubmit="event.preventDefault();submitQuery();">
        <table class="full-width" id="query-editor">
            <tr>
                <th>Type</th>
                <th>Column</th>
                <th>Arguments</th>
                <th></th>
            </tr>
            <tr id="rule-template" class="rule" style="display: none">
                <td>
                    <select class="type" onchange="showOneOfMany('.' + this.value, '.args', this.parentNode.parentNode)">
                        <option value="select">Show column</option>
                        <option value="condition">Condition</option>
                        <option value="aggregation">Aggregation</option>
                        <option value="order">Order by</option>
                        <option value="group">Group by</option>
                </td>
                <td>
                    <select class="variable">
                        @foreach ($variables as $variable)
                            <option value="{{ $variable->name }}">{{ $variable->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <div class="args select" style="display: none">
                    </div>

                    <div class="args condition" style="display: none">
                        <select class="operator" onchange="">
                            <option value="=">equals</option>
                            <option value="!=">does not equal</option>
                            <option value=">">greater</option>
                            <option value="<">lesser</option>
                        </select>

                        <input type="text" class="value" onchange="">
                    </div>

                    <div class="args aggregation" style="display: none">
                        <select class="value" onchange="">
                            <option value="AVG">Average</option>
                            <option value="MEDIAN">Median</option>
                            <option value="SUM">Sum</option>
                            <option value="MIN">Min</option>
                            <option value="MAX">Max</option>
                        </select>
                    </div>

                    <div class="args order" style="display: none">
                        <select class="value">
                            <option value="DESC">Descending</option>
                            <option value="ASC">Ascending</option>
                        </select>
                    </div>

                    <div class="args group" style="display: none">
                    </div>

                </td>
                <td>
                    <button type="button" onclick="removeElement(this.parentNode.parentNode);submitQuery();">
                        - Remove
                    </button>
                </td>
            </tr>
        </table>

        <button type="submit" accesskey="r" title="Keyboard shortcut: [Alt]+r or [Control]+[Alt]+r">
            Refresh
        </button>
    </form>
</div>

{{-- ADVANDED QUERY --}}
<div id="advanced" class="mode" style="display: none">
    <textarea id="query" class="full-width">SELECT * FROM ?</textarea>

    <button onclick="runQuery(document.querySelector('#query').value, true)">Run query</button>
</div>

<h5>Dataset</h5>

<p id="sql-output"></p>
