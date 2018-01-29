// dependencies
var alasql = require('alasql');
var Chart = require('chart.js');

// helpers
window.qs = function (selector) {
    return document.querySelector(selector);
};

window.qsa = function (selector) {
    return document.querySelectorAll(selector);
};

// meat and potatoes
window.makeQuery = function () {
    var sql, select;

    sql = 'SELECT ';

    select = query.select
        .map(function(item) {
            if (item.aggregations.length) {
                return item.aggregations
                    .map(function (agg) {
                        return agg + '(' + item.name + ')';
                    })
                    .join(', ');
            }

            // just the name by default
            return item.name;
        })
        .join(', ');

    sql += select.length ? select : '*';

    sql += ' FROM ?';

    if (query.group_by) {
        sql += ' GROUP BY ' + query.group_by;
    }

    if (query.order_by) {
        sql += ' ORDER BY ' + query.order_by.field + ' ' + query.order_by.order_value;
    }
    console.log(sql);
    return sql;
};

window.toggleFilter = function (filterName, checked) {
    var aggs = document.querySelector('#' + filterName + ' .aggregations');

    // add
    if (checked) {
        query.select.push({ name: filterName, aggregations: [] });

        // show aggs
        aggs.style.visibility = 'visible';
    }
    // remove
    else {
        query.select = query.select.filter(function (item) {
            return item.name !== filterName;
        });
        
        // hide and reset aggs
        aggs.style.visibility = 'hidden';

        Array.from(aggs.querySelectorAll('input'))
            .map(function (agg) {
                agg.checked = false;
            });
    }

    runQuery(makeQuery());
};

window.toggleAggregation = function (filterName, aggregation, checked) {
    // select correct filter
    var filter = query.select.filter(function (shownFilter) {
        return filterName == shownFilter.name;
    })[0];

    if (checked) {
        filter.aggregations.push(aggregation);
    }
    else {
        filter.aggregations.splice(filter.aggregations.indexOf(aggregation));
    }

    runQuery(makeQuery());
};

window.setOrderBy = function (field, order_value) {
    var element = document.getElementById('order_by');

    if (field) {
        element.style.visibility = 'visible';

        query.order_by = {
            field: field,
            order_value: order_value,

        };
    }
    else {
        element.style.visibility = 'hidden';
        query.order_by = null;
    }

    runQuery(makeQuery());
};

window.setGroupBy = function (field) {
    var element = document.getElementById('group_by');

    if (field) {
        element.style.visibility = 'visible';
    }
    else {
        element.style.visibility = 'hidden';
    }

    query.group_by = field;

    runQuery(makeQuery());
};

window.showOneOfMany = function (needleId, haystack) {
    var element;

    for (var i = 0; i < haystack.length; i++) {
        element = haystack[i];

        if (element.id === needleId) {
            element.style.display = 'block';
        }
        else {
            element.style.display = 'none';
        }
    }
};

window.runQuery = function (query) {
    var outputElement = document.querySelector('#sql-output');
    if (!query) {
        return false;
    }

    try {
        results = alasql(query, [dataset]);
    }
    catch (exception) {
        outputElement.innerText = exception;
        outputElement.className = 'red';

        return false;
    }

    console.log(results);

    // format table
    var html = '<table>';

    // add header row
    html += '<tr><th>' +
        Object.keys(results[0]).join('</th><th>') +
        '</th></tr>';

    // add data
    for (var i = 0; i < results.length; i++) {
        html += '<tr><td>' +
            Object.values(results[i]).join('</td><td>') +
            '</td></tr>';
    }

    html += '<table>';

    // output
    outputElement.className = '';
    outputElement.innerHTML = html;

    return drawChart(results);
};

window.drawChart = function (results) {
    if (!results.length) {
        return false;
    }

    var chartDatasets = Object.keys(results[0]).map(function (key) {
        return {
            label: key,
            data: results.map(function (row) {
                return row[key];
            })
        };
    });

    if (!chart) {
        chart = new Chart(document.querySelector('#chart'), {
            type: 'line',
            data: {
                datasets: []
            },
            options: {}
        });
    }

    chart.data.labels = new Array(chartDatasets[0].data.length);
    chart.data.datasets = chartDatasets;
    chart.update();

    return true;
};

window.onload = function () {
    // shared data
    query = { select: [] };
    chart = null;

    // since default is simple mode
    runQuery(makeQuery());
};
