// dependencies
var alasql = require('alasql');
var Chart = require('chart.js');

// global data
query = {
    select: [],
    conditions: []
};
chart = null;

// helpers
window.qs = function (selector) {
    return document.querySelector(selector);
};

window.qsa = function (selector) {
    return document.querySelectorAll(selector);
};

window.addElementTo = function (sourceSelector, targetSelector) {
    // clone with all children
    var elem = document.querySelector(sourceSelector).cloneNode(true);

    return document.querySelector(targetSelector).appendChild(elem);
};

window.showOneOfMany = function (needleSelector, haystackSelector, context) {
    context = context || document;

    var element = context.querySelector(needleSelector);
    var haystack = context.querySelectorAll(haystackSelector);

    // hide all
    for (var i = 0; i < haystack.length; i++) {
        haystack[i].style.display = 'none';
    }

    // show one
    element.style.display = 'block';
};

// meat and potatoes
window.makeQuery = function () {
    // fields to select
    var rules = document.querySelectorAll('.rule');

    var select = [], conditions = [], order = [], group = [];

    // start from the first one
    for (var i = 1; i < rules.length; i++) {
        var rule = rules[i];
        var type = rule.querySelector('.type').value;
        var variable = rule.querySelector('.variable').value;
        
        var valueElement = rule.querySelector('.' + type + ' .value');
        var value = valueElement ? valueElement.value : '';

        switch (type) {
            case 'select':
                select.push(variable);
                break;
            case 'aggregation':
                select.push(value +'(' + variable + ')');
                break;
            case 'condition':
                conditions.push(variable +rule.querySelector('.condition .operator').value +
                    (isNaN(value) ? '"' + value + '"' : value));
                break;
            case 'order':
                order.push(variable + ' ' + value);
                break;
            case 'group':
                group.push(variable);
                break;
            default:
                break;
        }
    }

    var sql = 'SELECT ' +
        (select.length ? select.join(', ') : '*') +
        ' FROM ?';

    // conditions
    if (conditions.length) {
        sql += ' WHERE ' + conditions.join(', ');
    }

    if (group.length) {
        sql += ' GROUP BY ' + group.join(', ');
    }

    if (order.length) {
        sql += ' ORDER BY ' + order.join(', ');;
    }

    console.log(sql);
    
    return sql;
};

window.runQuery = function (query) {
    var outputElement, html;

    outputElement = document.querySelector('#sql-output');
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

    if (results.length) {
        // format table
        html = '<table>';

        // add header row
        html += '<tr><th>#</th><th>' +
            Object.keys(results[0]).join('</th><th>') +
            '</th></tr>';

        // add data
        for (var i = 0; i < results.length; i++) {
            html += '<tr><td>' + (i+1) + '</td><td>' +
                Object.values(results[i]).join('</td><td>') +
                '</td></tr>';
        }

        html += '<table>';
    }
    else {
        html = '<p>Nothing found.</p>';
    }

    // set output
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
    // since default is simple mode
    runQuery(makeQuery());
};
