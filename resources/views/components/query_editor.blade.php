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
                // hide all
                var elements = document.querySelectorAll('.args');

                for (var i = 0; i < elements.length; i++) {
                    elements[i].style.display = 'none';
                }

                // show one
                this.parentNode.parentNode
                    .querySelector('.' + this.value).style.display = '';
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

            <div class="args group" style="display: none">
            </div>

        </td>
        <td>
            <button onclick="
            this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);
            ">- Remove</button>
        </td>
    </tr>
</table>