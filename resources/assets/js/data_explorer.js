// global dependencies
Chart = require('chart.js');

window.output = function (callback) {
    return callback(document.querySelector('#sql-output'));
};

window.chart = function (labels, data) {
    return new Chart(document.querySelector('#chart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: data
        },
        options: {}
    });
};

window.renderError = function (error, verbose) {
    output(function (element) {
        element.innerText = verbose ? exception : 'Something went wrong with the query. Please refresh and try again or contact administrator if it keeps repeating.';
        element.className = 'red';
    });
};

window.renderChart = function (results) {
    if (!results.length) {
        return;
    }

    var chartDatasets = Object.keys(results[0]).map(function (key) {
        return {
            label: key,
            data: results.map(function (row) {
                return row[key];
            })
        };
    });

    var labels = new Array(chartDatasets[0].data.length);
    chart(labels, chartDatasets);
};

window.renderTable = function (results) {
    var html;

    if (results.length) {
        html = '<h5>Results <em>(' + results.length + ')</em></h5>';

        // format table
        html += '<table>';

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
    output(function (element) {
        element.className = '';
        element.innerHTML = html;
    });
};

window.addElementTo = function (sourceSelector, targetSelector) {
    // clone with all children
    var elem = document.querySelector(sourceSelector).cloneNode(true);

    return document.querySelector(targetSelector).appendChild(elem);
};

window.removeElement = function (element) {
    return element.parentNode.removeChild(element);
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
                var conditionValue;

                // put in quotes
                if (value === '') {
                    conditionValue = '""';
                }
                else {
                    conditionValue = isNaN(value) ? '"' + value + '"' : value;
                }
                
                conditions.push(variable + rule.querySelector('.condition .operator').value +
                    conditionValue);
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
        ' FROM dataset';

    // conditions
    if (conditions.length) {
        sql += ' WHERE ' + conditions.join(', ');
    }

    if (group.length) {
        sql += ' GROUP BY ' + group.join(', ');
    }

    if (order.length) {
        sql += ' ORDER BY ' + order.join(', ');
    }

    console.log(sql);

    return sql;
};

window.runQuery = function (query, verbose) {
    if (!query) {
        return;
    }

    var r = new XMLHttpRequest();

    r.open('POST', '/api/snapshots/' + window.snapshot_id + '/query');
    r.setRequestHeader('Content-Type', 'application/json');
    r.setRequestHeader('Authorization', 'Bearer ' + window.api_token);

    r.onload = function() {
        if (r.status === 200) {
            var results = JSON.parse(r.responseText);

            renderChart(results.data);
            renderTable(results.data);
        }

        // error
        else if (r.status !== 200) {
            renderError(r.responseText, verbose);
        }
    };

    r.send(JSON.stringify({ query: query }));
};

window.submitQuery = function () {
    return runQuery(makeQuery());
};

// TODO FIXME
// window.exportToCSV = function (query) {
//     alasql.promise('SELECT * INTO CSV("query_results.csv", { separator: ","}) FROM (' + query + ')', [dataset])
//         .then(function(){
//              console.log('File was saved');
//         }).catch(function(error){
//              console.log('Error:', error);
//         });
// };

window.onload = function () {
    // since default is simple mode
    submitQuery();
};
